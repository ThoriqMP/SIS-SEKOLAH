@extends('layouts.app')

@section('title', 'Login - ' . config('app.name', 'SIS Sekolah'))

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-900 via-purple-950 to-slate-900">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Masuk ke Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">{{ config('app.name', 'SIS Sekolah') }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ request()->is('student*') ? route('student.login.submit') : route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-purple-600">
                    Ingat saya
                </label>
            </div>

            <button type="submit"
                class="w-full py-3 bg-purple-700 hover:bg-purple-800 text-white font-semibold rounded-xl transition">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
