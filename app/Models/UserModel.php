<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';          // nama tabel sesuai DB
    protected $primaryKey = 'iduser';   // pk sesuai DB
    public $timestamps = false;         // set true kalau tabel ada timestamps

    protected $fillable = [
        'username',
        'password',
        'idrole'
    ];

    // Jika password sudah di-hash sebelum disimpan dari controller, 
    // jangan gunakan mutator. Kalau mau otomatis hash:
    public function setPasswordAttribute($value)
    {
        // hanya hash jika belum di-hash (opsional)
        if ( !empty($value) && \Illuminate\Support\Str::startsWith($value, '$2y$') === false ) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Relasi ke role (opsional)
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'idrole', 'idrole');
    }
}