<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'image',
        'desc',
        'upload_at',
        'is_active'
    ];

    protected $casts = [
        'upload_at' => 'date',
        'is_active' => 'boolean'
    ];
}
