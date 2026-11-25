@extends('layouts.app')

@php
    $ibadahMutu = $mutuLabels->where('type', 'Ibadah')->values()->map(function($label) {
        return ['code' => $label->code, 'label' => $label->label];
    });
    if ($ibadahMutu->isEmpty()) {
        $ibadahMutu = collect([
            ['code' => 'A', 'label' => 'Sangat Baik'],
            ['code' => 'B', 'label' => 'Baik'],
            ['code' => 'C', 'label' => 'Cukup'],
        ]);
    }

    $initialIbadahValues = [];
    $activeIbadahItems = $ibadahItems;
    if ($report->ibadahReport && isset($report->ibadahReport->additional_grades['_ordered_indicators'])) {
        $activeIbadahItems = $report->ibadahReport->additional_grades['_ordered_indicators'];
    }
    foreach ($activeIbadahItems as $item) {
        $itemId = $item['id'];
        $val = '-';
        if ($report->ibadahReport) {
            if (isset($report->ibadahReport->{$itemId})) {
                $val = $report->ibadahReport->{$itemId};
            } elseif (isset($report->ibadahReport->additional_grades[$itemId])) {
                $val = $report->ibadahReport->additional_grades[$itemId];
            }
        }
        $initialIbadahValues[] = [
            'id' => $itemId,
            'label' => $item['label'],
            'is_custom' => $item['is_custom'] ?? false,
            'value' => $val ?: '-'
        ];
    }
@endphp

