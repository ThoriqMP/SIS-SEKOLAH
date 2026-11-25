@extends('layouts.app')

@section('title', 'Manajemen Proyek Bulanan')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Proyek Bulanan</h1>
                <p class="text-xs sm:text-sm text-gray-550 dark:text-gray-400 font-medium">Buat dan kelola proyek bulanan untuk referensi rapor probing siswa.</p>
            </div>
            <a href="{{ route('project-templates.create') }}" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-95 duration-150">
                + Tambah Proyek Bulanan
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="p-4 bg-emerald-55 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-450 rounded-2xl text-xs font-bold flex items-center gap-2">
                <span>✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- Templates Grid/Table -->
        <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 overflow-hidden shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-150 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Judul Proyek</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Deskripsi Proyek</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($templates as $template)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $template->title }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs sm:max-w-md">
                                    <div class="text-xs font-medium text-gray-550 dark:text-gray-400 truncate">{{ $template->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('project-templates.edit', $template) }}" class="p-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 text-gray-650 dark:text-gray-300 rounded-lg text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('project-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek bulanan ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-455 rounded-lg text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">📋</div>
                                    <div class="text-sm font-bold text-gray-500">Belum ada proyek bulanan yang dibuat.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($templates->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                    {{ $templates->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
