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

        if ($settings === null) {
            $settings = \App\Models\Pengaturan::pluck('nilai', 'nama_pengaturan')->toArray();
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
        return \App\Models\Pengaturan::updateOrCreate(
            ['nama_pengaturan' => $key],
            ['nilai' => $value, 'tipe' => $type]
        );
    }
}