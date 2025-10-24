<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EmployeesController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        // Expect an image (from upload or camera blob)
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:6144', // 6MB
        ]);

        $faceUrl = rtrim(config('services.face.url'), '/') . '/embed';

        // Send to FastAPI /embed
        $resp = Http::withHeaders([
                'x-api-key' => config('services.face.key'), // remove if FastAPI has no auth
            ])->attach(
                'image',
                fopen($request->file('image')->getRealPath(), 'r'),
                $request->file('image')->getClientOriginalName()
            )->post($faceUrl);

        if (!$resp->ok()) {
            return response()->json(['ok' => false, 'error' => $resp->body()], 422);
        }

        // We expect FastAPI to return an "embedding" JSON array (length 512).
        $embedding = $resp->json('embedding');

        if (!is_array($embedding) || count($embedding) !== 512) {
            // If your FastAPI returns only base64, add JSON array support there (recommended).
            return response()->json([
                'ok' => false,
                'error' => 'Embedding array not present or invalid length. Ensure FastAPI /embed returns {"embedding":[...512 floats...]}'
            ], 422);
        }

        // Upsert into JSON column (unique: employee_id + model)
        $model = 'buffalo_l';
        $now = now();

        DB::table('face_embeddings')->updateOrInsert(
            ['employee_id' => $employee->id, 'model' => $model],
            ['vector' => json_encode(array_map('floatval', $embedding)), 'updated_at' => $now, 'created_at' => $now]
        );

        return response()->json(['ok' => true]);
    }
}

