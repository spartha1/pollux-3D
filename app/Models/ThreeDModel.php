<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThreeDModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'metadata',
        'user_id',
        'description'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
