<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelkategori extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'katid';
    protected $allowedFields    = [
        'katid', 'katnama'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function cariData($cari)
    {
        return $this->table('kategori')->like('katnama', $cari);
    }
}
