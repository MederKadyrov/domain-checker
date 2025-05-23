<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/domain-check', function (Request $request) {
    $validated = $request->validate([
        'domains' => 'required|array',
        'domains.*' => 'required|string',
    ]);

    $results = collect($validated['domains'])->map(function ($domain) {
        $domain = strtolower(trim($domain));

        if (!preg_match('/^[a-z0-9\-]+\.[a-z]{2,}$/', $domain)) {
            return ['domain' => $domain, 'status' => 'Невалидный домен'];
        }

        $isAvailable = rand(0, 1); // фейковая проверка
        return [
            'domain' => $domain,
            'status' => $isAvailable
                ? 'Доступен для регистрации'
                : 'Занят до ' . now()->addDays(rand(30, 365))->format('Y-m-d'),
        ];
    });

    return response()->json(['results' => $results]);
});
