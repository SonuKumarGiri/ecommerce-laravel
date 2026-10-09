<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method', 'store_logo', 'store_favicon');
        
        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');
            $filename = \Illuminate\Support\Str::random(25) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $data['store_logo'] = 'uploads/settings/' . $filename;
        }
        
        if ($request->hasFile('store_favicon')) {
            $file = $request->file('store_favicon');
            $filename = \Illuminate\Support\Str::random(25) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $data['store_favicon'] = 'uploads/settings/' . $filename;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Settings updated successfully.');
    }
}