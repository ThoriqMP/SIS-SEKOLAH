<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherNoteSuggestion;
use Illuminate\Http\Request;

class TeacherNoteSuggestionController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.academic-settings.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        TeacherNoteSuggestion::create($request->all());

        return redirect()->back()->with('success', 'Saran catatan berhasil ditambahkan.');
    }

    public function update(Request $request, TeacherNoteSuggestion $suggestion)
    {
        $request->validate([
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $suggestion->update($request->all());

        return redirect()->back()->with('success', 'Saran catatan berhasil diperbarui.');
    }

    public function destroy(TeacherNoteSuggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->back()->with('success', 'Saran catatan berhasil dihapus.');
    }
}
