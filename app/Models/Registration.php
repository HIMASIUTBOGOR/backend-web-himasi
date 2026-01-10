<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes, HasUuids;
    protected $fillable = [
        'fullname',
        'nim',
        'semester',
        'no_wa',
        'department_id',
        'reason',
    ];

    public function department()
    {
        return $this->belongsTo(Departemen::class, 'department_id');
    }
}
