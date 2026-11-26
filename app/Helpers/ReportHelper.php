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

    public static function calculatePredicate($score, $kkm = 75): string
    {
        if ($score === null || $score === '') {
            return '-';
        }
        $score = (float) $score;
        $kkm = (float) $kkm;

        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= $kkm) return 'C';
        return 'D';
    }

    public static function getGradeRanges(): array
    {
        return [
            (object) ['letter' => 'A', 'min_score' => 90, 'description' => 'Sangat Baik'],
            (object) ['letter' => 'B', 'min_score' => 80, 'description' => 'Baik'],
            (object) ['letter' => 'C', 'min_score' => 75, 'description' => 'Cukup'],
            (object) ['letter' => 'D', 'min_score' => 0,  'description' => 'Perlu Bimbingan'],
        ];
    }

    public static function getMultipleIntelligences(): array
    {
        return [
            'Linguistik',
            'Logis-Matematis',
            'Visual-Spasial',
            'Musikal',
            'Kinestetik',
            'Interpersonal',
            'Intrapersonal',
            'Naturalis',
        ];
    }

    public static function getST30Strengths(): array
    {
        return [
            'Administrator', 'Ambassador', 'Analyst', 'Arranger', 'Caretaker',
            'Commander', 'Communicator', 'Creator', 'Designer', 'Developer',
            'Educator', 'Evaluator', 'Explorer', 'Helper', 'Ideator',
            'Journalist', 'Marketer', 'Mediator', 'Motivator', 'Operator',
            'Organizer', 'Producer', 'Quality Controller', 'Researcher', 'Restorer',
            'Strategist', 'Synthesizer', 'Trainer', 'Visionary', 'Writer',
        ];
    }

    public static function getSurahs(): array
    {
        return [
            'An-Naba', 'An-Nazi\'at', 'Abasa', 'At-Takwir', 'Al-Infitar',
            'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj', 'At-Tariq', 'Al-A\'la',
            'Al-Ghasyiyah', 'Al-Fajr', 'Al-Balad', 'Asy-Syams', 'Al-Lail',
            'Ad-Duha', 'Al-Insyirah', 'At-Tin', 'Al-\'Alaq', 'Al-Qadr',
            'Al-Bayyinah', 'Az-Zalzalah', 'Al-\'Adiyat', 'Al-Qari\'ah', 'At-Takasur',
            'Al-\'Asr', 'Al-Humazah', 'Al-Fil', 'Quraisy', 'Al-Ma\'un',
            'Al-Kausar', 'Al-Kafirun', 'An-Nasr', 'Al-Lahab', 'Al-Ikhlas',
            'Al-Falaq', 'An-Nas',
        ];
    }

    public static function getIbadahItems(): array
    {
        return [
            ['id' => 'sholat_subuh', 'label' => 'Sholat Subuh'],
            ['id' => 'sholat_dzuhur', 'label' => 'Sholat Dzuhur'],
            ['id' => 'sholat_ashar', 'label' => 'Sholat Ashar'],
            ['id' => 'sholat_maghrib', 'label' => 'Sholat Maghrib'],
            ['id' => 'sholat_isya', 'label' => 'Sholat Isya'],
            ['id' => 'sholat_dhuha', 'label' => 'Sholat Dhuha'],
            ['id' => 'tilawah_alquran', 'label' => 'Tilawah Al-Qur\'an'],
            ['id' => 'dzikir_pagi_petang', 'label' => 'Dzikir Pagi & Petang'],
        ];
    }

    public static function getFoundationCompetencies(): array
    {
        return [
            'Beriman & Bertakwa',
            'Berpikir Kritis',
            'Kreatif & Inovatif',
            'Mandiri & Bertanggung Jawab',
            'Komunikasi & Kolaborasi',
        ];
    }

    public static function getMutuDescription(string $code, string $type = 'Akademik'): string
    {
        return match (strtoupper($code)) {
            'A' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            default => 'Perlu Bimbingan',
        };
    }
}
