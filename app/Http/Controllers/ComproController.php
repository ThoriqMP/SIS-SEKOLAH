<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolHeroSetting;
use App\Models\SchoolPillar;
use App\Models\SchoolProgram;
use App\Models\CurriculumLoad;
use App\Models\InvestmentItem;
use App\Models\InvestmentPromo;
use App\Models\GalleryAlbum;
use App\Models\LearningMethod;
use App\Models\LearningHighlight;

class ComproController extends Controller
{
    public function index()
    {
        try {
            $settings           = SchoolHeroSetting::getAllSettings();
            $pillars            = SchoolPillar::orderBy('urutan')->get();
            $programs           = SchoolProgram::orderBy('urutan')->get();
            $curriculumLoads    = CurriculumLoad::with('activities')->orderBy('urutan')->get();
            $investmentItems    = InvestmentItem::where('aktif', true)->orderBy('urutan')->get();
            $activePromo        = InvestmentPromo::where('aktif', true)->with('discounts.investmentItem')->first();
            $galleryAlbums      = GalleryAlbum::where('aktif', true)->with('photos')->orderBy('urutan')->get();
            $learningMethods    = LearningMethod::orderBy('urutan')->get();
            $learningHighlights = LearningHighlight::orderBy('urutan')->get();
            $schoolProfile      = \App\Models\ProfilSekolah::first();
        } catch (\Throwable $e) {
            $settings           = [];
            $pillars            = collect();
            $programs           = collect();
            $curriculumLoads    = collect();
            $investmentItems    = collect();
            $activePromo        = null;
            $galleryAlbums      = collect();
            $learningMethods    = collect();
            $learningHighlights = collect();
            $schoolProfile      = null;
        }

        // Dynamically translate DB entries if locale is set to English
        if (app()->getLocale() === 'en') {
            // Translate Settings
            foreach ($settings as $key => $val) {
                $settings[$key] = \Lang::has('messages.compro.settings.' . $key) ? __('messages.compro.settings.' . $key) : $val;
            }
            
            // Translate Pillars
            foreach ($pillars as $p) {
                $p->judul = \Lang::has('messages.compro.pillars.' . $p->id . '.judul') ? __('messages.compro.pillars.' . $p->id . '.judul') : $p->judul;
                $p->deskripsi = \Lang::has('messages.compro.pillars.' . $p->id . '.deskripsi') ? __('messages.compro.pillars.' . $p->id . '.deskripsi') : $p->deskripsi;
            }
            
            // Translate Programs
            foreach ($programs as $p) {
                $p->judul = \Lang::has('messages.compro.programs.' . $p->jenjang . '.judul') ? __('messages.compro.programs.' . $p->jenjang . '.judul') : $p->judul;
                $p->deskripsi = \Lang::has('messages.compro.programs.' . $p->jenjang . '.deskripsi') ? __('messages.compro.programs.' . $p->jenjang . '.deskripsi') : $p->deskripsi;
                $p->catatan_kaki = \Lang::has('messages.compro.programs.' . $p->jenjang . '.catatan_kaki') ? __('messages.compro.programs.' . $p->jenjang . '.catatan_kaki') : $p->catatan_kaki;
            }
            
            // Translate Curriculum Loads
            foreach ($curriculumLoads as $c) {
                $c->nama = \Lang::has('messages.compro.curriculum_loads.' . $c->kode . '.nama') ? __('messages.compro.curriculum_loads.' . $c->kode . '.nama') : $c->nama;
                $c->deskripsi = \Lang::has('messages.compro.curriculum_loads.' . $c->kode . '.deskripsi') ? __('messages.compro.curriculum_loads.' . $c->kode . '.deskripsi') : $c->deskripsi;
                
                foreach ($c->activities as $a) {
                    $a->nama = \Lang::has('messages.compro.activities.' . $a->nama . '.nama') ? __('messages.compro.activities.' . $a->nama . '.nama') : $a->nama;
                    $a->deskripsi = \Lang::has('messages.compro.activities.' . $a->nama . '.deskripsi') ? __('messages.compro.activities.' . $a->nama . '.deskripsi') : $a->deskripsi;
                }
            }
            
            // Translate Investment Items
            foreach ($investmentItems as $item) {
                $item->nama = \Lang::has('messages.compro.investment_items.' . $item->nama . '.nama') ? __('messages.compro.investment_items.' . $item->nama . '.nama') : $item->nama;
                if ($item->keterangan) {
                    $item->keterangan = \Lang::has('messages.compro.investment_items.' . $item->nama . '.keterangan') ? __('messages.compro.investment_items.' . $item->nama . '.keterangan') : $item->keterangan;
                }
            }
            
            // Translate Active Promo
            if ($activePromo) {
                $activePromo->label = \Lang::has('messages.compro.promo.' . $activePromo->label . '.label') ? __('messages.compro.promo.' . $activePromo->label . '.label') : $activePromo->label;
                $activePromo->deskripsi = \Lang::has('messages.compro.promo.' . $activePromo->label . '.deskripsi') ? __('messages.compro.promo.' . $activePromo->label . '.deskripsi') : $activePromo->deskripsi;
                foreach ($activePromo->discounts as $d) {
                    $d->keterangan = \Lang::has('messages.compro.promo.discounts.' . $d->keterangan) ? __('messages.compro.promo.discounts.' . $d->keterangan) : $d->keterangan;
                }
            }
            
            // Translate Learning Methods
            foreach ($learningMethods as $m) {
                $m->judul = \Lang::has('messages.compro.methods.' . $m->judul . '.judul') ? __('messages.compro.methods.' . $m->judul . '.judul') : $m->judul;
                $m->deskripsi = \Lang::has('messages.compro.methods.' . $m->judul . '.deskripsi') ? __('messages.compro.methods.' . $m->judul . '.deskripsi') : $m->deskripsi;
            }
            
            // Translate Learning Highlights
            foreach ($learningHighlights as $h) {
                $h->teks = \Lang::has('messages.compro.highlights.' . $h->teks) ? __('messages.compro.highlights.' . $h->teks) : $h->teks;
            }
        }

        return view('compro.index', compact(
            'settings',
            'pillars',
            'programs',
            'curriculumLoads',
            'investmentItems',
            'activePromo',
            'galleryAlbums',
            'learningMethods',
            'learningHighlights',
            'schoolProfile'
        ));
    }
}
