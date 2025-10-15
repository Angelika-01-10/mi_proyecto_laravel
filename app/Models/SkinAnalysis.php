<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'skin_type',
        'probabilities'
    ];

    protected $casts = [
        'probabilities' => 'array', // para manejar JSON automáticamente
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
