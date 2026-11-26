<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getTintedLogoPath(?string $logoPath, string $colorHex = '2563eb'): ?string
    {
        if (!$logoPath) {
            return null;
        }
        $fullPath = storage_path('app/public/' . ltrim($logoPath, '/'));
        return file_exists($fullPath) ? $fullPath : null;
    }

    public static function getTransparentSignaturePath(?string $signaturePath): ?string
    {
        if (!$signaturePath) {
            return null;
        }
        $fullPath = storage_path('app/public/' . ltrim($signaturePath, '/'));
        return file_exists($fullPath) ? $fullPath : null;
    }
}
