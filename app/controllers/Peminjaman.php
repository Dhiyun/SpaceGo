<?php

class Peminjaman extends Controller
{
    public function tambah()
    {
        $this->checkRole('User');

        try {
            if ($this->model("PeminjamanM")->tambahPeminjaman($_POST) > 0) {
                Flasher::setFlash('Peminjaman Berhasil Ditambahkan', '', 'success');
            } else {
                Flasher::setFlash('Peminjaman Gagal Ditambahkan', '', 'danger');
            }
            header('Location: ' . USERURL . '/peminjaman');
            exit;
        } catch (PDOException $e) {
            Flasher::setFlash('Peminjaman Tidak Sesuai atau Sudah Ada', '', 'warning');
            header('Location: ' . USERURL . '/peminjaman');
            exit;
        }
    }

    public function hapus($kode)
    {
        $this->checkRole('User');

        if ($this->model("PeminjamanM")->hapusPeminjaman($kode) > 0) {
            Flasher::setFlash('Peminjaman Berhasil Dihapus', '', 'success');
            header('Location: ' . USERURL . '/peminjaman');
            exit;
        } else {
            Flasher::setFlash('Peminjaman Gagal Dihapus', '', 'danger');
            header('Location: ' . USERURL . '/peminjaman');
            exit;
        }
    }

    public function approve($id)
    {
        $this->checkRole('Admin');

        $model = $this->model("PeminjamanM");

        try {
            if ($model->editApprove($id) > 0) {
                Flasher::setFlash('Peminjaman Diterima', '', 'success');
                header('Location: ' . ADMINURL . '/peminjaman');
                exit;
            } else {
                Flasher::setFlash('Terjadi kesalahan', '', 'danger');
                header('Location: ' . ADMINURL . '/peminjaman');
                exit;
            }
        } catch (PDOException $e) {

            Flasher::setFlash('Tidak Ada Perubahan atau Tidak Sesuai', '', 'warning');
            header('Location: ' . ADMINURL . '/peminjaman');
            exit;
        }
    }

    public function decline($id)
    {
        $this->checkRole('Admin');

        $model = $this->model("PeminjamanM");

        try {
            if ($model->editDecline($id) > 0) {
                Flasher::setFlash('Peminjaman Ditolak', '', 'danger');
                header('Location: ' . ADMINURL . '/peminjaman');
                exit;
            } else {
                Flasher::setFlash('Terjadi kesalahan', '', 'danger');
                header('Location: ' . ADMINURL . '/peminjaman');
                exit;
            }
        } catch (PDOException $e) {

            Flasher::setFlash('Tidak Ada Perubahan atau Tidak Sesuai', '', 'warning');
            header('Location: ' . ADMINURL . '/peminjaman');
            exit;
        }
    }
}

?>