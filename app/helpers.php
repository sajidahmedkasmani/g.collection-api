<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key with optional default fallback.
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('cms_image')) {
    /**
     * Get full URL for a CMS-uploaded image, or fallback to default.
     */
    function cms_image(string $key, $default = null): ?string
    {
        $val = Setting::get($key);
        if (!$val) {
            return $default;
        }

        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://') || str_starts_with($val, '//')) {
            return $val;
        }

        if (str_starts_with($val, 'uploads/')) {
            return asset($val);
        }

        return asset('uploads/' . ltrim($val, '/'));
    }
}

if (!function_exists('clean_phone')) {
    /**
     * Remove non-numeric characters for phone / whatsapp links.
     */
    function clean_phone(?string $phone): string
    {
        if (!$phone) {
            return '';
        }
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '92' . substr($digits, 1);
        }
        return $digits;
    }
}

if (!function_exists('whatsapp_link')) {
    /**
     * Generate WhatsApp chat URL.
     */
    function whatsapp_link(?string $number = null, string $text = ''): string
    {
        $num = $number ?: setting('whatsapp_number', '923347779231');
        $clean = clean_phone($num);
        $url = "https://wa.me/{$clean}";
        if ($text) {
            $url .= '?text=' . urlencode($text);
        }
        return $url;
    }
}
