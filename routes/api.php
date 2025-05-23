<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;

Route::post('/register', function (Request $request) {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return $user;
});

Route::post('/login', function (Request $request) {
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid login'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});

use App\DTO\DomainResultDTO;

Route::middleware('auth:sanctum')->post('/domain-check', function (Request $request) {
    $validated = $request->validate([
        'domains' => 'required|array',
        'domains.*' => 'required|string',
    ]);

    $results = collect($validated['domains'])->map(function ($domain) {
        $domain = strtolower(trim($domain));

        if (!preg_match('/^[a-z0-9\-]+\.[a-z]{2,}$/', $domain)) {
            return new DomainResultDTO($domain, 'Невалидный домен');
        }

        $isAvailable = rand(0, 1);
        $status = $isAvailable
            ? 'Доступен для регистрации'
            : 'Занят до ' . now()->addYears(1)->format('Y-m-d');

        return new DomainResultDTO($domain, $status);
    });

    return response()->json([
        'results' => $results->map->toArray()->values()
    ]);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
