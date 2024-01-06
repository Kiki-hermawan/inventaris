<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelsatuan extends Model
{
    protected $table            = 'satuan';
    protected $primaryKey       = 'satid';
    protected $allowedFields    = [
        'satnama'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function cariData($cari)
    {
        return $this->table('satuan')->like('satnama', $cari);
    }
}
