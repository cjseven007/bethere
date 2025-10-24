<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\FaceEmbedding;
use App\Support\OrgContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function embed(Request $request, Employee $employee)
    {
        $org = OrgContext::current();
        abort_if(!$org || $employee->organization_id !== $org->id, 403);

        // Must be admin of this org
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user
            ->organizations()
            ->where('organizations.id', $org->id)
            ->wherePivot('role','admin')
            ->exists();
        abort_if(!$isAdmin, 403);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:5120',
        ]);

        // Call Python Cloud Run (buffalo model fixed)
        $resp = Http::withHeaders([
                    'x-api-key' => config('services.face.key'),
                ])
                ->attach('image', fopen($request->file('image')->getRealPath(), 'r'), 'face.jpg')
                ->post(rtrim(config('services.face.url'), '/').'/embed', [
                    'model' => 'buffalo_l',
                ]);

        if (!$resp->ok()) {
            return response()->json(['ok' => false, 'error' => $resp->body()], 422);
        }

        $vector = $resp->json('embedding'); // expect float[] from Python

        // upsert
        FaceEmbedding::updateOrCreate(
            ['employee_id' => $employee->id, 'model' => 'buffalo_l'],
            ['vector' => $vector]
        );

        return response()->json(['ok' => true]);
    }
}

