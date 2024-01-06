<?php

namespace App\Controllers;

use App\Models\Modelbarang;
use App\Models\Modelkategori;
use App\Models\Modelsatuan;

use App\Controllers\BaseController;

class Barang extends BaseController
{
    public function __construct()
    {
        $this->barang = new Modelbarang();
    }

    public function index()
    {
        $tombolcari = $this->request->getPost('tombolcari');

        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_barang',$cari);
            redirect()->to('barang');
        } else {
            $cari = session()->get('cari_barang');
        }

        $totaldata = $cari ? $this->barang->cari($cari)->countAllResults(): $this->barang->tampildata()->countAllResults();

        $databarang = $cari ? $this->barang->cari($cari)->paginate(5, 'barang') : $this->barang->tampildata()->paginate(5, 'barang');

        $nohalaman = $this->request->getVar('page_barang') ? $this->request->getVar('page_barang') : 1;
        
        $data = [
            'tampildata' => $databarang,
            'pager' => $this->barang->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari,
        ];
        return view('barang/viewbarang', $data);
    }

    public function tambah()
    {
        $modelkategori = new Modelkategori();
        $modelsatuan = new Modelsatuan();

        $data = [
            'datakategori' => $modelkategori->findAll(),
            'datasatuan' => $modelsatuan->findAll(),
        ];
        return view('barang/formtambah', $data);
    }

    public function simpandata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'kodebarang' => [
                'rules' => 'required|is_unique[barang.brgkode]',
                'label' => 'Kode barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah ada'
                ]
            ],

            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus angka'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus angka'
                ]
            ],
            'gambar' => [
                'rules' => 'mime_in[gambar,image/png,image/jpeg,image/jpg|ext_in[gambar,png,jpg,gif,jpeg]]',
                'label' => 'Gambar'
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error</h5>
                ' . $validation->listErrors() . '
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/barang/tambah');
        } else {
            $gambar = $_FILES['gambar']['name'];
            if ($gambar != NULL) {
                $namafile = $kodebarang;
                $filegambar = $this->request->getFile('gambar');
                $filegambar->move('assets/upload', $namafile . '.' . $filegambar->getExtension());

                $pathgambar = 'assets/upload/' . $filegambar->getName();
            } else {
                $pathgambar = '';
            }

            $this->barang->insert([
                'brgkode' => $kodebarang,
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathgambar
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">barang dengan kode <strong>' . $kodebarang . '</strong> berhasil ditambahkan </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('barang');
        }
    }

    public function edit($kode)
    {
        $rowData = $this->barang->find($kode);

        if ($rowData) {

            $modelkategori = new Modelkategori();
            $modelsatuan = new Modelsatuan();


            $data = [
                'kodebarang' => $rowData['brgkode'],
                'namabarang' => $rowData['brgnama'],
                'kategori' => $rowData['brgkatid'],
                'satuan' => $rowData['brgsatid'],
                'harga' => $rowData['brgharga'],
                'stok' => $rowData['brgstok'],
                'gambar' => $rowData['brggambar'],
                'datakategori' => $modelkategori->findAll(),
                'datasatuan' => $modelsatuan->findAll(),
            ];

            return view('barang/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus angka'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus angka'
                ]
            ],
            'gambar' => [
                'rules' => 'mime_in[gambar,image/png,image/jpeg,image/jpg|ext_in[gambar,png,jpg,gif,jpeg]]',
                'label' => 'Gambar'
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error</h5>
                ' . $validation->listErrors() . '
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/barang/tambah');
        } else {
            $rowData = $this->barang->find($kodebarang);
            $pathgambarlama = $rowData['brggambar'];

            $gambar = $_FILES['gambar']['name'];
            if ($gambar != NULL) {
                ($pathgambarlama == '' || $pathgambarlama == NULL) ? '' : unlink($pathgambarlama);

                $namafile = $kodebarang;
                $filegambar = $this->request->getFile('gambar');
                $filegambar->move('assets/upload', $namafile . '.' . $filegambar->getExtension());

                $pathgambar = 'assets/upload/' . $filegambar->getName();
            } else {
                $pathgambar = $pathgambarlama;
            }

            $this->barang->update($kodebarang, [
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathgambar
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">barang dengan kode <strong>' . $kodebarang . '</strong> berhasil diupdate </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('barang');
        }
    }

    public function hapus($kode)
    {
        $rowData = $this->barang->find($kode);

        if ($rowData) {

            $this->barang->delete($kode);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                Data barang berhasil dihapus
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('barang');
        } else {
            exit('Data tidak ditemukan');
        }
    }
}
