<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerja extends Model
{
use HasFactory;

protected $table = 'pekerja'; 

protected $fillable = [
'user_id', 
'pekerja_id',
'nama',
'role',
'nohp',
'email',
'alamat',
'tanggal_masuk',
];

public function user()
{
return $this->belongsTo(User::class, 'user_id', 'id');
}
}