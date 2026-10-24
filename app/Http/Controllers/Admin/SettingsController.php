<?php

namespace App\Http\Controllers\Admin;

use App\Admin\SettingsSchema;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings', ['groups' => SettingsSchema::groups(), 'values' => Setting::all_cached()]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (SettingsSchema::fields() as $key => $f) {
            $rules[$key] = match ($f['type']) {
                'email' => ['nullable', 'email', 'max:255'],
                'url' => ['nullable', 'url', 'max:255'],
                'markdown', 'list', 'textarea' => ['nullable', 'string', 'max:50000'],
                default => ['nullable', 'string', 'max:255'],
            };
        }
        Setting::put($request->validate($rules));

        return back()->with('status', 'Settings saved.');
    }
}
