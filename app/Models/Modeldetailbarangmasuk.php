<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeldetailbarangmasuk extends Model
{
    protected $table            = 'detail_barangmasuk';
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

    public function dataDetail($faktur)
    {
        return $this->table('detail_barangmasuk')->join('barang', 'brgkode=detbrgkode')->where('detfaktur', $faktur)->get();
    }

    function ambilTotalHarga($faktur)
    {
        $query = $this->table('detail_barangmasuk')->getWhere([
            'detfaktur' => $faktur
        ]);
        $totalHarga = 0;
        foreach ($query->getResultArray() as $r) {
            $totalHarga += $r['detsubtotal'];
        }
        return $totalHarga;
    }

    public function ambilDetailID($iddetail)
    {
        return $this->table('detail_barangmasuk')->join('barang', 'brgkode=detbrgkode')->where('iddetail', $iddetail)->get();
    }

    public function hapusfaktur($faktur)
    {
        return $this->table('detail_barangmasuk')->delete('detfaktur', $faktur);
    }
}
