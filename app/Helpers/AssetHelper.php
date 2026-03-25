<?php

namespace App\Helpers;

class AssetHelper
{
    /**
     * Get asset path with file hash for cache busting
     * @param string $path Asset path (e.g., 'css/admin.css')
     * @return string Full path with hash version
     */
    public static function assetVersion($path)
    {
        $fullPath = public_path($path);
        
        if (!file_exists($fullPath)) {
            return asset($path);
        }
        
        $hash = hash_file('md5', $fullPath);
        return asset($path) . '?v=' . substr($hash, 0, 8);
    }
}
