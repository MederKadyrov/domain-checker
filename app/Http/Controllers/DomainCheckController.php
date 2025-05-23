<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainCheckController extends Controller
{
    public function check(Request $request)
    {
        $data = $request->validate([
            'domains' => 'required|array',
            'domains.*' => 'required|string'
        ]);

        $results = [];

        foreach ($data['domains'] as $domain) {
            $domain = strtolower(trim($domain));
            $valid = preg_match('/^[a-z0-9\-]+\.(com|net|org)$/', $domain);

            if (! $valid) {
                $results[$domain] = ['status' => 'invalid'];
                continue;
            }

            if (strlen($domain) % 2 === 0) {
                $results[$domain] = [
                    'status' => 'taken',
                    'expires_at' => now()->addDays(rand(30, 365))->toDateString(),
                ];
            } else {
                $results[$domain] = ['status' => 'available'];
            }
        }

        return response()->json(['results' => $results]);
    }
}
