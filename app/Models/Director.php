<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'comment',
        'status',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

}