@section('content')
<div class="py-12" x-data="reportForm()" x-on:subject-deleted.window="if (!deletedSubjectIds.includes($event.detail.id)) deletedSubjectIds.push($event.detail.id)">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-[20px] p-8 border border-gray-200">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-extrabold">Edit Raport {{ $report->category->name }}</h2>
                <a href="{{ route('admin.reports.student-list', [$report->siswa_id, 'tahun_ajaran' => request('tahun_ajaran')]) }}" class="bg-gray-100 px-5 py-2 rounded-xl font-semibold">Kembali</a>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold dark:bg-red-950/20 dark:border-red-900/50 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-650 rounded-xl text-sm font-semibold dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold dark:bg-red-950/20 dark:border-red-900/50 dark:text-red-400">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="report-edit-form" action="{{ route('admin.reports.update', $report) }}{{ request('tahun_ajaran') ? '?tahun_ajaran=' . request('tahun_ajaran') : '' }}" method="POST" enctype="multipart/form-data" @submit.prevent="validateForm($event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="is_draft_input" name="is_draft" value="{{ $report->is_draft ? '1' : '0' }}">
                <input type="hidden" name="siswa_id" value="{{ $report->siswa_id }}">
                <input type="hidden" name="report_category_id" value="{{ $report->report_category_id }}">
                <input type="hidden" name="ibadah_order" :value="JSON.stringify(localIbadahItems.map(item => ({ id: item.id, label: item.label, is_custom: item.is_custom })))">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                    <div>
                        <label class="block text-sm font-bold text-gray-400">SISWA</label>
                        <div class="text-xl font-bold">{{ $report->student->nama_siswa }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Periode / Semester</label>
                        @if(Auth::user()->isPimpinan())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <select x-model="selectedSemester" @change="updatePeriod()" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-semibold focus:border-blue-500 focus:ring-blue-500">
                                        <option value="Ganjil">Semester Ganjil</option>
                                        <option value="Genap">Semester Genap</option>
                                    </select>
                                </div>
                                <div>
                                    <select x-model="selectedAcademicYear" @change="updatePeriod()" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-semibold focus:border-blue-500 focus:ring-blue-500">
                                        <template x-for="year in getAcademicYears()" :key="year">
                                            <option :value="year" x-text="year" :selected="year === selectedAcademicYear"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="period" :value="period">
                            <p class="text-xs text-gray-400 mt-2 uppercase font-bold tracking-wider">
                                Format Periode: <span class="text-blue-600 dark:text-blue-400 font-extrabold" x-text="period"></span>
                            </p>
                        @else
                            <div class="text-xl font-bold text-blue-600 dark:text-blue-400 flex items-center gap-2">
                                <span class="px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-xl border border-blue-100 dark:border-blue-900/50" x-text="period"></span>
                            </div>
                            <input type="hidden" name="period" :value="period">
                        @endif
                    </div>
                    <template x-if="academicType">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Jenis Raport</label>
                            <div class="text-xl font-black text-blue-600 uppercase" x-text="academicType"></div>
                            <input type="hidden" name="academic_type" :value="academicType">
                        </div>
                    </template>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Fasilitator (Tanda Tangan)</label>
                        @if(Auth::user()->isPimpinan())
                            <select name="teacher_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-semibold focus:border-blue-500 focus:ring-blue-500">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $report->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="text-xl font-bold">{{ $report->teacher->name ?? '-' }}</div>
                        @endif
                    </div>
                </div>

                <!-- Academic Form -->
                <template x-if="isAkademik">
                    <div class="space-y-12">
                        <div class="flex justify-between items-center border-l-4 border-blue-500 pl-4 mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Nilai Akademik</h3>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="importExamGrades($event)" class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-emerald-100 transition shadow-sm border border-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Import Nilai Ujian
                                </button>
                                <button type="button" @click="newSubjects.push({ id: Date.now(), name: '', kkm: 75 })" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-blue-100 transition shadow-sm border border-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Mapel
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                        <th class="p-4">Mapel</th>
                                        <template x-if="academicType === 'PTS' || !academicType">
                                            <th class="p-4 text-center">PTS</th>
                                        </template>
                                        <template x-if="academicType === 'PAS' || !academicType">
                                            <th class="p-4 text-center">PAS</th>
                                        </template>
                                        <th class="p-4 text-center">Rem</th>
                                        <th class="p-4 text-center">Har</th>
                                        <th class="p-4 text-center">Akhir</th>
                                        <th class="p-4 text-center">Pred</th>
                                        <th class="p-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($report->category->subjects as $subject)
                                        @php $grade = $report->grades->where('report_subject_id', $subject->id)->first(); @endphp
                                        <tr x-data="{ 
                                                score: '{{ $grade->score ?? '' }}', 
                                                pts: '{{ $grade->score_pts ?? '' }}', 
                                                pas: '{{ $grade->score_pas ?? '' }}', 
                                                rem: '{{ $grade->score_remedial ?? '' }}', 
                                                harian: '{{ $grade->score_harian ?? '' }}', 
                                                pred: '{{ $grade->predicate ?? '-' }}',
                                                deleted: !@json(isset($grade)),
                                                calculate() {
                                                    let v = [parseFloat(this.pts)||0, parseFloat(this.pas)||0, parseFloat(this.rem)||0, parseFloat(this.harian)||0].filter(x => x > 0);
                                                    if (v.length === 0) { this.score = ''; this.pred = '-'; return; }
                                                    this.score = (v.reduce((a,b)=>a+b,0)/v.length).toFixed(2);
                                                    this.pred = reportForm().calculatePredicate(this.score);
                                                }
                                            }" 
                                            x-init="$watch('pts', () => calculate()); $watch('pas', () => calculate()); $watch('rem', () => calculate()); $watch('harian', () => calculate())"
                                            x-show="!deleted"
                                            x-on:activate-subject.window="if ($event.detail.id === {{ $subject->id }}) { deleted = false; }"
                                            class="hover:bg-gray-50/50 transition academic-row">
                                            <td class="p-4">
                                                <div class="font-bold text-gray-700 subject-name">{{ $subject->name }}</div>
                                                <div class="text-[9px] text-gray-400 font-black">KKM: {{ $subject->kkm ?? 75 }}</div>
                                            </td>
                                            <template x-if="academicType === 'PTS' || !academicType">
                                                <td class="p-3 text-center"><input type="number" name="grades[{{ $subject->id }}][score_pts]" x-model="pts" step="0.01" :disabled="deleted" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center pts-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            </template>
                                            <template x-if="academicType === 'PAS' || !academicType">
                                                <td class="p-3 text-center"><input type="number" name="grades[{{ $subject->id }}][score_pas]" x-model="pas" step="0.01" :disabled="deleted" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center pas-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            </template>
                                            <td class="p-3 text-center"><input type="number" name="grades[{{ $subject->id }}][score_remedial]" x-model="rem" step="0.01" :disabled="deleted" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center rem-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            <td class="p-3 text-center"><input type="number" name="grades[{{ $subject->id }}][score_harian]" x-model="harian" step="0.01" :disabled="deleted" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center harian-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            <td class="p-3 text-center"><input type="number" name="grades[{{ $subject->id }}][score]" x-model="score" step="0.01" :disabled="deleted" class="w-20 py-2 rounded-xl border-blue-150 dark:border-blue-900 bg-blue-50 dark:bg-blue-950/30 font-black text-blue-600 dark:text-blue-400 text-center score-input" readonly></td>
                                            <td class="p-4 text-center">
                                                <input type="hidden" name="grades[{{ $subject->id }}][predicate]" :value="pred" :disabled="deleted">
                                                <span class="inline-block min-w-[24px] px-2 py-0.5 rounded-md text-[10px] font-black shadow-sm" :class="{
                                                    'bg-green-500 text-white': pred === 'A',
                                                    'bg-blue-500 text-white': pred === 'B',
                                                    'bg-yellow-500 text-white': pred === 'C',
                                                    'bg-red-500 text-white': pred === 'D',
                                                    'bg-gray-100 text-gray-400': pred === '-'
                                                }" x-text="pred"></span>
                                            </td>
                                            <td class="p-4 text-center">
                                                <button type="button" @click="deleted = true; $dispatch('subject-deleted', { id: {{ $subject->id }} })" class="text-red-500 hover:text-red-700 transition">
                                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    <!-- New Subjects Added Dynamically -->
                                    <template x-for="(sub, index) in newSubjects" :key="sub.id">
                                        <tr x-data="{ 
                                                score: '', 
                                                pts: '', 
                                                pas: '', 
                                                rem: '', 
                                                harian: '', 
                                                pred: '-',
                                                calculate() {
                                                    let v = [parseFloat(this.pts)||0, parseFloat(this.pas)||0, parseFloat(this.rem)||0, parseFloat(this.harian)||0].filter(x => x > 0);
                                                    if (v.length === 0) { this.score = ''; this.pred = '-'; return; }
                                                    this.score = (v.reduce((a,b)=>a+b,0)/v.length).toFixed(2);
                                                    this.pred = reportForm().calculatePredicate(this.score);
                                                }
                                            }" 
                                            x-init="$watch('pts', () => calculate()); $watch('pas', () => calculate()); $watch('rem', () => calculate()); $watch('harian', () => calculate())"
                                            class="bg-blue-50/20 academic-row">
                                            <td class="p-3"><input type="text" :name="`new_subjects[${index}][name]`" placeholder="Nama Mapel" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold py-2 focus:ring-blue-500 focus:border-blue-500"></td>
                                            <template x-if="academicType === 'PTS' || !academicType">
                                                <td class="p-3 text-center"><input type="number" :name="`new_subjects[${index}][score_pts]`" x-model="pts" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center pts-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            </template>
                                            <template x-if="academicType === 'PAS' || !academicType">
                                                <td class="p-3 text-center"><input type="number" :name="`new_subjects[${index}][score_pas]`" x-model="pas" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center pas-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            </template>
                                            <td class="p-3 text-center"><input type="number" :name="`new_subjects[${index}][score_remedial]`" x-model="rem" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center rem-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            <td class="p-3 text-center"><input type="number" :name="`new_subjects[${index}][score_harian]`" x-model="harian" class="w-20 py-2 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center harian-input font-bold focus:ring-blue-500 focus:border-blue-500"></td>
                                            <td class="p-3 text-center"><input type="number" :name="`new_subjects[${index}][score]`" x-model="score" class="w-20 py-2 rounded-xl border-blue-150 dark:border-blue-900 bg-blue-50 dark:bg-blue-950/30 font-black text-blue-600 dark:text-blue-400 text-center score-input" readonly></td>
                                            <td class="p-4 text-center">
                                                <input type="hidden" :name="`new_subjects[${index}][predicate]`" :value="pred">
                                                <span class="inline-block min-w-[24px] px-2 py-0.5 rounded-md text-[10px] font-black shadow-sm" :class="{
                                                    'bg-green-500 text-white': pred === 'A',
                                                    'bg-blue-500 text-white': pred === 'B',
                                                    'bg-yellow-500 text-white': pred === 'C',
                                                    'bg-red-500 text-white': pred === 'D',
                                                    'bg-gray-100 text-gray-400': pred === '-'
                                                }" x-text="pred"></span>
                                            </td>
                                            <td class="p-4 text-center">
                                                <button type="button" @click="newSubjects = newSubjects.filter(item => item.id !== sub.id)" class="text-red-500 hover:text-red-700 transition">
                                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Excluded Subjects to Restore -->
                        <template x-if="deletedSubjectIds.length > 0">
                            <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                                <span class="text-xs font-bold text-gray-500 block mb-2">Mata Pelajaran Non-Aktif (Klik untuk mengaktifkan kembali):</span>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="id in deletedSubjectIds">
                                        <button type="button" @click="$dispatch('activate-subject', { id: id }); deletedSubjectIds = deletedSubjectIds.filter(x => x !== id);" class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span x-text="allSubjects.find(s => s.id === id)?.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                            @foreach(['pts_pas' => 'PTS/PAS', 'remedial' => 'Remedial', 'harian' => 'Harian'] as $key => $label)
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">KESIMPULAN {{ $label }}</label>
                                        <button type="button" @click="generateAcademicSummary('{{ $key }}')" class="flex items-center gap-1 text-[9px] bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-black hover:bg-blue-100 transition shadow-sm border border-blue-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            AUTO-GENERATE
                                        </button>
                                    </div>
                                    <textarea name="{{ $key }}_summary" rows="5" class="w-full rounded-2xl border-gray-200 text-sm summary-textarea leading-relaxed" data-type="{{ $key }}">{{ $report->{$key.'_summary'} }}</textarea>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="space-y-4 pt-8 border-t border-gray-100">
                            <div class="flex justify-between items-end">
                                <div>
                                    <label class="block text-sm font-black text-gray-400 uppercase tracking-widest">Catatan Umum Wali Kelas</label>
                                </div>
                                <button type="button" @click="showSuggestionBox = true" class="flex items-center gap-2 text-xs font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    LIHAT SARAN
                                </button>
                            </div>
                            <textarea name="summary_notes" id="summary_notes_area" rows="4" class="w-full rounded-2xl border-gray-200 leading-relaxed p-4">{{ $report->summary_notes }}</textarea>
                        </div>
                    </div>
                </template>

                <!-- Suggestion Box Modal -->
                <div x-show="showSuggestionBox" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showSuggestionBox" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showSuggestionBox = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showSuggestionBox" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                            <div class="bg-white px-8 py-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Saran Kalimat Wali Kelas</h3>
                                    <button @click="showSuggestionBox = false" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-4 custom-scrollbar">
                                    @forelse($suggestions as $suggestion)
                                        <button type="button" @click="document.getElementById('summary_notes_area').value = '{{ addslashes($suggestion->content) }}'; showSuggestionBox = false" class="w-full text-left p-6 rounded-2xl border border-gray-100 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                            @if($suggestion->category)
                                                <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-black uppercase rounded-md mb-2">{{ $suggestion->category }}</span>
                                            @endif
                                            <p class="text-sm text-gray-600 group-hover:text-blue-700 leading-relaxed">{{ $suggestion->content }}</p>
                                        </button>
                                    @empty
                                        <div class="text-center py-10">
                                            <p class="text-gray-400 font-bold italic">Belum ada saran kalimat yang tersedia.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Auto Generate Confirmation Modal -->
                <div x-show="showAutoGenerateConfirm" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showAutoGenerateConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAutoGenerateConfirm = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showAutoGenerateConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-8 py-8">
                                <div class="flex items-start mb-6">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-500 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Konfirmasi Deskripsi Kosong</h3>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-bold">Nilai untuk kategori ini belum terisi. Pilih bagaimana sistem harus mengisi kolom deskripsi/kesimpulan ini:</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex flex-col gap-2">
                                    <button type="button" @click="fillEmptySummary('-')" class="w-full text-left px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 font-bold text-xs text-gray-700 dark:text-gray-200 transition-all">
                                        Isi dengan strip ("-")
                                    </button>
                                    <button type="button" @click="fillEmptySummary('Belum ada penilaian')" class="w-full text-left px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 font-bold text-xs text-gray-700 dark:text-gray-200 transition-all">
                                        Isi dengan "Belum ada penilaian"
                                    </button>
                                    <button type="button" @click="fillEmptySummary('Tidak mengikuti penilaian')" class="w-full text-left px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 font-bold text-xs text-gray-700 dark:text-gray-200 transition-all">
                                        Isi dengan "Tidak mengikuti penilaian"
                                    </button>
                                    <button type="button" @click="fillEmptySummary('')" class="w-full text-left px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 font-bold text-xs text-red-600 transition-all">
                                        Biarkan Kosong
                                    </button>
                                </div>
                                <div class="mt-6 flex justify-end gap-2">
                                    <button type="button" @click="showAutoGenerateConfirm = false" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-800 dark:text-gray-200 px-5 py-2.5 rounded-xl text-xs font-black transition">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Probing / PBL Form -->
                <template x-if="isProbing">
                    <div class="space-y-12">
                        <div class="border-l-4 border-amber-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Segmen Raport Probing (PBL) per Semester</h3>
                            <p class="text-xs text-gray-500 mt-1">Perbarui catatan perkembangan keseluruhan, matriks 14 kompetensi, dan aktivitas proyek bulanan di bawah.</p>
                        </div>

                        <!-- Toggle Switches -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-[28px] border border-gray-150 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_first_pbl" value="1" class="sr-only peer" {{ $report->is_first_pbl ? 'checked' : '' }}>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-600"></div>
                                <span class="ml-3 text-sm font-bold text-amber-600 uppercase tracking-widest">Tandai sebagai PBL Pertama (Sampul)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_final_pbl" value="1" class="sr-only peer" {{ $report->is_final_pbl ? 'checked' : '' }}>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                <span class="ml-3 text-sm font-bold text-purple-600 uppercase tracking-widest">Tandai sebagai Rapot Akhir (Catatan)</span>
                            </label>
                        </div>

                        <!-- Notes Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Catatan Perkembangan: Berkembang</label>
                                <textarea name="notes_developed" rows="4" class="w-full rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Tuliskan aspek perkembangan ananda yang sangat baik semester ini...">{{ old('notes_developed', $report->notes_developed) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Catatan Perkembangan: Perlu Dikembangkan</label>
                                <textarea name="notes_need_development" rows="4" class="w-full rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Tuliskan aspek perkembangan ananda yang masih perlu distimulasi...">{{ old('notes_need_development', $report->notes_need_development) }}</textarea>
                            </div>
                        </div>

                        <!-- 14 Foundation Competencies Matrix -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-[28px] border border-gray-150 dark:border-gray-700 shadow-md">
                            <h4 class="text-sm font-black text-purple-600 uppercase tracking-widest mb-6 border-b pb-3 border-gray-100 dark:border-gray-700">Matriks 14 Foundation Competencies (Skala 1 - 10)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($probingCompetencies as $competency)
                                    @php
                                        $existingScore = $report->competencyAssessments->firstWhere('competency_name', $competency)?->score ?? 8;
                                    @endphp
                                    <div class="p-4 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-gray-150 dark:border-gray-750 flex flex-col justify-between"
                                         x-data="{ scoreVal: {{ $existingScore }} }">
                                        <label class="block text-xs font-extrabold text-gray-700 dark:text-gray-300 mb-2">{{ $competency }}</label>
                                        <div class="flex items-center gap-3">
                                            <input type="range" name="competencies[{{ $competency }}]" min="1" max="10" x-model="scoreVal"
                                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600 dark:bg-gray-700">
                                            <span x-text="scoreVal" class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 flex items-center justify-center font-black text-xs"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Probing Projects Builder -->
                        <div class="space-y-6">
                            <div class="flex justify-between items-center border-l-4 border-amber-500 pl-4">
                                <h4 class="text-md font-black text-gray-800 dark:text-gray-200">Daftar Proyek Bulanan Siswa</h4>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="importPblFromCollective()" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold flex items-center gap-1 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-2 rounded-xl border border-indigo-200/50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Impor dari Input Kolektif
                                    </button>
                                    <select @change="addPblActivityFromSemesterProject($event)" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-semibold text-xs py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                                         <option value="">-- Pilih Proyek (Bulanan / Semester) --</option>
                                         <optgroup label="Proyek Bulanan Aktif Semester Ini">
                                             @foreach($semesterProjectTemplates as $tpl)
                                                 <option value="{{ $tpl->id }}" data-source="template" data-title="{{ $tpl->title }}" data-description="{{ $tpl->description }}" data-kegiatan="{{ json_encode($tpl->kegiatan) }}">
                                                     {{ $tpl->title }}
                                                 </option>
                                             @endforeach
                                         </optgroup>
                                         <optgroup label="Template Proyek Bulanan Lainnya">
                                             @foreach($otherProjectTemplates as $tpl)
                                                 <option value="{{ $tpl->id }}" data-source="template" data-title="{{ $tpl->title }}" data-description="{{ $tpl->description }}" data-kegiatan="{{ json_encode($tpl->kegiatan) }}">
                                                     {{ $tpl->title }}
                                                 </option>
                                             @endforeach
                                         </optgroup>
                                         <optgroup label="Proyek Semester">
                                             @foreach($semesterProjects as $sp)
                                                 <option value="{{ $sp->id }}" data-source="semester_project" data-title="{{ $sp->title }}" data-description="{{ $sp->description }}" data-kegiatan="[]">
                                                     {{ $sp->title }} (Semester)
                                                 </option>
                                             @endforeach
                                         </optgroup>
                                    </select>
                                    <button type="button" @click="addManualActivity()" class="text-amber-600 hover:text-amber-800 text-xs font-bold flex items-center gap-1 bg-amber-50 dark:bg-amber-900/30 px-3 py-2 rounded-xl border border-amber-200/50 transition">
                                        + Tambah Manual
                                    </button>
                                </div>
                            </div>

                            {{-- Legend Card for Probing/PBL Achievements --}}
                            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 p-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Keterangan Capaian Penilaian (Legend)</h4>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-0.5">Singkatan capaian penilaian indikator proyek bulanan:</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-[10px] font-black uppercase">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> SB : Sangat Baik
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30 rounded-xl text-[10px] font-black uppercase">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> B : Baik
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 rounded-xl text-[10px] font-black uppercase">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> C : Cukup
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 dark:bg-orange-950/40 text-orange-700 dark:text-orange-400 border border-orange-100 dark:border-orange-900/30 rounded-xl text-[10px] font-black uppercase">
                                        <span class="w-2 h-2 rounded-full bg-orange-500"></span> K : Kurang
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-[10px] font-black uppercase">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Br : Buruk
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <template x-for="(activity, index) in pblActivities" :key="activity.id">
                                    <div :id="`activity-${activity.id}`" class="p-8 border border-gray-200 dark:border-gray-700 rounded-[32px] bg-gray-50/50 dark:bg-gray-800/50 relative space-y-8 transition-all duration-300">
                                        
                                        <!-- Header collapse toggle bar -->
                                        <div class="flex justify-between items-center bg-white dark:bg-gray-800/80 px-6 py-4 -mx-8 -mt-8 rounded-t-[32px] border-b border-gray-150 dark:border-gray-750">
                                            <div class="flex items-center gap-3">
                                                <button type="button" @click="activity.is_collapsed = !activity.is_collapsed" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition text-gray-500">
                                                    <!-- Chevron icon -->
                                                    <svg class="w-5 h-5 transform transition-transform duration-300" :class="activity.is_collapsed ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>
                                                <span class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">
                                                    Proyek #<span x-text="index + 1"></span>: 
                                                    <span class="text-purple-600 dark:text-purple-400" x-text="activity.tema || 'Belum Ditulis'"></span>
                                                </span>
                                            </div>
                                            
                                            <button type="button" @click="pblActivities = pblActivities.filter(a => a.id !== activity.id)" class="text-red-400 hover:text-red-600 transition p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <input type="hidden" :name="`probing[${index}][semester_project_id]`" :value="activity.semester_project_id">

                                        <!-- Collapsible Content Wrapper -->
                                        <div x-show="!activity.is_collapsed" x-collapse class="space-y-8">

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                            <div>
                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tema / Nama Proyek</label>
                                                <input type="text" :name="`probing[${index}][tema]`" x-model="activity.tema" required placeholder="Contoh: Month Of History" class="w-full rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Lokasi Kegiatan</label>
                                                <input type="text" :name="`probing[${index}][location]`" x-model="activity.location" required placeholder="Contoh: Sentul / Sekolah" class="w-full rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi Aktivitas</label>
                                                <textarea :name="`probing[${index}][description]`" x-model="activity.description" rows="2" class="w-full rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Narasi ringkas aktivitas..."></textarea>
                                            </div>
                                        </div>

                                        <!-- Categories and Indicators -->
                                        <div class="mb-4 flex justify-between items-center">
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Fase & Indikator Capaian</label>
                                            <button type="button" @click="activity.kegiatan.push({ id: 'cat_' + Date.now() + '_' + Math.random(), category: 'Fase Baru', indicators: [{id: 'ind_' + Date.now() + '_' + Math.random(), name: 'Indikator Baru', score: 'Baik'}] }); pblActivities = [...pblActivities]" class="text-xs font-bold text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                                                + Tambah Fase
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                            <template x-for="(cat, catIdx) in activity.kegiatan" :key="cat.id">
                                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[24px] border border-gray-150 dark:border-gray-750 shadow-sm relative group flex flex-col justify-between"
                                                     :draggable="categoryDraggable"
                                                     @dragstart="dragStartCat($event, catIdx, index)"
                                                     @dragover.prevent
                                                     @dragenter="dragEnterCat(catIdx, index)"
                                                     @dragend="dragEndCat()">
                                                    <div>
                                                        <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 mb-4 gap-2">
                                                            <!-- Category Drag Handle -->
                                                            <div class="cursor-grab text-purple-500 hover:text-purple-700 p-1 shrink-0 select-none"
                                                                 @mousedown="categoryDraggable = true"
                                                                 @mouseup="categoryDraggable = false"
                                                                 @mouseleave="categoryDraggable = false">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <input type="text" x-model="cat.category" :name="`probing[${index}][kegiatan][${catIdx}][category]`" class="w-full text-sm font-black text-gray-800 dark:text-white border-none bg-transparent px-0 py-0 focus:ring-0 placeholder-gray-300" placeholder="Nama Kategori Fase">
                                                            </div>
                                                            <button type="button" @click="activity.kegiatan.splice(catIdx, 1); pblActivities = [...pblActivities]" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-955/30 rounded-xl transition shrink-0">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </div>

                                                        <div class="grid grid-cols-1 gap-4 mb-4">
                                                            <template x-for="(ind, indIdx) in cat.indicators" :key="ind.id">
                                                                <div class="p-4 bg-gray-50/50 dark:bg-gray-900/60 rounded-2xl border border-gray-100/50 dark:border-gray-700/60 relative flex flex-col gap-2"
                                                                     draggable="true"
                                                                     @dragstart="dragStartInd($event, indIdx, catIdx, index)"
                                                                     @dragover.prevent
                                                                     @dragenter="dragEnterInd(indIdx, catIdx, index)"
                                                                     @dragend="dragEndInd()">
                                                                    <div class="flex justify-between items-center gap-2">
                                                                        <!-- Indicator Drag Handle -->
                                                                        <div class="cursor-grab text-gray-400 hover:text-gray-600 p-0.5 shrink-0 select-none">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                                                                            </svg>
                                                                        </div>
                                                                        <input type="text" x-model="ind.name" :name="`probing[${index}][kegiatan][${catIdx}][indicators][${indIdx}][name]`" class="flex-1 text-xs font-bold text-gray-700 dark:text-gray-200 bg-transparent border-none focus:ring-0 p-0 placeholder-gray-400 dark:placeholder-gray-500" placeholder="Tulis Indikator...">
                                                                        <button type="button" @click="cat.indicators.splice(indIdx, 1); pblActivities = [...pblActivities]" class="p-1 text-gray-400 hover:text-red-500 shrink-0">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                        </button>
                                                                    </div>
                                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-1 justify-between bg-white dark:bg-gray-900 p-2.5 rounded-xl border border-gray-150 dark:border-gray-700">
                                                                        <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Capaian</span>
                                                                        <div class="flex flex-wrap gap-1">
                                                                            <template x-for="scoreVal in ['-', 'Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Buruk']">
                                                                                <button type="button" @click="ind.score = scoreVal" 
                                                                                        :class="ind.score == scoreVal ? scoreConfig[scoreVal].bgActive : scoreConfig[scoreVal].bgInactive" 
                                                                                        class="px-2.5 py-1.5 rounded-lg font-black text-[9px] border transition-all min-w-[32px] text-center">
                                                                                    <span x-text="scoreConfig[scoreVal].label"></span>
                                                                                </button>
                                                                            </template>
                                                                        </div>
                                                                        <input type="hidden" :name="`probing[${index}][kegiatan][${catIdx}][indicators][${indIdx}][score]`" :value="ind.score">
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="cat.indicators.push({id: 'ind_' + Date.now() + '_' + Math.random(), name: 'Indikator Baru', score: 'Baik'}); pblActivities = [...pblActivities]" class="w-full text-xs font-black text-amber-600 py-2.5 border border-dashed border-amber-300 rounded-xl transition">
                                                        + Tambah Indikator
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Potentials Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            @foreach(['personality' => 'Personality Type', 'intelligence' => 'Multiple Intelligence', 'strength' => 'Potential Strength'] as $pKey => $pLabel)
                                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[32px] border border-gray-150 dark:border-gray-700 shadow-sm flex flex-col h-[260px]">
                                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">{{ $pLabel }}</label>
                                                    <div class="flex-1 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                                        @if($pKey === 'personality')
                                                            @foreach(['Sanguinis', 'Koleris', 'Plegmatis', 'Melankolis'] as $type)
                                                                <label class="flex items-center p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 cursor-pointer">
                                                                    <input type="checkbox" :name="`probing[${index}][{{ $pKey }}][]`" value="{{ $type }}" :checked="activity.personality.includes('{{ $type }}')" class="rounded text-amber-500 focus:ring-amber-500 border-gray-300">
                                                                    <span class="ml-2.5 text-xs font-bold text-gray-600 dark:text-gray-400">{{ $type }}</span>
                                                                </label>
                                                            @endforeach
                                                        @elseif($pKey === 'intelligence')
                                                            @foreach($intelligences as $intel)
                                                                <label class="flex items-center p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 cursor-pointer">
                                                                    <input type="checkbox" :name="`probing[${index}][{{ $pKey }}][]`" value="{{ $intel }}" :checked="activity.intelligence.includes('{{ $intel }}')" class="rounded text-amber-500 focus:ring-amber-500 border-gray-300">
                                                                    <span class="ml-2.5 text-xs font-bold text-gray-600 dark:text-gray-400">{{ $intel }}</span>
                                                                </label>
                                                            @endforeach
                                                        @else
                                                            @foreach($strengths as $strength)
                                                                <label class="flex items-center p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 cursor-pointer">
                                                                    <input type="checkbox" :name="`probing[${index}][{{ $pKey }}][]`" value="{{ $strength }}" :checked="activity.strength.includes('{{ $strength }}')" class="rounded text-amber-500 focus:ring-amber-500 border-gray-300">
                                                                    <span class="ml-2.5 text-xs font-bold text-gray-600 dark:text-gray-400">{{ $strength }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Photo Documentation with Captions -->
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Dokumentasi Foto & Keterangan</label>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Existing Images -->
                                                <template x-for="(url, imgIdx) in activity.previews" :key="`existing-${imgIdx}`">
                                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-150 flex flex-col items-center gap-3 relative">
                                                        <img :src="url" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                                                        <input type="text" :name="`probing[${index}][existing_photo_captions][]`" x-model="activity.existing_photo_captions[imgIdx]" placeholder="Caption foto..." 
                                                               class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold focus:outline-none">
                                                        <input type="hidden" :name="`probing[${index}][existing_images][]`" :value="activity.existing_images[imgIdx]">
                                                        <button type="button" @click="removeExistingImage(index, imgIdx)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-md">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>

                                                <!-- New Image Slots -->
                                                <template x-for="(slot, slotIdx) in activity.new_slots" :key="`slot-${slot.id}`">
                                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-150 flex flex-col items-center gap-3 relative">
                                                        <div class="w-full h-32 rounded-xl border border-gray-200 flex items-center justify-center relative overflow-hidden bg-gray-50">
                                                            <template x-if="slot.preview">
                                                                <img :src="slot.preview" class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!slot.preview">
                                                                <div class="text-center p-4">
                                                                    <svg class="w-6 h-6 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                                    <span class="text-[9px] font-bold text-gray-450 uppercase tracking-wider block">Unggah Foto</span>
                                                                </div>
                                                            </template>
                                                            <input type="file" accept="image/*" :name="`probing[${index}][images][]`" @change="handleSlotFile(index, slotIdx, $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                                                        </div>
                                                        <input type="text" :name="`probing[${index}][photo_captions][]`" x-model="slot.caption" placeholder="Tulis caption foto..." 
                                                               class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold focus:outline-none">
                                                        
                                                        <button type="button" @click="activity.new_slots.splice(slotIdx, 1)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-md">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                
                                                <div class="flex items-center justify-center" x-show="activity.new_slots.length + activity.previews.length < 3">
                                                    <button type="button" @click="activity.new_slots.push({ id: Date.now(), preview: null, caption: '' })"
                                                            class="w-full h-32 rounded-2xl border-2 border-dashed border-purple-200 bg-purple-50/20 flex flex-col items-center justify-center text-purple-600 hover:bg-purple-50 transition group">
                                                        <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                        <span class="text-[9px] font-black uppercase tracking-widest">Tambah Foto Slot</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Diniyah -->
                <template x-if="isDiniyah">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-l-4 border-emerald-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold">Hafalan Al-Quran</h3>
                            <button type="button" @click="diniyahItems.push({ id: Date.now(), surah_id: '', start: 1, end: 1, mutu: 'A' })" class="text-emerald-600 font-bold text-sm">TAMBAH SURAT</button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(item, index) in diniyahItems" :key="item.id">
                                <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    <div class="md:col-span-4">
                                        <select :name="`diniyah[${index}][surah_id]`" x-model="item.surah_id" @change="updateTotalAyahs(index)" class="w-full rounded-xl border-gray-200 text-sm font-bold">
                                            <option value="">Pilih Surat...</option>
                                            <template x-for="surah in surahs" :key="surah.id">
                                                <option :value="surah.id" x-text="surah.name" :selected="surah.id == item.surah_id"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="md:col-span-3 flex items-center gap-2">
                                        <input type="number" :name="`diniyah[${index}][start_ayah]`" x-model="item.start" min="1" :max="item.totalAyahs" class="w-full rounded-xl border-gray-200 text-center text-sm font-bold">
                                        <span class="text-gray-400">-</span>
                                        <input type="number" :name="`diniyah[${index}][end_ayah]`" x-model="item.end" min="1" :max="item.totalAyahs" class="w-full rounded-xl border-gray-200 text-center text-sm font-bold">
                                    </div>
                                    <div class="md:col-span-3">
                                        <select :name="`diniyah[${index}][mutu_code]`" x-model="item.mutu" class="w-full rounded-xl border-gray-200 text-sm font-bold text-emerald-600">
                                            @php 
                                                $diniyahMutu = $mutuLabels->where('type', 'Diniyah');
                                                if($diniyahMutu->isEmpty()) {
                                                    $diniyahMutu = collect([
                                                        (object)['code' => 'A', 'label' => 'A (Mumtaz)'],
                                                        (object)['code' => 'B', 'label' => 'B (Jayyid J.)'],
                                                        (object)['code' => 'C', 'label' => 'C (Jayyid)'],
                                                        (object)['code' => 'D', 'label' => 'D (Maqbul)'],
                                                    ]);
                                                }
                                            @endphp
                                            @foreach($diniyahMutu as $m)
                                                <option value="{{ $m->code }}">{{ $m->code }} ({{ $m->label }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-2 text-right">
                                        <button type="button" @click="diniyahItems = diniyahItems.filter(i => i.id !== item.id)" class="text-red-400 font-black text-xs">HAPUS</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="isIbadah">
                    <div class="space-y-6">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Praktek Ibadah</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="(item, itemIdx) in localIbadahItems" :key="item.id">
                                <div draggable="true"
                                     @dragstart="dragStart($event, itemIdx)"
                                     @dragover.prevent
                                     @dragenter="dragEnter($event, itemIdx)"
                                     @drop="dragDrop($event, itemIdx)"
                                     @dragend="dragEnd()"
                                     :class="{'opacity-50 border-dashed border-indigo-500 bg-indigo-50/20': draggingIndex === itemIdx}"
                                     class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex justify-between items-center shadow-sm cursor-move select-none transition-all duration-200 hover:border-indigo-300">
                                    <div class="flex items-center gap-3">
                                        <div class="text-gray-400 dark:text-gray-600 cursor-grab hover:text-indigo-650 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-755 dark:text-gray-300" x-text="item.label"></span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex gap-2">
                                            <template x-for="labelObj in mutuLabels" :key="labelObj.code">
                                                <label class="flex items-center cursor-pointer" :title="labelObj.label">
                                                    <input type="radio" 
                                                           :name="'ibadah[' + item.id + ']'" 
                                                           :value="labelObj.code" 
                                                           class="hidden peer" 
                                                           :checked="labelObj.code === (item.value || '-')"
                                                           @change="item.value = labelObj.code">
                                                    <span class="px-4 py-1 rounded-lg border border-gray-200 dark:border-gray-700 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 text-xs font-black transition uppercase tracking-widest" x-text="labelObj.code"></span>
                                                </label>
                                            </template>
                                        </div>
                                        <button type="button" @click="localIbadahItems = localIbadahItems.filter(i => i.id !== item.id)" class="text-red-400 hover:text-red-655 hover:scale-105 active:scale-95 transition-all p-2 rounded-xl bg-red-50/50 dark:bg-red-950/20 border border-transparent hover:border-red-200" title="Hapus Indikator">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <template x-if="item.is_custom">
                                        <input type="hidden" :name="'new_ibadah_indicators[' + item.id + ']'" :value="item.label">
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Add New Indicator Form -->
                        <div class="bg-indigo-50/30 dark:bg-indigo-950/20 p-6 rounded-3xl border border-indigo-100/50 dark:border-indigo-900/50 mt-8 flex flex-col sm:flex-row gap-4 items-center justify-between shadow-sm">
                            <div class="flex-1 w-full">
                                <h4 class="text-xs font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest mb-1.5 font-bold">Tambah Indikator Baru</h4>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 font-bold">Indikator yang Anda tambahkan di sini akan otomatis menjadi pilihan bagi guru lain.</p>
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                                <input type="text" x-model="newIbadahIndicatorName" placeholder="Cth: Doa Masuk Masjid" class="w-full sm:w-64 rounded-xl border-gray-200 text-xs font-semibold focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="button" @click="addNewIbadahIndicator()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black transition shadow-md active:scale-95 shrink-0 uppercase tracking-wider">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-4 flex-wrap">
                    @if($report->is_draft)
                        <button id="btn-draft-report" type="submit" formnovalidate @click="isDraft = true" class="bg-gray-150 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-750 dark:text-gray-300 px-8 py-5 rounded-[24px] font-black transition text-base tracking-[0.15em] uppercase">
                            💾 UPDATE DRAFT (TETAP DRAFT)
                        </button>
                        <button type="submit" @click="isDraft = false" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-[24px] font-black shadow-2xl transition text-lg tracking-[0.2em] uppercase">
                            🚀 SIMPAN & FINALKAN RAPOR
                        </button>
                    @else
                        <button type="submit" @click="isDraft = false" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-[24px] font-black shadow-2xl transition text-lg tracking-[0.2em] uppercase">
                            💾 SIMPAN PERUBAHAN
                        </button>
                    @endif
                </div>
                <!-- Validation Error Modal -->
                <div x-show="showErrorModal" 
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-[32px] w-full max-w-lg shadow-2xl overflow-hidden transform transition-all border border-red-100 dark:border-red-900/30"
                         @click.away="showErrorModal = false">
                        
                        <div class="p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="h-12 w-12 bg-red-50 dark:bg-red-950/40 rounded-2xl flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Gagal Menyimpan Perubahan</h3>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mt-0.5">Kesalahan Validasi Input</p>
                                </div>
                            </div>

                            <div class="bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/20 p-5 rounded-2xl mb-6">
                                <ul class="space-y-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <template x-for="error in validationErrors" :key="error">
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-red-500 text-base leading-none shrink-0">•</span>
                                            <span x-text="error" class="leading-normal"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="showErrorModal = false" class="px-8 py-4 bg-red-600 hover:bg-red-750 text-white rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-red-600/20 transition active:scale-95">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Finalize Confirmation Modal (Immersive Design) -->
    <div x-show="showFinalizeModal" 
         class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-[40px] w-full max-w-lg shadow-2xl overflow-hidden transform transition-all flex flex-col p-8 md:p-10 relative"
             @click.away="showFinalizeModal = false">
            
            <!-- Warning Badge Decorator -->
            <div class="mx-auto w-16 h-16 bg-amber-50 dark:bg-amber-950/30 rounded-3xl flex items-center justify-center text-amber-500 dark:text-amber-400 mb-6 border border-amber-100 dark:border-amber-900/30 relative">
                <span class="absolute inset-0 rounded-3xl bg-amber-400 animate-ping opacity-25"></span>
                <svg class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-wide uppercase">Finalkan Rapor</h3>
                <p class="text-sm font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest leading-none mb-4">
                    {{ $report->student->nama_siswa }} • {{ $report->category->name }}
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed">
                    Apakah Anda yakin ingin memfinalkan rapor ini? Aksi ini akan memicu hal-hal berikut:
                </p>
            </div>

            <!-- Immersive Trigger Checkpoints -->
            <div class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-950/50 p-6 rounded-[28px] border border-gray-150 dark:border-gray-850">
                <div class="flex items-start gap-4">
                    <div class="h-8 w-8 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Rilis ke Student Portal</h4>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Nilai & perkembangan siswa akan langsung dipublikasikan dan dapat diakses oleh murid & orang tua.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="h-8 w-8 bg-rose-50 dark:bg-rose-955/40 text-rose-600 dark:text-rose-455 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Kunci Perubahan Nilai</h4>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Rapor akan dikunci dan **tidak dapat diedit kembali** oleh guru (kecuali dengan persetujuan pimpinan).</p>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <button @click="showFinalizeModal = false" type="button"
                        class="w-full sm:w-auto px-6 py-4 text-center text-xs font-black text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 uppercase tracking-widest transition">
                    Batal
                </button>
                <button @click="document.getElementById('report-edit-form').submit()" type="button"
                        class="w-full sm:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 shadow-lg shadow-emerald-600/10 active:scale-95">
                    Ya, Finalkan & Kunci Rapor
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function reportForm() {
        const categoryName = '{{ $report->category->name }}'.trim().toLowerCase();
        return {
            categoryName: categoryName,
            isDraft: @json($report->is_draft ? true : false),
            showErrorModal: false,
            validationErrors: [],
            academicType: '{{ $report->academic_type ?? '' }}',
            period: '{{ old("period", $report->period) }}',
            selectedSemester: 'Ganjil',
            selectedAcademicYear: '',
            init() {
                this.initPeriod();
            },
            initPeriod() {
                const match = this.period.match(/Semester\s+(Ganjil|Genap)\s+(\d{4}\/\d{4})/i);
                if (match) {
                    this.selectedSemester = match[1];
                    this.selectedAcademicYear = match[2];
                } else {
                    this.selectedSemester = this.period.toLowerCase().includes('genap') ? 'Genap' : 'Ganjil';
                    const yearMatch = this.period.match(/(\d{4}\/\d{4})/);
                    if (yearMatch) {
                        this.selectedAcademicYear = yearMatch[1];
                    } else {
                        const currentYear = new Date().getFullYear();
                        this.selectedAcademicYear = `${currentYear}/${currentYear + 1}`;
                    }
                }
                this.updatePeriod();
            },
            updatePeriod() {
                this.period = `Semester ${this.selectedSemester} ${this.selectedAcademicYear}`;
            },
            getAcademicYears() {
                const currentYear = new Date().getFullYear();
                const years = [];
                for (let i = -3; i <= 3; i++) {
                    const y = currentYear + i;
                    years.push(`${y}/${y + 1}`);
                }
                return years;
            },
            newSubjects: [],
            allSubjects: @json($report->category->subjects->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'kkm' => $s->kkm ?? 75
            ])),
            deletedSubjectIds: @json($report->category->subjects->filter(fn($s) => $report->grades->where('report_subject_id', $s->id)->isEmpty())->pluck('id')->values()),
            isProbing: categoryName.includes('project based') || categoryName.includes('probing') || categoryName === 'pbl',
            isAkademik: categoryName === 'akademik',
            isDiniyah: categoryName === 'diniyah',
            isIbadah: categoryName === 'praktek ibadah' || categoryName === 'ibadah',
            showSuggestionBox: false,
            showAutoGenerateConfirm: false,
            autoGenerateType: '',
            fillEmptySummary(value) {
                document.querySelector(`.summary-textarea[data-type="${this.autoGenerateType}"]`).value = value;
                this.showAutoGenerateConfirm = false;
                this.autoGenerateType = '';
            },
            scoreConfig: {
                '-': { label: '-', bgActive: 'bg-gray-500 text-white border-gray-500', bgInactive: 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-600' },
                'Sangat Baik': { label: 'SB', bgActive: 'bg-emerald-600 text-white border-emerald-600 shadow-sm', bgInactive: 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30' },
                'Baik': { label: 'B', bgActive: 'bg-blue-600 text-white border-blue-600 shadow-sm', bgInactive: 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-900/30' },
                'Cukup': { label: 'C', bgActive: 'bg-amber-500 text-white border-amber-500 shadow-sm', bgInactive: 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-100 dark:border-amber-900/30' },
                'Kurang': { label: 'K', bgActive: 'bg-orange-500 text-white border-orange-500 shadow-sm', bgInactive: 'bg-orange-50 dark:bg-orange-950/40 text-orange-700 dark:text-orange-400 border-orange-100 dark:border-orange-900/30' },
                'Buruk': { label: 'Br', bgActive: 'bg-rose-600 text-white border-rose-600 shadow-sm', bgInactive: 'bg-rose-50 dark:bg-rose-955/20 text-rose-700 dark:text-rose-450 border-rose-100 dark:border-rose-900/30' }
            },
            showFinalizeModal: false,
            localIbadahItems: @json($initialIbadahValues),
            newIbadahIndicatorName: '',
            draggingIndex: null,
            dragStart(event, index) {
                this.draggingIndex = index;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', index);
            },
            dragEnter(event, index) {
                if (this.draggingIndex !== null && this.draggingIndex !== index) {
                    const items = [...this.localIbadahItems];
                    const draggedItem = items[this.draggingIndex];
                    items.splice(this.draggingIndex, 1);
                    items.splice(index, 0, draggedItem);
                    this.localIbadahItems = items;
                    this.draggingIndex = index;
                }
            },
            dragDrop(event, index) {
                this.dragEnd();
            },
            dragEnd() {
                this.draggingIndex = null;
            },
            // Category (phase) dragging
            categoryDraggable: false,
            draggedCatIdx: null,
            draggedActivityIdx: null,
            dragStartCat(e, catIdx, activityIdx) {
                this.draggedCatIdx = catIdx;
                this.draggedActivityIdx = activityIdx;
                e.dataTransfer.effectAllowed = 'move';
            },
            dragEnterCat(catIdx, activityIdx) {
                if (this.draggedActivityIdx === activityIdx && this.draggedCatIdx !== catIdx && this.draggedCatIdx !== null) {
                    const list = this.pblActivities[activityIdx].kegiatan;
                    const temp = list[this.draggedCatIdx];
                    list.splice(this.draggedCatIdx, 1);
                    list.splice(catIdx, 0, temp);
                    this.draggedCatIdx = catIdx;
                    this.pblActivities = [...this.pblActivities];
                }
            },
            dragEndCat() {
                this.draggedCatIdx = null;
                this.draggedActivityIdx = null;
                this.categoryDraggable = false;
            },

            // Indicator dragging
            draggedIndIdx: null,
            draggedIndCatIdx: null,
            draggedIndActivityIdx: null,
            dragStartInd(e, indIdx, catIdx, activityIdx) {
                this.draggedIndIdx = indIdx;
                this.draggedIndCatIdx = catIdx;
                this.draggedIndActivityIdx = activityIdx;
                e.dataTransfer.effectAllowed = 'move';
            },
            dragEnterInd(indIdx, catIdx, activityIdx) {
                if (this.draggedIndActivityIdx === activityIdx && this.draggedIndCatIdx === catIdx && this.draggedIndIdx !== indIdx && this.draggedIndIdx !== null) {
                    const list = this.pblActivities[activityIdx].kegiatan[catIdx].indicators;
                    const temp = list[this.draggedIndIdx];
                    list.splice(this.draggedIndIdx, 1);
                    list.splice(indIdx, 0, temp);
                    this.draggedIndIdx = indIdx;
                    this.pblActivities = [...this.pblActivities];
                }
            },
            dragEndInd() {
                this.draggedIndIdx = null;
                this.draggedIndCatIdx = null;
                this.draggedIndActivityIdx = null;
            },
            mutuLabels: [
                { code: '-', label: 'Kosong' },
                ...@json($ibadahMutu)
            ],
            addNewIbadahIndicator() {
                const name = this.newIbadahIndicatorName.trim();
                if (!name) return;
                
                const exists = this.localIbadahItems.some(item => item.label.toLowerCase() === name.toLowerCase());
                if (exists) {
                    alert('Indikator ini sudah ada.');
                    return;
                }

                const code = 'custom_' + name.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_+|_+$)/g, '');
                
                this.localIbadahItems.push({
                    id: code,
                    label: name,
                    is_custom: true,
                    value: '-'
                });

                this.newIbadahIndicatorName = '';
            },
            surahs: @json($surahs ?? []),
            gradeRanges: @json(\App\Models\AcademicGradeRange::orderBy('min_score', 'desc')->get()),
            diniyahItems: {!! json_encode($report->diniyahReports->map(fn($d) => [
                'id' => $d->id,
                'surah_id' => $d->surah_id,
                'start' => $d->start_ayah,
                'end' => $d->end_ayah,
                'mutu' => $d->mutu_code,
                'totalAyahs' => collect($surahs ?? [])->firstWhere('id', $d->surah_id)['total_ayahs'] ?? 1
            ])) !!},
            addPblActivityFromSemesterProject(event) {
                const select = event.target;
                const opt = select.options[select.selectedIndex];
                if (!opt.value) return;

                try {
                    const title = opt.dataset.title;
                    const desc = opt.dataset.description;
                    const source = opt.dataset.source;
                    const kegiatan = JSON.parse(opt.dataset.kegiatan || '[]');

                    const finalKegiatan = kegiatan.length > 0 ? kegiatan.map((cat, catI) => ({
                        id: 'cat_new_' + Date.now() + '_' + catI + '_' + Math.random().toString(36).substr(2, 9),
                        category: cat.category || '',
                        indicators: (cat.indicators || []).map((ind, indI) => ({
                            id: 'ind_new_' + Date.now() + '_' + catI + '_' + indI + '_' + Math.random().toString(36).substr(2, 9),
                            name: ind.name || '',
                            score: ind.score || 'Baik'
                        }))
                    })) : [
                        { id: 'cat_d1_' + Date.now(), category: 'Pembekalan', indicators: [{id: 'ind_d1_1_' + Date.now(), name: 'Menyimak Pembekalan', score: 'Baik'}, {id: 'ind_d1_2_' + Date.now(), name: 'Kerjasama Tim', score: 'Baik'}, {id: 'ind_d1_3_' + Date.now(), name: 'Keaktifan', score: 'Baik'}] },
                        { id: 'cat_d2_' + Date.now(), category: 'Realisasi', indicators: [{id: 'ind_d2_1_' + Date.now(), name: 'Kedisiplinan', score: 'Baik'}, {id: 'ind_d2_2_' + Date.now(), name: 'Kerjasama', score: 'Baik'}, {id: 'ind_d2_3_' + Date.now(), name: 'Pengumpulan Bahan Materi', score: 'Baik'}] },
                        { id: 'cat_d3_' + Date.now(), category: 'Persiapan Presentasi', indicators: [{id: 'ind_d3_1_' + Date.now(), name: 'Kreativitas', score: 'Baik'}, {id: 'ind_d3_2_' + Date.now(), name: 'Kerjasama', score: 'Baik'}, {id: 'ind_d3_3_' + Date.now(), name: 'Deadline Pengumpulan', score: 'Baik'}] },
                        { id: 'cat_d4_' + Date.now(), category: 'Presentasi', indicators: [{id: 'ind_d4_1_' + Date.now(), name: 'Penguasaan Materi', score: 'Baik'}, {id: 'ind_d4_2_' + Date.now(), name: 'Keaktifan Tanya Jawab', score: 'Baik'}, {id: 'ind_d4_3_' + Date.now(), name: 'Penyampaian', score: 'Baik'}] }
                    ];

                    const newId = Date.now();
                    this.pblActivities.push({
                        id: newId,
                        semester_project_id: source === 'semester_project' ? opt.value : '',
                        tema: title,
                        description: desc,
                        location: '',
                        kegiatan: finalKegiatan,
                        personality: [],
                        intelligence: [],
                        strength: [],
                        existing_images: [],
                        existing_photo_captions: [],
                        previews: [],
                        new_slots: [],
                        is_collapsed: false
                    });

                    this.$nextTick(() => {
                        const el = document.getElementById('activity-' + newId);
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                } catch (e) {
                    console.error(e);
                }
                select.value = '';
            },
            addManualActivity() {
                const newId = Date.now();
                this.pblActivities.push({
                    id: newId,
                    semester_project_id: '',
                    tema: '',
                    description: '',
                    location: '',
                    kegiatan: [
                        { id: 'cat_m1_' + Date.now(), category: 'Pembekalan', indicators: [{id: 'ind_m1_1_' + Date.now(), name: 'Menyimak Pembekalan', score: 'Baik'}, {id: 'ind_m1_2_' + Date.now(), name: 'Kerjasama Tim', score: 'Baik'}, {id: 'ind_m1_3_' + Date.now(), name: 'Keaktifan', score: 'Baik'}] },
                        { id: 'cat_m2_' + Date.now(), category: 'Realisasi', indicators: [{id: 'ind_m2_1_' + Date.now(), name: 'Kedisiplinan', score: 'Baik'}, {id: 'ind_m2_2_' + Date.now(), name: 'Kerjasama', score: 'Baik'}, {id: 'ind_m2_3_' + Date.now(), name: 'Pengumpulan Bahan Materi', score: 'Baik'}] },
                        { id: 'cat_m3_' + Date.now(), category: 'Persiapan Presentasi', indicators: [{id: 'ind_m3_1_' + Date.now(), name: 'Kreativitas', score: 'Baik'}, {id: 'ind_m3_2_' + Date.now(), name: 'Kerjasama', score: 'Baik'}, {id: 'ind_m3_3_' + Date.now(), name: 'Deadline Pengumpulan', score: 'Baik'}] },
                        { id: 'cat_m4_' + Date.now(), category: 'Presentasi', indicators: [{id: 'ind_m4_1_' + Date.now(), name: 'Penguasaan Materi', score: 'Baik'}, {id: 'ind_m4_2_' + Date.now(), name: 'Keaktifan Tanya Jawab', score: 'Baik'}, {id: 'ind_m4_3_' + Date.now(), name: 'Penyampaian', score: 'Baik'}] }
                    ],
                    personality: [],
                    intelligence: [],
                    strength: [],
                    existing_images: [],
                    existing_photo_captions: [],
                    previews: [],
                    new_slots: [],
                    is_collapsed: false
                });

                this.$nextTick(() => {
                    const el = document.getElementById('activity-' + newId);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            },
            importPblFromCollective() {
                const siswaId = '{{ $report->siswa_id }}';
                const period = this.period;

                if (!siswaId || !period) {
                    alert('Data Siswa atau Periode tidak valid.');
                    return;
                }

                if (!confirm(`Apakah Anda yakin ingin mengimpor nilai proyek bulanan (PBL) kolektif? Proyek bulanan yang saat ini diinput manual akan ditimpa.`)) {
                    return;
                }

                fetch('{{ route('admin.reports.import-pbl') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        siswa_id: siswaId,
                        period: period
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.pblActivities = data.activities;
                        alert('Nilai PBL berhasil diimpor!');
                    } else {
                        alert('Gagal mengimpor nilai PBL: ' + (data.message || 'Terjadi kesalahan.'));
                    }
                })
                .catch(error => {
                    console.error('Error importing PBL:', error);
                    alert('Terjadi kesalahan koneksi saat mengimpor PBL.');
                });
            },
            pblActivities: {!! json_encode($report->probingActivities->map(function($p) {
                // 1. Ambil data kegiatan (bisa dari relasi activityGrades, fallback ke JSON lama)
                $kegiatan = [];
                if ($p->activityGrades && $p->activityGrades->count() > 0) {
                    $tempGrades = [];
                    foreach ($p->activityGrades as $g) {
                        $catName = trim($g->phase_name ?? '');
                        if (empty($catName)) {
                            $indNameLower = strtolower($g->indicator_name ?? '');
                            if (str_contains($indNameLower, 'menyimak') || str_contains($indNameLower, 'keaktifan') || str_contains($indNameLower, 'tim') || str_contains($indNameLower, 'pembekalan')) {
                                $catName = 'Pembekalan';
                            } elseif (str_contains($indNameLower, 'kedisiplinan') || str_contains($indNameLower, 'realisasi') || str_contains($indNameLower, 'bahan') || str_contains($indNameLower, 'materi') || str_contains($indNameLower, 'tanggung jawab') || str_contains($indNameLower, 'tanggung')) {
                                $catName = 'Realisasi';
                            } elseif (str_contains($indNameLower, 'kreativitas') || str_contains($indNameLower, 'deadline') || str_contains($indNameLower, 'persiapan') || str_contains($indNameLower, 'visual')) {
                                $catName = 'Persiapan Presentasi';
                            } elseif (str_contains($indNameLower, 'presentasi') || str_contains($indNameLower, 'penguasaan') || str_contains($indNameLower, 'tanya') || str_contains($indNameLower, 'jawab') || str_contains($indNameLower, 'penyampaian')) {
                                $catName = 'Presentasi';
                            } else {
                                $catName = 'Pembekalan';
                            }
                        }
                        $tempGrades[$catName][] = [
                            'name' => $g->indicator_name,
                            'score' => $g->grade ?? 'Baik'
                        ];
                    }
                    // Fetch template kegiatan order if available
                    $templateKegiatan = null;
                    if ($p->semesterProject && $p->semesterProject->projectTemplate) {
                        $templateKegiatan = $p->semesterProject->projectTemplate->kegiatan;
                    }

                    // Fallback: search template by title matching the activity theme name
                    if (empty($templateKegiatan) && !empty($p->activity_name)) {
                        $tpl = \App\Models\ProjectTemplate::where('title', 'like', '%' . trim($p->activity_name) . '%')->first();
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
                        $sortedKegiatan = [];
                        foreach ($templateKegiatan as $tplCat) {
                            $tplCatName = trim($tplCat['category'] ?? '');
                            if (empty($tplCatName)) continue;

                            $matchedCatName = null;
                            foreach (array_keys($tempGrades) as $savedCatName) {
                                if (strcasecmp($savedCatName, $tplCatName) === 0) {
                                    $matchedCatName = $savedCatName;
                                    break;
                                }
                            }

                            if ($matchedCatName !== null) {
                                $savedIndicators = $tempGrades[$matchedCatName];
                                unset($tempGrades[$matchedCatName]);

                                $sortedIndicators = [];
                                $tplInds = $tplCat['indicators'] ?? [];
                                foreach ($tplInds as $tplInd) {
                                    $tplIndName = is_array($tplInd) ? ($tplInd['name'] ?? '') : $tplInd;
                                    $tplIndName = trim($tplIndName);
                                    if (empty($tplIndName)) continue;

                                    $foundMatch = false;
                                    foreach ($savedIndicators as $savedIdx => $savedInd) {
                                        if (strcasecmp(trim($savedInd['name']), $tplIndName) === 0) {
                                            $sortedIndicators[] = $savedInd;
                                            unset($savedIndicators[$savedIdx]);
                                            $foundMatch = true;
                                            break;
                                        }
                                    }

                                    if (!$foundMatch) {
                                        $sortedIndicators[] = ['name' => $tplIndName, 'score' => '-'];
                                    }
                                }
                                foreach ($savedIndicators as $savedInd) {
                                    $sortedIndicators[] = $savedInd;
                                }

                                $sortedKegiatan[] = [
                                    'category' => $matchedCatName,
                                    'indicators' => $sortedIndicators
                                ];
                            } else {
                                $newIndicators = [];
                                $tplInds = $tplCat['indicators'] ?? [];
                                foreach ($tplInds as $tplInd) {
                                    $tplIndName = is_array($tplInd) ? ($tplInd['name'] ?? '') : $tplInd;
                                    $tplIndName = trim($tplIndName);
                                    if (empty($tplIndName)) continue;
                                    $newIndicators[] = ['name' => $tplIndName, 'score' => '-'];
                                }
                                $sortedKegiatan[] = [
                                    'category' => $tplCatName,
                                    'indicators' => $newIndicators
                                ];
                            }
                        }
                        foreach ($tempGrades as $remainingCatName => $remainingInds) {
                            $sortedKegiatan[] = [
                                'category' => $remainingCatName,
                                'indicators' => $remainingInds
                            ];
                        }
                        $kegiatan = $sortedKegiatan;
                    } else {
                        foreach ($tempGrades as $catName => $indicators) {
                            $kegiatan[] = [
                                'category' => $catName,
                                'indicators' => $indicators
                            ];
                        }
                    }
                } else {
                    $kegiatan = $p->pbl_data['kegiatan'] ?? [];
                    if (empty($kegiatan)) {
                        $templateKegiatan = null;
                        if ($p->semesterProject && $p->semesterProject->projectTemplate) {
                            $templateKegiatan = $p->semesterProject->projectTemplate->kegiatan;
                        }
                        if (empty($templateKegiatan) && !empty($p->activity_name)) {
                            $tpl = \App\Models\ProjectTemplate::where('title', 'like', '%' . trim($p->activity_name) . '%')->first();
                            if ($tpl) { $templateKegiatan = $tpl->kegiatan; }
                        }
                        if (is_string($templateKegiatan)) {
                            $templateKegiatan = json_decode($templateKegiatan, true);
                        }
                        if (is_array($templateKegiatan) && !empty($templateKegiatan)) {
                            foreach ($templateKegiatan as $tplCat) {
                                $tplCatName = trim($tplCat['category'] ?? '');
                                if (empty($tplCatName)) continue;
                                $newIndicators = [];
                                foreach ($tplCat['indicators'] ?? [] as $tplInd) {
                                    $tplIndName = is_array($tplInd) ? ($tplInd['name'] ?? '') : $tplInd;
                                    $tplIndName = trim($tplIndName);
                                    if (!empty($tplIndName)) $newIndicators[] = ['name' => $tplIndName, 'score' => '-'];
                                }
                                $kegiatan[] = ['category' => $tplCatName, 'indicators' => $newIndicators];
                            }
                        }
                    }
                }

                // 2. Ambil data foto (dari relasi activityDocumentations, fallback ke JSON lama)
                $images = [];
                $captions = [];
                if ($p->activityDocumentations && $p->activityDocumentations->count() > 0) {
                    foreach ($p->activityDocumentations as $doc) {
                        $images[] = $doc->image_path;
                        $captions[] = $doc->caption ?? '';
                    }
                } else {
                    $images = $p->pbl_data['images'] ?? [];
                    $captions = array_fill(0, count($images), '');
                }

                // Map all kegiatan to have stable unique category and indicator IDs for Alpine.js rendering keys
                $kegiatan = array_map(function($catIdx, $cat) {
                    $inds = $cat['indicators'] ?? [];
                    $indicators = array_map(function($indIdx, $ind) use ($catIdx) {
                        return [
                            'id' => $ind['id'] ?? ('ind_' . $catIdx . '_' . $indIdx . '_' . uniqid()),
                            'name' => $ind['name'] ?? '',
                            'score' => $ind['score'] ?? 'Baik'
                        ];
                    }, array_keys($inds), $inds);

                    return [
                        'id' => $cat['id'] ?? ('cat_' . $catIdx . '_' . uniqid()),
                        'category' => $cat['category'] ?? '',
                        'indicators' => $indicators
                    ];
                }, array_keys($kegiatan), $kegiatan);

                return [
                    'id' => $p->id,
                    'semester_project_id' => $p->semester_project_id ?? '',
                    'tema' => $p->activity_name,
                    'location' => $p->location ?? '',
                    'description' => $p->description ?? '',
                    'kegiatan' => $kegiatan,
                    'personality' => $p->pbl_data['personality'] ?? [],
                    'intelligence' => $p->pbl_data['intelligence'] ?? [],
                    'strength' => $p->pbl_data['strength'] ?? [],
                    'existing_images' => $images,
                    'existing_photo_captions' => $captions,
                    'previews' => collect($images)->map(fn($path) => asset('storage/'.$path))->toArray(),
                    'new_slots' => []
                ];
            })) !!}.map(activity => {
                if (activity.kegiatan && !Array.isArray(activity.kegiatan)) {
                    activity.kegiatan = Object.values(activity.kegiatan);
                }
                if (activity.kegiatan) {
                    activity.kegiatan.forEach(cat => {
                        if (cat.indicators && !Array.isArray(cat.indicators)) {
                            cat.indicators = Object.values(cat.indicators);
                        }
                    });
                } else {
                    activity.kegiatan = [];
                }
                if (!activity.personality) activity.personality = [];
                if (!activity.intelligence) activity.intelligence = [];
                if (!activity.strength) activity.strength = [];
                return activity;
            }),

            removeExistingImage(activityIndex, imgIndex) {
                this.pblActivities[activityIndex].existing_images.splice(imgIndex, 1);
                this.pblActivities[activityIndex].previews.splice(imgIndex, 1);
            },

            updateTotalAyahs(index) {
                const id = this.diniyahItems[index].surah_id;
                const s = this.surahs.find(s => s.id == id);
                if (s) {
                    this.diniyahItems[index].totalAyahs = s.total_ayahs;
                    if (this.diniyahItems[index].end > s.total_ayahs) this.diniyahItems[index].end = s.total_ayahs;
                }
            },

            async handleSlotFile(activityIndex, slotIndex, event) {
                const file = event.target.files[0];
                if (!file) return;

                if (file.type.startsWith('image/')) {
                    try {
                        const compressedFile = await this.compressImage(file);
                        
                        // Menimpa file asli dengan file hasil kompresi di input element
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        event.target.files = dataTransfer.files;
                        
                        this.pblActivities[activityIndex].new_slots[slotIndex].preview = URL.createObjectURL(compressedFile);
                    } catch (error) {
                        console.error('Gagal mengkompres gambar:', error);
                        this.pblActivities[activityIndex].new_slots[slotIndex].preview = URL.createObjectURL(file);
                    }
                } else {
                    this.pblActivities[activityIndex].new_slots[slotIndex].preview = URL.createObjectURL(file);
                }
            },

            compressImage(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = event => {
                        const img = new Image();
                        img.src = event.target.result;
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            const MAX_WIDTH = 1200;
                            const MAX_HEIGHT = 1200;
                            let width = img.width;
                            let height = img.height;

                            if (width > height) {
                                if (width > MAX_WIDTH) {
                                    height *= MAX_WIDTH / width;
                                    width = MAX_WIDTH;
                                }
                            } else {
                                if (height > MAX_HEIGHT) {
                                    width *= MAX_HEIGHT / height;
                                    height = MAX_HEIGHT;
                                }
                            }
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            // Kompres ke JPEG dengan kualitas 70%
                            canvas.toBlob(blob => {
                                if (!blob) return reject(new Error('Canvas kosong'));
                                const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                resolve(newFile);
                            }, 'image/jpeg', 0.7);
                        };
                        img.onerror = error => reject(error);
                    };
                    reader.onerror = error => reject(error);
                });
            },

            calculatePredicate(score) {
                if (!score) return '-';
                score = parseFloat(score);
                for (let r of this.gradeRanges) { if (score >= r.min_score) return r.letter; }
                return 'D';
            },

            generateAcademicSummary(type) {
                let belowKKM = [];
                let aboveKKM = [];
                document.querySelectorAll('.academic-row').forEach(tr => {
                    const firstInput = tr.querySelector('input');
                    if (firstInput && firstInput.disabled) return;

                    const name = tr.querySelector('.subject-name')?.innerText || tr.querySelector('.subject-name')?.value;
                    let scoresToCheck = [];
                    if (type === 'pts_pas') {
                        scoresToCheck.push(parseFloat(tr.querySelector('.pts-input')?.value));
                        scoresToCheck.push(parseFloat(tr.querySelector('.pas-input')?.value));
                    } else if (type === 'remedial') {
                        scoresToCheck.push(parseFloat(tr.querySelector('.rem-input')?.value));
                    } else if (type === 'harian') {
                        scoresToCheck.push(parseFloat(tr.querySelector('.harian-input')?.value));
                    }

                    const val = scoresToCheck.filter(s => !isNaN(s) && s > 0);
                    if (val.length > 0) {
                        const isAnyBelow = val.some(s => s < 75);
                        if (name) {
                            if (isAnyBelow) belowKKM.push(name);
                            else aboveKKM.push(name);
                        }
                    }
                });
                
                let result = '';
                let typeLabel = '';
                if (type === 'pts_pas') {
                    typeLabel = this.academicType === 'PAS' ? 'Penilaian Akhir Semester' : 'Penilaian Tengah Semester';
                } else if (type === 'remedial') {
                    typeLabel = 'Remedial';
                } else {
                    typeLabel = 'Harian';
                }
                
                if (belowKKM.length === 0 && aboveKKM.length > 0) {
                    result = `Alhamdulillah, capaian nilai ${typeLabel} ananda secara keseluruhan sudah sangat baik dan melampaui KKM di semua mata pelajaran. Pertahankan semangat belajarnya ya Ananda!`;
                } else if (belowKKM.length > 0) {
                    result = `Secara umum nilai ${typeLabel} ananda sudah menunjukkan usaha yang baik. Namun, pada mata pelajaran ${belowKKM.join(', ')} ananda masih berada di bawah KKM. Diharapkan ananda dapat lebih fokus dan meningkatkan intensitas belajarnya di bidang tersebut agar hasilnya lebih optimal di masa mendatang.`;
                } else {
                    this.autoGenerateType = type;
                    this.showAutoGenerateConfirm = true;
                    return;
                }
                
                document.querySelector(`.summary-textarea[data-type="${type}"]`).value = result;
            },



            importExamGrades(event) {
                const siswaId = '{{ $report->siswa_id }}';
                const period = this.period;
                const academicType = this.academicType;

                if (!siswaId || !period) {
                    alert('Data Siswa atau Periode tidak valid.');
                    return;
                }

                if (!confirm(`Apakah Anda yakin ingin mengimpor nilai ujian untuk ${period} (${academicType || 'Semua'})? Nilai yang saat ini diinput di tabel akan ditimpa.`)) {
                    return;
                }

                const btn = event ? event.currentTarget : null;
                const originalText = btn ? btn.innerHTML : '';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-emerald-600 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengimpor...`;
                }

                fetch('{{ route('admin.reports.import-grades') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        siswa_id: siswaId,
                        period: period,
                        academic_type: academicType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const grades = data.grades;
                        document.querySelectorAll('.academic-row').forEach(tr => {
                            const subjectNameNode = tr.querySelector('.subject-name');
                            let subjectName = '';
                            if (subjectNameNode) {
                                subjectName = subjectNameNode.innerText || subjectNameNode.value;
                            }
                            subjectName = subjectName.trim().toLowerCase();

                            const matchedSubjectKey = Object.keys(grades).find(k => k.toLowerCase() === subjectName);
                            if (matchedSubjectKey) {
                                const val = grades[matchedSubjectKey];
                                
                                const ptsInput = tr.querySelector('.pts-input');
                                const pasInput = tr.querySelector('.pas-input');
                                const remInput = tr.querySelector('.rem-input');
                                const harianInput = tr.querySelector('.harian-input');

                                if (ptsInput && val.score_pts !== undefined) {
                                    ptsInput.value = val.score_pts !== null ? val.score_pts : '';
                                    ptsInput.dispatchEvent(new Event('input'));
                                }
                                if (pasInput && val.score_pas !== undefined) {
                                    pasInput.value = val.score_pas !== null ? val.score_pas : '';
                                    pasInput.dispatchEvent(new Event('input'));
                                }
                                if (remInput && val.score_remedial !== undefined) {
                                    remInput.value = val.score_remedial !== null ? val.score_remedial : '';
                                    remInput.dispatchEvent(new Event('input'));
                                }
                                if (harianInput && val.score_harian !== undefined) {
                                    harianInput.value = val.score_harian !== null ? val.score_harian : '';
                                    harianInput.dispatchEvent(new Event('input'));
                                }
                            }
                        });
                        alert('Nilai ujian berhasil diimpor!');
                    } else {
                        alert('Gagal mengimpor nilai: ' + (data.message || 'Terjadi kesalahan.'));
                    }
                })
                .catch(error => {
                    console.error('Error importing grades:', error);
                    alert('Terjadi kesalahan koneksi.');
                })
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });
            },

            validateForm(event) {
                // Synchronously set the draft value before submission to prevent Alpine race condition
                const draftInput = document.getElementById('is_draft_input');
                if (draftInput) {
                    draftInput.value = this.isDraft ? '1' : '0';
                }

                if (this.isDraft) {
                    event.target.submit();
                    return;
                }
                let errors = [];
                
                // 1. Validate Academic
                if (this.isAkademik) {
                    let hasGrades = false;
                    document.querySelectorAll('.academic-row').forEach(tr => {
                        const inputs = tr.querySelectorAll('input[type="number"]');
                        let rowHasValue = false;
                        inputs.forEach(input => {
                            if (input.value && !input.readOnly && !input.disabled) rowHasValue = true;
                        });
                        if (rowHasValue) hasGrades = true;
                    });
                    
                    if (!hasGrades) errors.push("Minimal satu nilai mata pelajaran harus diisi.");
                    
                    const summaries = ['pts_pas', 'remedial', 'harian'];
                    summaries.forEach(s => {
                        const area = document.querySelector(`.summary-textarea[data-type="${s}"]`);
                        if (!area || !area.value.trim()) {
                            errors.push(`Kesimpulan ${s.toUpperCase().replace('_', '/')} harus diisi.`);
                        }
                    });
                }

                // 2. Validate Diniyah
                if (this.isDiniyah) {
                    if (this.diniyahItems.length === 0 || !this.diniyahItems[0].surah_id) {
                        errors.push("Minimal satu hafalan surat harus diisi.");
                    }
                }

                // 3. Validate Probing
                if (this.isProbing) {
                    if (this.pblActivities.length === 0 || !this.pblActivities[0].tema) {
                        errors.push("Minimal satu tema PBL harus diisi.");
                    }
                }

                if (errors.length > 0) {
                    this.validationErrors = errors;
                    this.showErrorModal = true;
                    return false;
                }

                this.showFinalizeModal = true;
            }
        };
    }
</script>
@endsection
