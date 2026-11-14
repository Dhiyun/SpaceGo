<?php

class JadwalM {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllJadwal() {
        $this->db->query("SELECT j.*, r.kode, r.lantai, r.nama_ruangan, k.nama_kelas, k.prodi, d.nama_dosen
        FROM jadwal j 
        JOIN ruangan r ON j.kode_ruang = r.kode 
        JOIN kelas k ON j.id_kelas = k.id
        JOIN dosen d ON j.kode_dosen = d.kode
        ORDER BY r.kode, r.lantai ASC");

        return $this->db->resultSet();
    }
    
    private function cariIdKelas($namaKelas, $prodi)
    {
        $this->db->query("SELECT id FROM kelas WHERE nama_kelas = :nama_kelas AND prodi = :prodi");
        $this->db->bind(':nama_kelas', $namaKelas);
        $this->db->bind(':prodi', $prodi);
        $kelas = $this->db->single();

        return $kelas ? $kelas['id'] : null;
    }

    private function cariKodeDosen($namaDosen)
    {
        $this->db->query("SELECT kode FROM dosen WHERE nama_dosen = :nama_dosen");
        $this->db->bind(':nama_dosen', $namaDosen);
        $dosen = $this->db->single();

        return $dosen ? $dosen['kode'] : null;
    }

    public function tambahJadwal($data)
    {
        $idKelas = $this->cariIdKelas($data['nama_kelas'], $data['prodi']);
        $kodeDosen = $this->cariKodeDosen($data['nama_dosen']);

        $this->db->query("INSERT INTO jadwal (kode_ruang, id_kelas, kode_dosen, hari, waktu_mulai, waktu_selesai, matkul)
        VALUES (:kode_ruang, :id_kelas, :kode_dosen, :hari, :waktu_mulai, :waktu_selesai, :matkul)");
        $this->db->bind(':kode_ruang', $data['kode_ruang']);
        $this->db->bind(':id_kelas', $idKelas);
        $this->db->bind(':kode_dosen', $kodeDosen);
        $this->db->bind(':hari', $data['hari']);
        $this->db->bind(':waktu_mulai', $data['waktu_mulai']);
        $this->db->bind(':waktu_selesai', $data['waktu_selesai']);
        $this->db->bind(':matkul', $data['matkul']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusJadwal($id) {
        $query = "DELETE FROM jadwal WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editJadwal($data)
    {
        $idKelas = $this->cariIdKelas($data['nama_kelas'], $data['prodi']);
        $kodeDosen = $this->cariKodeDosen($data['nama_dosen']);

        $this->db->query("UPDATE jadwal SET
            kode_ruang = :kode_ruang,
            id_kelas = :id_kelas,
            kode_dosen = :kode_dosen,
            hari = :hari,
            waktu_mulai = :waktu_mulai,
            waktu_selesai = :waktu_selesai,
            matkul = :matkul
        WHERE id = :id");
        $this->db->bind(':kode_ruang', $data['kode_ruang']);
        $this->db->bind(':id_kelas', $idKelas);
        $this->db->bind(':kode_dosen', $kodeDosen);
        $this->db->bind(':hari', $data['hari']);
        $this->db->bind(':waktu_mulai', $data['waktu_mulai']);
        $this->db->bind(':waktu_selesai', $data['waktu_selesai']);
        $this->db->bind(':matkul', $data['matkul']);
        $this->db->bind(':id', $data['id']);

        $this->db->execute();
        return $this->db->rowCount();
    }
}
