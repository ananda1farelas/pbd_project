<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModel;

class SatuanModel extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'idsatuan';
    public $timestamps = false;

    protected $fillable = ['nama_satuan', 'status'];

    public function barang()
    {
        return $this->hasMany(BarangModel::class, 'idsatuan', 'idsatuan');
    }
}
