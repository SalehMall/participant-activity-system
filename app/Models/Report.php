<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use HasFactory;

class Report extends Model
{
    

    protected $fillable = [
    'user_id',
    'tanggal',
    'aktivitas',
    'status',
    'komentar_mentor',
    'gambar', // <--- PASTIKAN INI ADA
];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}