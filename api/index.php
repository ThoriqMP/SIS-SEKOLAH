<?php

/**
 * Vercel Serverless Entry Point untuk Laravel (Modul Sekolah)
 * Filesystem Vercel bersifat read-only kecuali /tmp.
 */
$tmpDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

$publicIndex = __DIR__ . '/../public/index.php';

if (!file_exists($publicIndex)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Laravel public/index.php tidak ditemukan. Pastikan kerangka utama Laravel (public/, bootstrap/, config/, composer.json) sudah dilengkapi.';
    exit;
}

require $publicIndex;
