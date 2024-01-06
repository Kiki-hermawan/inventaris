<?php

namespace App\Controllers;

use App\Models\Modeldetailbarangmasuk;
use App\Models\Modeltempbarangmasuk;
use App\Models\Modelbarang;
use App\Models\Modelbarangmasuk;

use App\Controllers\BaseController;

class Barangmasuk extends BaseController
{

    public function index()
    {
        return view('barangmasuk/forminput');
    }

    public function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modeltemp = new Modeltempbarangmasuk();
            $data = [
                'datatemp' => $modeltemp->tampilDatatemp($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
            ];
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kdbarang = $this->request->getPost('kdbarang');

            $modelbarang = new Modelbarang();
            $ambilData = $modelbarang->find($kdbarang);

            if ($ambilData == NULL) {
                $json = [
                    'error' => 'data barang tidak di temukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga'],
                ];

                $json = [
                    'sukses' => $data
                ];
            }
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $kdbarang = $this->request->getPost('kdbarang');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $jumlah = $this->request->getPost('jumlah');


            $modelTempBarang = new Modeltempbarangmasuk();

            $modelTempBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kdbarang,
                'dethargajual' => $hargajual,
                'dethargamasuk' => $hargabeli,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli),
            ]);
            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelTempBarang = new Modeltempbarangmasuk();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function cariDataBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangmasuk/modalcaribarang')
            ];

            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function detailCariBarang()
    {
        if ($this->request->isAJAX()) {
            $cari = $this->request->getPost('cari');

            $modelBarang = new Modelbarang();
            $data = $modelBarang->cari($cari)->get();

            if ($data != null) {
                $json = [
                    'data' => view('barangmasuk/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];
            }
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function selesaiTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');

            $modelTemp = new Modeltempbarangmasuk();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $faktur]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'maaf, data item untuk faktur ini belum ada...'
                ];
            } else {
                $modelBrangMasuk = new Modelbarangmasuk();
                $totalSubtotal = 0;
                foreach ($dataTemp->getResultArray() as $total) :
                    $totalSubtotal += intval(($total['detsubtotal']));
                endforeach;

                $modelBrangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'totalharga' => $totalSubtotal
                ]);

                //simpan ke tabel detal barang masuk

                $modelDetailBarangMasuk = new Modeldetailbarangmasuk();
                foreach ($dataTemp->getResultArray() as $row) :
                    $modelDetailBarangMasuk->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'dethargamasuk' => $row['dethargamasuk'],
                        'dethargajual' => $row['dethargajual'],
                        'detjml' => $row['detjml'],
                        'detsubtotal' => $row['detsubtotal'],
                    ]);
                endforeach;

                //hapus data yang ada di tabel temp
                $modelTemp->emptyTable();


                $json = [
                    'sukses' => 'Transaksi berhasil di simpan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    public function data()
    {

        $tombolcari = $this->request->getPost('tombolcari');

        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('barangmasuk/data');
        } else {
            $cari = session()->get('cari_faktur');
        }

        $modelbarangMasuk = new Modelbarangmasuk();

        $totaldata = $cari ? $modelbarangMasuk->cari($cari)->countAllResults() : $modelbarangMasuk->countAllResults();

        $databarangmasuk = $cari ? $modelbarangMasuk->cari($cari)->paginate(5, 'barangmasuk') : $modelbarangMasuk->paginate(5, 'barangmasuk');

        $nohalaman = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') : 1;

        $data = [
            'tampildata' => $databarangmasuk,
            'pager' => $modelbarangMasuk->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari,
        ];


        return view('barangmasuk/viewdata', $data);
    }

    function dataItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();

            $data = [
                'tampildatadetail' => $modelDetail->dataDetail($faktur)
            ];
            $json = [
                'data' => view('barangmasuk/modeldetailitem', $data)
            ];
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function edit($faktur)
    {
        $modelbarangMasuk = new Modelbarangmasuk();
        $cekfaktur = $modelbarangMasuk->cekfaktur($faktur);

        if ($cekfaktur->getNumRows() > 0) {
            $row = $cekfaktur->getRowArray();

            $data = [
                'nofaktur' => $row['faktur'],
                'tanggal' => $row['tglfaktur']
            ];
            return view('barangmasuk/formedit', $data);
        } else {
            exit('data tidak ditemukan');
        }
    }

    function dataDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();

            $data = [
                'datadetail' => $modelDetail->dataDetail($faktur)
            ];
            $totalHargaFaktur = number_format($modelDetail->ambilTotalHarga($faktur), 0, ",", ".");
            $json = [
                'data' => view('barangmasuk/datadetail', $data),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($json);
        }
    }

    function edititem()
    {
        if ($this->request->isAJAX()) {
            $idedetail = $this->request->getPost('iddetail');
            $modelDetail = new Modeldetailbarangmasuk();
            $ambilData = $modelDetail->ambilDetailID($idedetail);

            $row = $ambilData->getRowArray();
            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'hargajual' => $row['dethargajual'],
                'hargabeli' => $row['dethargamasuk'],
                'jumlah' => $row['detjml'],
            ];

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
        }
    }

    function simpanDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $kdbarang = $this->request->getPost('kdbarang');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $jumlah = $this->request->getPost('jumlah');


            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kdbarang,
                'dethargajual' => $hargajual,
                'dethargamasuk' => $hargabeli,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli),
            ]);
            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function updateItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $kdbarang = $this->request->getPost('kdbarang');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $jumlah = $this->request->getPost('jumlah');
            $iddetail = $this->request->getPost('iddetail');


            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            $modelDetail->update($iddetail, [
                'dethargajual' => $hargajual,
                'dethargamasuk' => $hargabeli,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli),
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil diUpdate'
            ];
            echo json_encode($json);
        }
    }

    function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->delete($id);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('maaf tidak bisa di panggil');
        }
    }

    function hapusTransaksi()
    {
        $faktur = $this->request->getPost('faktur');

        $db = \Config\Database::connect();
        $modelBarangMasuk = new Modelbarangmasuk();

        $db->table('detail_barangmasuk')->delete(['detfaktur' => $faktur]);
        $modelBarangMasuk->delete($faktur);

        $json = [
            'sukses' => "Transaksi dengan Faktur : $faktur berhasil dihapus"
        ];
        echo json_encode($json);
    }
}
