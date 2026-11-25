<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor Probing PBL - {{ $report->student->nama_siswa }}</title>
    <style>
        @page { margin: 0.8cm 1cm 1cm 1cm; }
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; color: #222222; line-height: 1.35; font-size: 7.5pt; background-color: #ffffff; }

        /* Typography */
        .h1 { font-size: 13pt; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 12px; color: #4c1d95; border-bottom: 2px solid #7c3aed; padding-bottom: 6px; }
        .font-bold { font-weight: bold; color: #000000; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }

        /* Student Info Box */
        .info-box { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-box td { padding: 2px 4px; vertical-align: top; color: #222222; border: none; }

        /* Tables */
        table.main-table { width: 100%; border-collapse: collapse; table-layout: fixed; border: 1px solid #ddd6fe; margin-bottom: 0; }
        table.main-table th { background-color: #7c3aed; color: #ffffff; padding: 4px 5px; font-size: 7pt; border: 1px solid #7c3aed; text-transform: uppercase; text-align: center; }
        table.main-table td { border: 1px solid #ddd6fe; padding: 4px 5px; word-wrap: break-word; vertical-align: top; font-size: 7pt; }

        .section-header {
            background-color: #f5f3ff; color: #4c1d95; padding: 4px; font-weight: bold;
            text-align: center; text-transform: uppercase; font-size: 7.5pt;
            border: 1px solid #ddd6fe; border-top: none;
        }

        .potensi-grid { width: 100%; border-collapse: collapse; border: 1px solid #ddd6fe; border-top: none; }
        .potensi-grid th { background-color: #7c3aed; color: #ffffff; padding: 4px; font-size: 6.5pt; border: 1px solid #7c3aed; text-transform: uppercase; text-align: center; }
        .potensi-grid td { border: 1px solid #ddd6fe; padding: 4px; text-align: center; font-size: 7pt; vertical-align: middle; height: 20px; }

        /* Competency Matrix */
        table.competency-table { width: 100%; border-collapse: collapse; border: 1px solid #ddd6fe; }
        table.competency-table th { background-color: #4c1d95; color: #ffffff; padding: 4px 6px; font-size: 6.5pt; border: 1px solid #4c1d95; text-align: center; }
        table.competency-table td { border: 1px solid #ddd6fe; padding: 3px 5px; font-size: 6.5pt; vertical-align: middle; }
        .score-bar-wrap { width: 100%; height: 8px; background-color: #ede9fe; border-radius: 4px; }
        .score-bar-fill { height: 8px; background-color: #7c3aed; border-radius: 4px; }

        /* Dokumentasi */
        table.image-grid { width: 100%; border-collapse: collapse; border: 1px solid #ddd6fe; border-top: none; table-layout: fixed; }
        table.image-grid td { width: 33.33%; padding: 6px; text-align: center; border: 1px solid #ddd6fe; vertical-align: middle; height: 190px; }
        .img-container { max-width: 100%; max-height: 180px; width: auto; height: auto; border-radius: 4px; display: inline-block; }
        .img-caption { font-size: 6.5pt; color: #555555; margin-top: 4px; text-align: center; font-style: italic; }

        /* Layout Helpers */
        .page-break { page-break-after: always; }
        .page-break-before { page-break-before: always; }
        .no-break { page-break-inside: avoid; }

        /* Footer */
        .footer { position: fixed; bottom: -0.2cm; left: 0; right: 0; display: table; width: 100%; font-size: 7pt; color: #777777; border-top: 1px solid #e5e7eb; padding-top: 3px; }

        /* Signatures */
        .signature-container { margin-top: 30px; page-break-inside: avoid; }
        .sig-table { width: 100%; margin-top: 0; border-collapse: collapse; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding-bottom: 5px; color: #222222; }
        .sig-line { border-top: 1.5px solid #000000; width: 140px; margin: 40px auto 5px auto; }

        /* Watermark */
        .watermark { position: fixed; top: 30%; left: 10%; width: 80%; text-align: center; opacity: 0.04; z-index: -1000; }
        .watermark img { width: 400px; height: auto; }

        /* Notes section */
        .notes-box { border: 1px solid #ddd6fe; border-radius: 4px; padding: 8px 10px; min-height: 40px; background-color: #fafafa; }
    </style>
</head>
<body>
    @php
        $logoPath = null;
        if ($school && $school->logo_path && file_exists(storage_path('app/public/' . $school->logo_path))) {
            $logoPath = storage_path('app/public/' . $school->logo_path);
        } elseif (file_exists(storage_path('app/public/logos/sayyidah-school-logo.png'))) {
            $logoPath = storage_path('app/public/logos/sayyidah-school-logo.png');
        } elseif (file_exists(storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png'))) {
            $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        }

        $tahunPelajaran = '';
        if (preg_match('/(\d{4}\/\d{4})/', $report->period, $matches)) {
            $tahunPelajaran = $matches[1];
        } else {
            $tahunPelajaran = now()->format('Y') . '/' . now()->addYear()->format('Y');
        }

        // Eager-load relations jika belum
        $report->loadMissing(['probingActivities.activityGrades', 'probingActivities.activityDocumentations', 'competencyAssessments']);
    @endphp

    @if($logoPath)
        <div class="watermark"><img src="{{ $logoPath }}" alt="Watermark Logo"></div>
    @endif

    {{-- Judul & Info Siswa --}}
    <div class="h1">LAPORAN PROJECT BASED LEARNING (PBL)</div>
    <table class="info-box">
        <tr>
            <td width="15%"><strong>Nama Siswa</strong></td><td width="2%">:</td><td width="43%" class="font-bold">{{ strtoupper($report->student->nama_siswa) }}</td>
            <td width="15%"><strong>Semester</strong></td><td width="2%">:</td><td width="23%">{{ $report->period }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td><td>:</td><td>{{ $report->student->class }}</td>
            <td><strong>Tahun Ajaran</strong></td><td>:</td><td>{{ $tahunPelajaran }}</td>
        </tr>
    </table>

    {{-- ==================== PROYEK BULANAN ==================== --}}
    @foreach($report->probingActivities as $activity)
        @php
            // === RESOLUSI DATA: Prioritaskan relasi baru, fallback ke JSON lama ===
            $useRelational  = $activity->activityGrades->isNotEmpty();
            $pblData        = $activity->pbl_data ?? [];

            // Potensi: relasi baru → kolom JSON → pbl_data
            $parsePotensi = function($val) {
                if (empty($val)) return [];
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    return is_array($decoded) ? $decoded : [$val];
                }
                return (array)$val;
            };
            $personality  = $parsePotensi($activity->personality  ?? $pblData['personality']  ?? []);
            $intelligence = $parsePotensi($activity->intelligence ?? $pblData['intelligence'] ?? []);
            $strength     = $parsePotensi($activity->strength     ?? $pblData['strength']     ?? []);

            // Deskripsi
            $desc = $activity->description ?? $pblData['description'] ?? '';
            if (is_array($desc)) $desc = implode("\n", $desc);

            // Fase kegiatan dan indikator
            if ($useRelational) {
                // Data dari tabel activity_grades
                $templateKegiatan = null;
                if ($activity->semesterProject && $activity->semesterProject->projectTemplate) {
                    $templateKegiatan = $activity->semesterProject->projectTemplate->kegiatan;
                }

                // Fallback: search template by title matching the activity theme name
                if (empty($templateKegiatan) && !empty($activity->activity_name)) {
                    $tpl = \App\Models\ProjectTemplate::where('title', 'like', '%' . trim($activity->activity_name) . '%')->first();
                    if ($tpl) {
                        $templateKegiatan = $tpl->kegiatan;
                    }
                }

                if (is_string($templateKegiatan)) {
                    $templateKegiatan = json_decode($templateKegiatan, true);
                }

                // Fallback to default categories if template is not found
                if (!is_array($templateKegiatan) || empty($templateKegiatan)) {
                    $templateKegiatan = [
                        ['category' => 'Pembekalan', 'indicators' => []],
                        ['category' => 'Realisasi', 'indicators' => []],
                        ['category' => 'Persiapan Presentasi', 'indicators' => []],
                        ['category' => 'Presentasi', 'indicators' => []],
                    ];
                }

                if (is_array($templateKegiatan) && !empty($templateKegiatan)) {
                    $sortedGradesByPhase = collect();
                    $grouped = $activity->activityGrades->groupBy('phase_name');

                    foreach ($templateKegiatan as $tplCat) {
                        $tplCatName = trim($tplCat['category'] ?? '');
                        if (empty($tplCatName)) continue;

                        $matchedPhaseName = null;
                        foreach ($grouped->keys() as $keyName) {
                            if (strcasecmp($keyName, $tplCatName) === 0) {
                                $matchedPhaseName = $keyName;
                                break;
                            }
                        }

                        if ($matchedPhaseName !== null) {
                            $savedGrades = $grouped->get($matchedPhaseName);
                            $grouped->forget($matchedPhaseName);

                            $sortedGrades = collect();
                            $tplInds = $tplCat['indicators'] ?? [];

                            foreach ($tplInds as $tplInd) {
                                $tplIndName = is_array($tplInd) ? ($tplInd['name'] ?? '') : $tplInd;
                                $tplIndName = trim($tplIndName);
                                if (empty($tplIndName)) continue;

                                foreach ($savedGrades as $idx => $g) {
                                    if (strcasecmp(trim($g->indicator_name), $tplIndName) === 0) {
                                        $sortedGrades->push($g);
                                        $savedGrades->forget($idx);
                                    }
                                }
                            }

                            foreach ($savedGrades as $g) {
                                $sortedGrades->push($g);
                            }

                            $sortedGradesByPhase->put($matchedPhaseName, $sortedGrades);
                        }
                    }

                    foreach ($grouped as $phaseName => $grades) {
                        $sortedGradesByPhase->put($phaseName, $grades);
                    }

                    $gradesByPhase = $sortedGradesByPhase;
                } else {
                    $gradesByPhase = $activity->activityGrades->groupBy('phase_name');
                }
                $totalRows = $activity->activityGrades->count() ?: 1;
            } else {
                // Fallback: parse dari JSON pbl_data (format lama)
                $gradesByPhase = collect();
                $rawKegiatan = $pblData['kegiatan'] ?? [];
                if (!empty($rawKegiatan) && array_keys($rawKegiatan) !== range(0, count($rawKegiatan) - 1)) {
                    // Format lama (associative)
                    $gradeMap = [5=>'Sangat Baik',4=>'Baik',3=>'Cukup',2=>'Kurang',1=>'Buruk'];
                    $fallback = ['pembekalan'=>'Pembekalan','realisasi'=>'Realisasi','persiapan_presentasi'=>'Persiapan Presentasi','presentasi'=>'Presentasi'];
                    foreach ($fallback as $key => $phaseName) {
                        $kegData = $rawKegiatan[$key] ?? [];
                        $rows = collect();
                        foreach ($kegData as $indKey => $score) {
                            $rows->push((object)['indicator_name' => ucwords(str_replace('_', ' ', $indKey)), 'grade' => is_numeric($score) ? ($gradeMap[(int)$score] ?? $score) : $score]);
                        }
                        if ($rows->isNotEmpty()) $gradesByPhase[$phaseName] = $rows;
                    }
                } else {
                    // Format baru di JSON
                    $gradeMap = [5=>'Sangat Baik',4=>'Baik',3=>'Cukup',2=>'Kurang',1=>'Buruk'];
                    foreach ((array)$rawKegiatan as $cat) {
                        if (!is_array($cat)) continue;
                        $phaseName = $cat['category'] ?? 'Kegiatan';
                        $rows = collect();
                        foreach ($cat['indicators'] ?? [] as $ind) {
                            $rawScore = $ind['score'] ?? null;
                            $grade = is_numeric($rawScore) ? ($gradeMap[(int)$rawScore] ?? $rawScore) : $rawScore;
                            $rows->push((object)['indicator_name' => $ind['name'] ?? '-', 'grade' => $grade]);
                        }
                        if ($rows->isNotEmpty()) $gradesByPhase[$phaseName] = $rows;
                    }
                }
                $totalRows = max($gradesByPhase->flatten()->count(), 1);
            }

            // Dokumentasi foto
            if ($useRelational && $activity->activityDocumentations->isNotEmpty()) {
                $docs = $activity->activityDocumentations->take(3);
            } else {
                // Fallback ke images di JSON
                $docs = collect(array_slice($pblData['images'] ?? [], 0, 3))->map(fn($p) => (object)['image_path' => $p, 'caption' => null]);
            }
        @endphp

        {{-- Tema Bar --}}
        <div style="background-color: #f5f3ff; padding: 5px 10px; border-left: 4px solid #7c3aed; font-weight: bold; font-size: 9pt; color: #4c1d95; margin-bottom: 8px; overflow: hidden;">
            <div style="float: left; width: 60%;">TEMA: {{ strtoupper($activity->activity_name ?? $pblData['tema'] ?? '') }}</div>
            <div style="float: right; width: 40%; text-align: right;">LOKASI: {{ strtoupper($activity->location ?? $pblData['location'] ?? '-') }}</div>
            <div style="clear: both;"></div>
        </div>

        {{-- Tabel Kegiatan --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th width="40%">DESKRIPSI AKTIVITAS</th>
                    <th width="15%" class="text-center">FASE KEGIATAN</th>
                    <th width="33%" class="text-center">INDIKATOR</th>
                    <th width="12%" class="text-center">CAPAIAN</th>
                </tr>
            </thead>
            <tbody>
                @php $firstActivityRow = true; @endphp
                @forelse($gradesByPhase as $phaseName => $phaseGrades)
                    @php $phaseFirst = true; $phaseCount = count($phaseGrades); @endphp
                    @foreach($phaseGrades as $grade)
                        <tr>
                            @if($firstActivityRow)
                                <td rowspan="{{ $totalRows }}" style="text-align: left; font-size: 8pt; padding: 12px; vertical-align: top; line-height: 1.4; color: #1f2937;">
                                    @php
                                        $paragraphs = preg_split('/\r\n|\r|\n/', $desc);
                                        foreach ($paragraphs as $para) {
                                            if (trim($para) !== '') {
                                                echo '<p style="text-align: left; margin: 0 0 6px 0; line-height: 1.4;">' . e($para) . '</p>';
                                            }
                                        }
                                    @endphp
                                </td>
                                @php $firstActivityRow = false; @endphp
                            @endif
                            @if($phaseFirst)
                                <td rowspan="{{ $phaseCount }}" class="font-bold text-center" style="vertical-align: middle;">{{ strtoupper($phaseName) }}</td>
                                @php $phaseFirst = false; @endphp
                            @endif
                            <td class="text-center" style="font-size: 7.5pt;">{{ $grade->indicator_name }}</td>
                            <td class="text-center font-bold" style="font-size: 8pt; color: #4c1d95;">{{ $grade->grade ?? '-' }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td style="text-align: left; font-size: 8pt; padding: 12px; line-height: 1.4; color: #1f2937;">
                            @php
                                $paragraphs = preg_split('/\r\n|\r|\n/', $desc);
                                foreach ($paragraphs as $para) {
                                    if (trim($para) !== '') echo '<p style="text-align: left; margin: 0 0 6px 0; line-height: 1.4;">' . e($para) . '</p>';
                                }
                            @endphp
                        </td>
                        <td colspan="3" class="text-center" style="padding: 10px; font-style: italic; color: #777;">Belum ada indikator penilaian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Potensi Project --}}
        <div class="no-break" style="margin-top: 15px;">
            <div class="section-header" style="border-top: 1px solid #ddd6fe; border-radius: 6px 6px 0 0;">POTENSI PROJECT {{ strtoupper($activity->activity_name ?? '') }}</div>
            <table class="potensi-grid">
                <thead>
                    <tr>
                        <th width="33.33%">TYPE OF PERSONALITY</th>
                        <th width="33.33%">MULTIPLE INTELLIGENCE</th>
                        <th width="33.33%">STRENGTH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ implode(', ', (array)$personality) ?: '-' }}</td>
                        <td>{{ implode(', ', (array)$intelligence) ?: '-' }}</td>
                        <td>{{ implode(', ', (array)$strength) ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Dokumentasi --}}
        @if($docs->isNotEmpty())
            <div class="section-header" style="margin-top: 15px; border-top: 1px solid #ddd6fe; border-radius: 6px 6px 0 0;">LAMPIRAN DOKUMENTASI: {{ strtoupper($activity->activity_name ?? '') }}</div>
            <table class="image-grid">
                <tr>
                    @foreach($docs as $doc)
                        <td>
                            @php
                                $imgPath = public_path('storage/' . $doc->image_path);
                                $hasImg  = !empty($doc->image_path) && file_exists($imgPath);
                            @endphp
                            @if($hasImg)
                                <img src="{{ str_replace('\\', '/', $imgPath) }}" class="img-container" alt="Dokumentasi">
                            @else
                                <div style="background-color: #f2f2f2; width: 100%; height: 180px; border-radius: 4px; display: block;">
                                    <div style="padding-top: 80px; color: #aaa; font-size: 7px; text-align:center;">[Tidak Ditemukan]</div>
                                </div>
                            @endif
                            @if(!empty($doc->caption))
                                <div class="img-caption">{{ $doc->caption }}</div>
                            @endif
                        </td>
                    @endforeach
                    @for($i = $docs->count(); $i < 3; $i++)
                        <td></td>
                    @endfor
                </tr>
            </table>
        @else
            <table class="image-grid">
                <tr><td colspan="3" class="text-center" style="padding: 20px; color: #777; font-style: italic;">Tidak ada dokumentasi foto.</td></tr>
            </table>
        @endif

        {{-- Page break antar proyek (kecuali terakhir) --}}
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    {{-- ==================== HALAMAN FINAL: CATATAN + KOMPETENSI ==================== --}}
    @if($report->is_final_pbl)
        <div class="page-break-before"></div>

        @php
            // Agregasi potensi keseluruhan dari semua aktivitas
            $allPersonality = []; $allIntelligence = []; $allStrength = [];
            $parseAggregatePotensi = function($val) {
                if (empty($val)) return [];
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    return is_array($decoded) ? $decoded : [$val];
                }
                return (array)$val;
            };
            foreach ($report->probingActivities as $act) {
                $allPersonality  = array_merge($allPersonality, $parseAggregatePotensi($act->personality  ?? $act->pbl_data['personality']  ?? []));
                $allIntelligence = array_merge($allIntelligence, $parseAggregatePotensi($act->intelligence ?? $act->pbl_data['intelligence'] ?? []));
                $allStrength     = array_merge($allStrength, $parseAggregatePotensi($act->strength        ?? $act->pbl_data['strength']     ?? []));
            }
            $countAct = $report->probingActivities->count();
            $filterFn = function($arr, $limit) use ($countAct) {
                if (empty($arr)) return [];
                if ($countAct <= 1) return array_slice(array_unique($arr), 0, $limit);
                $counts = array_count_values($arr);
                arsort($counts);
                $res = array_keys(array_filter($counts, fn($c) => $c > 1));
                if (empty($res)) {
                    $res = array_keys($counts);
                }
                return array_slice($res, 0, $limit);
            };
            $finalPersonality  = $filterFn($allPersonality, 2);
            $finalIntelligence = $filterFn($allIntelligence, 3);
            $finalStrength     = $filterFn($allStrength, 3);

            // Catatan Berkembang / Perlu Dikembangkan
            $notesDeveloped = $report->notes_developed;
            $notesNeedDev   = $report->notes_need_development;
        @endphp

        {{-- Catatan Keseluruhan --}}
        <div class="section-header" style="border-top: 1px solid #777777;">CATATAN KESELURUHAN</div>
        <table class="main-table" style="border-top: none;">
            <thead>
                <tr>
                    <th width="50%">BERKEMBANG</th>
                    <th width="50%">PERLU DIKEMBANGKAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="min-height: 80px; vertical-align: top;">
                        @if($notesDeveloped)
                            @php
                                $paras = preg_split('/\r\n|\r|\n/', $notesDeveloped);
                                foreach ($paras as $p) if (trim($p)) echo '<p style="margin:0 0 4px 0;">• ' . e($p) . '</p>';
                            @endphp
                        @else
                            <span style="color: #999; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td style="min-height: 80px; vertical-align: top;">
                        @if($notesNeedDev)
                            @php
                                $paras = preg_split('/\r\n|\r|\n/', $notesNeedDev);
                                foreach ($paras as $p) if (trim($p)) echo '<p style="margin:0 0 4px 0;">• ' . e($p) . '</p>';
                            @endphp
                        @else
                            <span style="color: #999; font-style: italic;">-</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Potensi Keseluruhan --}}
        <div class="section-header">POTENSI PROJECT KESELURUHAN</div>
        <table class="potensi-grid">
            <thead>
                <tr>
                    <th width="33.33%">POTENSI TYPE OF PERSONALITY</th>
                    <th width="33.33%">POTENSI MULTIPLE INTELLIGENCE</th>
                    <th width="33.33%">POTENSI STRENGTH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 60px; vertical-align: middle;">{{ implode(', ', $finalPersonality) ?: '-' }}</td>
                    <td style="height: 60px; vertical-align: middle;">{{ implode(', ', $finalIntelligence) ?: '-' }}</td>
                    <td style="height: 60px; vertical-align: middle;">{{ implode(', ', $finalStrength) ?: '-' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Catatan Guru --}}
        <div style="margin-top: 15px; border: 1px solid #ddd6fe; background-color: #f5f3ff; padding: 12px; border-radius: 6px;">
            <div class="font-bold" style="border-bottom: 1px solid #ddd6fe; margin-bottom: 8px; padding-bottom: 4px; color: #4c1d95;">Catatan:</div>
            <div class="text-justify" style="min-height: 60px; font-size: 8pt; color: #222222;">
                {{ $report->summary_notes ?: 'Ananda menunjukkan perkembangan yang sangat positif selama satu semester ini. Terus tingkatkan semangat belajar dan eksplorasi potensi diri.' }}
            </div>
        </div>

        {{-- ==================== MATRIKS 14 FOUNDATION COMPETENCIES ==================== --}}
        @if($report->competencyAssessments->isNotEmpty())
            <div style="margin-top: 20px; page-break-inside: avoid;">
                <div class="section-header" style="border-top: 1px solid #7c3aed; border-bottom: none; border-radius: 6px 6px 0 0; background-color: #4c1d95; color: #ffffff; font-size: 9pt; padding: 7px;">
                    MATRIKS PERKEMBANGAN 14 FOUNDATION COMPETENCIES
                </div>
                <table class="competency-table">
                    <thead>
                        <tr>
                            <th width="4%" style="text-align:center;">#</th>
                            <th width="38%">KOMPETENSI</th>
                            <th width="10%" style="text-align:center;">SKOR</th>
                            <th width="48%">GRAFIK PERKEMBANGAN (1 – 10)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report->competencyAssessments as $idx => $comp)
                            @php
                                $pct = max(0, min(100, ($comp->score / 10) * 100));
                                $barColor = $comp->score >= 8 ? '#059669' : ($comp->score >= 5 ? '#7c3aed' : '#dc2626');
                            @endphp
                            <tr style="{{ $idx % 2 === 0 ? 'background-color:#fafafa;' : 'background-color:#ffffff;' }}">
                                <td style="text-align:center; color:#999;">{{ $idx + 1 }}</td>
                                <td style="font-weight:bold;">{{ $comp->competency_name }}</td>
                                <td style="text-align:center; font-weight:bold; font-size:9pt; color:{{ $barColor }};">{{ $comp->score }}</td>
                                <td style="padding: 3px 5px; vertical-align: middle;">
                                    <div class="score-bar-wrap">
                                        <div class="score-bar-fill" style="width:{{ $pct }}%; background-color:{{ $barColor }};"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Tanda Tangan --}}
        <div class="signature-container">
            <table class="sig-table">
                <tr>
                    <td>Mengetahui,<br>Orang tua/wali<div class="sig-line"></div></td>
                    <td></td>
                    <td>{{ $printPlace }}, {{ $printDate }}<br>Fasilitator<div class="sig-line"></div><span class="font-bold">{{ $report->teacher->name }}</span></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 25px;">
                        Mengetahui<br>Principle Sayyidah School
                        <div class="sig-line"></div>
                        <span class="font-bold">{{ optional($school)->kepala_sekolah_nama ?? 'Anggia Chrisanti, S.Psi, M.Psi, Psikolog' }}</span>
                    </td>
                </tr>
            </table>
        </div>

    @else
        {{-- Tanda tangan untuk raport non-final --}}
        <div class="signature-container">
            <table class="sig-table">
                <tr>
                    <td>Mengetahui,<br>Orang tua/wali<div class="sig-line"></div></td>
                    <td></td>
                    <td>{{ $printPlace }}, {{ $printDate }}<br>Fasilitator<div class="sig-line"></div><span class="font-bold">{{ $report->teacher->name }}</span></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 25px;">
                        Mengetahui<br>Principle Sayyidah School
                        <div class="sig-line"></div>
                        <span class="font-bold">{{ optional($school)->kepala_sekolah_nama ?? 'Anggia Chrisanti, S.Psi, M.Psi, Psikolog' }}</span>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <div class="footer">
        <div style="display: table-cell; width: 33.33%;">Sayyidah School</div>
        <div style="display: table-cell; width: 33.33%; text-align: center;">{{ $report->student->nama_siswa }}</div>
        <div style="display: table-cell; width: 33.33%; text-align: right;">{{ $report->student->class }} | {{ $report->period }}</div>
    </div>
</body>
</html>
