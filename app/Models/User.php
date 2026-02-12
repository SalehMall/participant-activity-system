<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mentor_id', // WAJIB ADA: Agar bisa menyimpan siapa mentornya
        'performance_target',
        'intern_location',
        'intern_start_date',
        'intern_end_date',
    ];

    // Relasi: Mentor punya banyak Peserta (Interns)
    public function interns()
    {
        return $this->hasMany(User::class, 'mentor_id');
    }

    // Relasi: Peserta punya satu Mentor
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // Relasi ke laporan
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}