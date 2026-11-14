<?php

class RuangM
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getCountRuang()
    {
        $this->db->query("SELECT COUNT(*) as hitung FROM ruangan");
        $this->db->execute();
        $result = $this->db->single();
        return $result['hitung'];
    }

    public function getAllRuangFasilitas()
    {
        $this->db->query("SELECT dr.*, r.*, GROUP_CONCAT(f.nama_barang) as nama_barang
        FROM ruangan r
        LEFT JOIN detail_ruangan dr ON dr.kode_ruangan = r.kode
        LEFT JOIN fasilitas f ON dr.id_fasilitas = f.id
        GROUP BY r.kode
        ORDER BY lantai, kode ASC");
        return $this->db->resultSet();
    }

    public function getAllLantai()
    {
        $this->db->query("SELECT DISTINCT lantai FROM ruangan ORDER BY lantai ASC");
        return $this->db->resultSet();
    }

    public function getAllFasilitas()
    {
        $this->db->query("SELECT * FROM fasilitas");
        return $this->db->resultSet();
    }

    public function tambahRuang($data)
    {
        if ((int) $data['kapasitas'] < 0) {
            throw new PDOException('Kapasitas tidak boleh negatif');
        }

        $queryR = "INSERT INTO ruangan VALUES (:kode, :nama_ruangan, :lantai, :kapasitas)";
        $this->db->query($queryR);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('nama_ruangan', $data['nama_ruangan']);
        $this->db->bind('lantai', $data['lantai']);
        $this->db->bind('kapasitas', $data['kapasitas']);
        $this->db->execute();

        $kode_ruangan = $data['kode'];

        foreach ($data['fasilitas_checked'] as $fasilitas_checked) {
            $queryDR = "INSERT INTO detail_ruangan (kode_ruangan, id_fasilitas) VALUES (:kode_ruangan, :id_fasilitas)";
            $this->db->query($queryDR);
            $this->db->bind('kode_ruangan', $kode_ruangan);
            $this->db->bind('id_fasilitas', $fasilitas_checked);
            $this->db->execute();
        }
        return $this->db->rowCount();
    }

    public function hapusRuang($kode)
    {
        $this->db->query("SELECT COUNT(*) as jumlah FROM peminjaman WHERE kode_ruangan = :kode");
        $this->db->bind('kode', $kode);
        $peminjaman = $this->db->single();

        if ($peminjaman['jumlah'] > 0) {
            throw new PDOException('Ruangan masih memiliki peminjaman');
        }

        $this->db->query("DELETE FROM detail_ruangan WHERE kode_ruangan = :kode");
        $this->db->bind('kode', $kode);
        $this->db->execute();

        $this->db->query("DELETE FROM jadwal WHERE kode_ruang = :kode");
        $this->db->bind('kode', $kode);
        $this->db->execute();

        $query = "DELETE FROM ruangan WHERE kode = :kode";
        $this->db->query($query);
        $this->db->bind('kode', $kode);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editRuang($data)
    {
        if ((int) $data['kapasitas'] < 0) {
            throw new PDOException('Kapasitas tidak boleh negatif');
        }

        $queryR = "UPDATE ruangan SET 
            kode = :kode,
            nama_ruangan = :nama_ruangan,
            lantai = :lantai,
            kapasitas = :kapasitas
            WHERE kode = :old_kode";

        $this->db->query($queryR);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('nama_ruangan', $data['nama_ruangan']);
        $this->db->bind('lantai', $data['lantai']);
        $this->db->bind('kapasitas', $data['kapasitas']);
        $this->db->bind('old_kode', $data['old_kode']);
        $this->db->execute();

        $this->db->query("UPDATE jadwal SET kode_ruang = :kode WHERE kode_ruang = :old_kode");
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('old_kode', $data['old_kode']);
        $this->db->execute();

        $this->db->query("UPDATE peminjaman SET kode_ruangan = :kode WHERE kode_ruangan = :old_kode");
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('old_kode', $data['old_kode']);
        $this->db->execute();

        $this->db->query("UPDATE detail_ruangan SET kode_ruangan = :kode WHERE kode_ruangan = :old_kode");
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('old_kode', $data['old_kode']);
        $this->db->execute();

        $queryDeleteDR = "DELETE FROM detail_ruangan WHERE kode_ruangan = :kode_ruangan";
        $this->db->query($queryDeleteDR);
        $this->db->bind('kode_ruangan', $data['kode']);
        $this->db->execute();

        foreach ($data['fasilitas_checked'] as $fasilitas_checked) {
            $queryDR = "INSERT INTO detail_ruangan (kode_ruangan, id_fasilitas) VALUES (:kode_ruangan, :id_fasilitas)";
            $this->db->query($queryDR);
            $this->db->bind('kode_ruangan', $data['kode']);
            $this->db->bind('id_fasilitas', $fasilitas_checked);
            $this->db->execute();
        }
        return $this->db->rowCount();
    }
}
