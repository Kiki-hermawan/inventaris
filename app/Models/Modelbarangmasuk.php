<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarangmasuk extends Model
{
    protected $table            = 'barangmasuk';
    protected $primaryKey       = 'faktur';
    protected $allowedFields    = [
        'faktur', 'tglfaktur', 'totalharga'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function cari($cari)
    {
        return $this->table('barangmasuk')->like('faktur', $cari);
    }

    public function cekfaktur($faktur)
    {
        return $this->table('barangmasuk')->getWhere([
            'sha1(faktur)' => $faktur
        ]);
    }
}
