<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $table = 'gallery_albums';
    protected $fillable = ['judul', 'deskripsi', 'cover_path', 'tanggal', 'urutan', 'aktif'];
    protected $casts = ['tanggal' => 'date', 'aktif' => 'boolean'];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id')->orderBy('urutan');
    }
}
