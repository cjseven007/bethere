<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function show()
    {
        // load settings (simple single-row table)
        $settings = DB::table('app_settings')->first();
        return view('scan', compact('settings'));
    }

    public function identify(Request $request)
    {
        $request->validate(['frame' => 'required|image|mimes:jpeg,png,jpg|max:5120']);

        $threshold = optional(DB::table('app_settings')->first())->threshold ?? 0.40;

        $resp = Http::withHeaders([
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

        $userId = $resp->json('user_id');
        $score  = $resp->json('score');
        $name   = $userId ? optional(\App\Models\User::find($userId))->name : null;

        // Optional: write attendance here

        return response()->json(['ok' => true, 'user_id' => $userId, 'name' => $name, 'score' => $score]);
    }
}
