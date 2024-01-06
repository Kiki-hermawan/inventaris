<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeltempbarangmasuk extends Model
{
    protected $table            = 'temp_barangmasuk';
    protected $primaryKey       = 'iddetail';
    protected $allowedFields    = [
        'iddetail', 'detfaktur', 'detbrgkode', 'dethargamasuk', 'dethargajual', 'detjml', 'detsubtotal'
    ];
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function tampilDataTemp($faktur)
    {
        return $this->table('temp_barangmasuk')->join('barang', 'brgkode=detbrgkode')->where(['detfaktur' => $faktur])->get();
    }
}
