<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','excerpt','content','user_id',
        'cover_image','source',
    ];

    // URL publik untuk cover
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }

    public function getCoverThumbUrlAttribute(): ?string
    {
        if (!$this->cover_image) return null;
        // thumb letaknya di covers/thumbs/namafile.webp
        $thumb = preg_replace('#^covers/#', 'covers/thumbs/', $this->cover_image);
        return asset('storage/'.$thumb);
    }

    // Relasi penulis (opsional jika ada)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

