<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'director',
        'year',
        'synopsis',
        'description',
        'comment',
        'status',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
