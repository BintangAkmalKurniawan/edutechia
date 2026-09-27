<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriFile extends Model
{
    protected $fillable = [
        'materi_id',
        'file_path',
        'original_name',
    ];

    /**
     * Get the materi that owns the file.
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
