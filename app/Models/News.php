<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'photo',
        'title',
        'desc',
        'author',
        'published_at',
        'is_active',
        'slug',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Enumeration::class, 'category_id');
    }
}
