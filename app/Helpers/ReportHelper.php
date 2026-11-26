<?php

namespace App\Helpers;

class ReportHelper
{
    public static function getCurrentPeriod(): string
    {
        $month = (int) date('n');
        $year = (int) date('Y');
        $semester = $month >= 7 ? 'Ganjil' : 'Genap';
        $academicYear = $month >= 7 ? "{$year}/" . ($year + 1) : ($year - 1) . "/{$year}";

        return "Semester {$semester} {$academicYear}";
    }

    public static function getGradeLetter(float $score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }
}
