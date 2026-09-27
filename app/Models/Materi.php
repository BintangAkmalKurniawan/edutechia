<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Materi extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'judul',
        'slug',
        'ringkasan',
        'link_kuis',
        'link_diskusi',
        'deskripsi',
        'video_url',
        'position',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(MateriVideo::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(MateriFile::class);
    }

    public function discussionPosts(): HasMany
    {
        return $this->hasMany(DiscussionPost::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }
}
