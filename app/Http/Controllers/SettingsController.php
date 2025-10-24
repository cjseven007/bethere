<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('app_settings')->first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'threshold' => 'required|numeric|min:0|max:1',
            'interval'  => 'required|integer|min:1|max:60',
        ]);

        $exists = DB::table('app_settings')->count() > 0;
        if ($exists) {
            DB::table('app_settings')->update($data);
        } else {
            DB::table('app_settings')->insert($data);
        }

        return back()->with('ok', 'Settings saved.');
    }
}