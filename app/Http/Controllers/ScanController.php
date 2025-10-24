<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function show()
    {
        $settings = DB::table('app_settings')->first();
        return view('scan', compact('settings'));
    }

    public function identify(Request $request)
    {
        $request->validate(['frame' => 'required|image|mimes:jpeg,png,jpg|max:5120']);

        $threshold = optional(DB::table('app_settings')->first())->threshold ?? 0.40;

        $resp = Http::withHeaders([
                // include if your FastAPI enforces an API key; otherwise remove
                'x-api-key' => config('services.face.key'),
            ])->attach(
                'image',
                fopen($request->file('frame')->getRealPath(), 'r'),
                $request->file('frame')->getClientOriginalName()
            )->post(
                rtrim(config('services.face.url'), '/').'/identify',
                ['threshold' => $threshold]
            );

        if (!$resp->ok()) {
            return response()->json(['ok' => false, 'error' => $resp->body()], 422);
        }

        // FastAPI returns: { ok, match, score, threshold }
        $match = $resp->json('match');
        $score = $resp->json('score');
        $name  = $match ? optional(\App\Models\Employee::find($match))->name : null;

        // Optional: record attendance here if $match !== null

        return response()->json([
            'ok' => true,
            'user_id' => $match,
            'name' => $name,
            'score' => $score,
        ]);
    }
}