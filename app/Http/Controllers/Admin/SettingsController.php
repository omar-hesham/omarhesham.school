<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    // Settings stored in cache (or a simple key-value DB table in production)
    protected array $allowedKeys = [
        'minor_ads_enabled',
        'require_parent_consent',
        'content_moderation_enabled',
        'maintenance_mode',
        'registration_open',
    ];

    public function index()
    {
        $settings = collect($this->allowedKeys)->mapWithKeys(
            fn($key) => [$key => Cache::get("setting:{$key}", $this->defaults()[$key] ?? null)]
        );

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate(
            collect($this->allowedKeys)->mapWithKeys(
                fn($k) => [$k => ['nullable', 'in:0,1,true,false']]
            )->toArray()
        );

        foreach ($data as $key => $value) {
            Cache::forever("setting:{$key}", (bool) $value);
        }

        AuditLog::record(auth()->id(), 'settings_updated', null, null, $data);

        return back()->with('status', __('admin.settings_saved'));
    }

    protected function defaults(): array
    {
        return [
            'minor_ads_enabled'          => false,
            'require_parent_consent'     => true,
            'content_moderation_enabled' => true,
            'maintenance_mode'           => false,
            'registration_open'          => true,
        ];
    }
}
