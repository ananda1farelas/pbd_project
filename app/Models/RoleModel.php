<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RoleModel extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'idrole';
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'idrole', 'idrole');
    }
}
