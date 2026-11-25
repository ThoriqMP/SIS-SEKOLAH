@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pengaturan Tanggal Rapor</h1>
        <p class="text-gray-600 mt-2">Atur tahun ajaran aktif serta tanggal dan tempat penerbitan rapor untuk dicetak.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Gagal</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Terdapat Kesalahan</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700">Form Pengaturan</h2>
        </div>
        
        <form action="{{ route('admin.reports.settings.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tahun Ajaran -->
                <div>
                    <label for="year_name" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran Aktif</label>
                    <input type="text" name="year_name" id="year_name" 
                        value="{{ old('year_name', $activeYear->year_name ?? '') }}" 
                        placeholder="Contoh: 2025/2026"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        required>
                    <p class="text-xs text-gray-500 mt-1">Masukkan tahun ajaran yang sedang berjalan.</p>
                </div>

                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester" id="semester" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" required>
                        <option value="Ganjil" {{ old('semester', $activeYear->semester ?? '') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester', $activeYear->semester ?? '') === 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tanggal Rapor -->
                <div>
                    <label for="report_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rapor</label>
                    <input type="date" name="report_date" id="report_date" 
                        value="{{ old('report_date', optional($activeYear?->raportSetting)->report_date ?? '') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        required>
                    <p class="text-xs text-gray-500 mt-1">Tanggal ini akan dicetak di bagian tanda tangan rapor.</p>
                </div>

                <!-- Tempat Penerbitan -->
                <div>
                    <label for="place" class="block text-sm font-medium text-gray-700 mb-2">Tempat Penandatanganan</label>
                    <input type="text" name="place" id="place" 
                        value="{{ old('place', optional($activeYear?->raportSetting)->place ?? 'Bogor') }}" 
                        placeholder="Contoh: Bogor"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        required>
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-gray-200 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Tahun Ajaran (Opsional) -->
    @if($academicYears->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700">Riwayat Tahun Ajaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($academicYears as $year)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $year->year_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $year->semester }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($year->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
