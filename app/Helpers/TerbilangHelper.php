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

    public static function rupiah($number): string
    {
        $text = trim(preg_replace('/\s+/', ' ', self::make($number)));
        return ($text !== '' ? $text : 'Nol') . ' Rupiah';
    }

    public static function toRoman($number): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        return $map[(int) $number] ?? (string) $number;
    }
}
