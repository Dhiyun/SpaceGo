<?php

class User extends Controller {
    public function index() {
        $this->checkRole('User');
                
        $data['judul'] = 'Home';

        $this->view('user/templates/header', $data);
        $this->view('user/templates/navbar');
        $this->view('user/dashboard/index', $data);
        $this->view('user/templates/footer');
    }

    public function ruang()
    {
        $this->checkRole('User');
        $data['judul'] = 'Ruang';

        $modelR = $this->model('RuangM');
        $modelJ = $this->model('JadwalM');
        $modelD = $this->model('DosenM');

        $data['rng'] = $modelR->getAllRuangFasilitas();
        $data['dsn'] = $modelD->getAllDosen();
        $data['jdwl'] = $modelJ->getAllJadwal();
        $data['lnt'] = $modelR->getAllLantai();

        $this->view('user/templates/header', $data);
        $this->view('user/templates/navbar');
        $this->view('user/ruang/index', $data);
        $this->view('user/templates/footer');
    }

    public function peminjaman()
    {
        $this->checkRole('User');

        $data['judul'] = 'Peminjaman';

        $modelJ = $this->model('JadwalM');
        $modelU = $this->model('PeminjamanM');
        $modelR = $this->model('RuangM');

        $data['jdwl'] = $modelJ->getAllJadwal();
        $data['rng'] = $modelR->getAllRuangFasilitas();
        $data['usr'] = $modelU->getAllUser();

        $this->view('user/templates/header', $data);
        $this->view('user/templates/navbar');
        $this->view('user/peminjaman/index', $data);
        $this->view('user/templates/footer');
    }

    public function history()
    {
        $this->checkRole('User');

        $data['judul'] = 'History';

        $model = $this->model('PeminjamanM');

        $data['usr'] = $model->getAllUser();
        $data['pmnjm'] = $model->getAllPeminjaman();

        $this->view('user/templates/header', $data);
        $this->view('user/templates/navbar');
        $this->view('user/history/index', $data);
        $this->view('user/templates/footer');
    }

    public function laporan($id)
    {
        $this->checkRole('User');

        $data['judul'] = 'Laporan';

        $model = $this->model('PeminjamanM');

        $data['pmnjm'] = $model->getIdPeminjaman($id);

        $this->view('user/laporan/index', $data);
    }
}
