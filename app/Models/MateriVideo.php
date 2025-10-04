<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriVideo extends Model
{
    use HasFactory;

    protected $fillable = ['materi_id', 'video_path'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
