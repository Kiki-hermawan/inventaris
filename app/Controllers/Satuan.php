<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelsatuan;

class Satuan extends BaseController
{
    public function __construct()
    {
        $this->satuan = new Modelsatuan();
    }

    public function index()
    {

        $tombolcari = $this->request->getPost('tombolcari');

        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_satuan', $cari);
            redirect()->to('satuan');
        } else {
            $cari = session()->get('cari_satuan');
        }

        $totaldata = $cari ? $this->satuan->cariData($cari)->countAllResults() : $this->satuan->countAllResults();

        $datasatuan = $cari ? $this->satuan->cariData($cari)->paginate(5, 'satuan') : $this->satuan->paginate(5, 'satuan');

        $nohalaman = $this->request->getVar('page_satuan') ? $this->request->getVar('page_satuan') : 1;
        $data = [
            'tampildata' => $datasatuan,
            'pager' => $this->satuan->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari
        ];

        return view('satuan/viewsatuan', $data);
    }

    public function formtambah()
    {
        return view('satuan/formtambah');
    }


    public function simpandata()
    {
        $namasatuan = $this->request->getVar('namasatuan');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamasatuan' => '<br><div class="alert alert-danger">' . $validation->listErrors() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formtambah');
        } else {
            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">Data satuan berhasil ditambahkan</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('satuan');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->satuan->find($id);

        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['satnama']
            ];

            return view('satuan/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idsatuan = $this->request->getVar('idsatuan');
        $namasatuan = $this->request->getVar('namasatuan');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama satuan',
                'error' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamasatuan' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('satuan/formedit/' . $idsatuan);
        } else {
            $this->satuan->update($idsatuan, [
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                Data satuan berhasil diupdate
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('satuan');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->satuan->find($id);

        if ($rowData) {

            $this->satuan->delete($id);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                Data satuan berhasil dihapus
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('satuan');
        } else {
            exit('Data tidak ditemukan');
        }
    }
}
