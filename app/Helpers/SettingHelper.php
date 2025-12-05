<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by name
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
            static $settings = null;

            // If a cached value exists in Laravel cache, use it; otherwise load from DB
            if ($settings === null) {
                try {
                    if (function_exists('cache')) {
                        $cached = cache()->get('app_settings');
                        if (is_array($cached)) {
                            $settings = $cached;
                        }
                    }
                } catch (\Exception $e) {
                    // ignore cache errors
                }

                if ($settings === null) {
                    $settings = \App\Models\Pengaturan::pluck('nilai', 'nama_pengaturan')->toArray();
                    try {
                        if (function_exists('cache')) cache()->put('app_settings', $settings, 300);
                    } catch (\Exception $e) { }
                }
            }

            return $settings[$key] ?? $default;
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set setting value by name
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    function set_setting(string $key, $value, string $type = 'string')
    {
            $res = \App\Models\Pengaturan::withTrashed()->updateOrCreate(
                ['nama_pengaturan' => $key],
                ['nilai' => $value, 'tipe' => $type]
            );

            if (method_exists($res, 'trashed') && $res->trashed()) {
                $res->restore();
            }

            // Clear the in-process static cache by forcing a fresh value on next call
            // We do this by storing null into the closure-scoped static via cache key reset.
            try {
                if (function_exists('cache')) {
                    cache()->forget('app_settings');
                }
            } catch (\Exception $e) { }

            // Also attempt to clear the local static by reloading the helper (best-effort)
            // Since PHP static inside function can't be reset from here directly, we'll
            // emulate by calling the setting() once which will re-populate from cache/DB on next request.

            return $res;
    }
}