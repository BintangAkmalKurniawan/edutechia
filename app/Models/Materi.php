<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'link_kuis',
        'link_diskusi',
        'deskripsi',
        'thumbnail',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(MateriVideo::class);
    }

    public function files()
    {
        return $this->hasMany(MateriFile::class);
    }
}
