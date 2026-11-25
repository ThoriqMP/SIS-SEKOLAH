<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectTemplate;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ProjectTemplate::orderBy('title')
            ->paginate(10);

        return view('admin.project_templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.project_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'kegiatan' => 'nullable|array',
        ], [
            'title.required' => 'Judul proyek bulanan wajib diisi.',
            'description.required' => 'Deskripsi proyek bulanan wajib diisi.',
        ]);

        ProjectTemplate::create([
            'title' => $request->title,
            'description' => $request->description,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('project-templates.index')
            ->with('success', 'Proyek Bulanan berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectTemplate $projectTemplate)
    {
        return view('admin.project_templates.edit', compact('projectTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectTemplate $projectTemplate)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'kegiatan' => 'nullable|array',
        ], [
            'title.required' => 'Judul proyek bulanan wajib diisi.',
            'description.required' => 'Deskripsi proyek bulanan wajib diisi.',
        ]);

        $projectTemplate->update([
            'title' => $request->title,
            'description' => $request->description,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('project-templates.index')
            ->with('success', 'Proyek Bulanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectTemplate $projectTemplate)
    {
        $projectTemplate->delete();

        return redirect()->route('project-templates.index')
            ->with('success', 'Proyek Bulanan berhasil dihapus.');
    }
}

