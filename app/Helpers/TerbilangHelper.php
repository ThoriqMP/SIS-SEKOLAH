<?php

namespace App\Helpers;

class TerbilangHelper
{
    public static function make($number): string
    {
        $number = abs((int) $number);
        $words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) return $words[$number];
        if ($number < 20) return self::make($number - 10) . ' Belas';
        if ($number < 100) return self::make(intdiv($number, 10)) . ' Puluh ' . self::make($number % 10);
        if ($number < 200) return 'Seratus ' . self::make($number - 100);
        if ($number < 1000) return self::make(intdiv($number, 100)) . ' Ratus ' . self::make($number % 100);
        if ($number < 2000) return 'Seribu ' . self::make($number - 1000);
        if ($number < 1000000) return self::make(intdiv($number, 1000)) . ' Ribu ' . self::make($number % 1000);
        if ($number < 1000000000) return self::make(intdiv($number, 1000000)) . ' Juta ' . self::make($number % 1000000);

        return (string) $number;
    }
}
