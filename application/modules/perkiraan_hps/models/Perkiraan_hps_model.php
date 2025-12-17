<?php
class Perkiraan_hps_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
            ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
            ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
            ->where('id_thn_harga !=', '[]')
            ->where('id_thn_harga is not null', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == 'UraianKegiatan') {
                        $this->db->like('tb_kegiatan.UraianKegiatan', $value);
                    } elseif ($key == 'satuan') {
                        $this->db->like('tb_kegiatan.satuan', $value);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_thn_pekerjaan_detail')->result_array();
        $get_count = $tot->get('tb_thn_pekerjaan_detail')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                $id_harga = json_decode($value['id_thn_harga']);
                $total_item = json_decode($value['total_item']);
                $total_satuan = [];

                foreach ($id_harga as $ky => $val) {
                    $data_harga = $this->db->query("SELECT SUM(harga * " . $total_item[$ky] . ") as total FROM tb_thn_harga WHERE id = '" . $val . "'")->first_row();
                    $total_satuan[] = $data_harga->total;
                }
                $total = array_sum($total_satuan);

                $get_data[$key]['harga'] = 'Rp.' . number_format($total, 0, '', '.');
                unset($get_data[$key]['id_thn_harga']);
                unset($get_data[$key]['total_item']);
            }
        }

        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkegiatan()
    {
        $kegiatan = $this->db->select('tk.id,kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan')
            ->from("tb_thn_kegiatan tk")
            ->join("tb_kegiatan k", "tk.idKegiatan = k.id")
            ->order_by('kodeKelompok')
            ->order_by('tahunPekerjaan', 'DESC')
            ->get()->result_array();

        $kel_spesifikasi = $this->db->select("
                k.id AS id_kelompok,
                k.idKelItem AS kodeKelItem,
                k.UraianKelompok,
                k.tipe,
                k.kriteria,
                MAX(h.harga) AS value_harga
            ")
            ->from("tb_kelompok_item k")
            ->join("tb_jenis_item j", "j.idKelompokItem = k.id", "left")
            ->join("tb_spesifikasi_item s", "s.idJenisItem = j.id", "left")
            ->join("tb_thn_harga h", "h.idSpesifikasi = s.id", "left")
            ->group_by("k.id")
            ->order_by("k.tipe ASC")
            ->order_by("k.kriteria ASC")
            ->order_by("k.idKelItem ASC")
            ->get()
            ->result_array();



        // Format harga
        foreach ($kel_spesifikasi as &$row) {
            $row['harga'] = $row['value_harga'] ? 'Rp.' . number_format($row['value_harga'], 0, '', '.') : '-';
        }

        return [
            'kegiatan' => $kegiatan ?: [],
            'kel_spesifikasi' => $kel_spesifikasi ?: []
        ];
    }

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            if ($this->db->where('id', $id)->update('tb_thn_pekerjaan_detail', $params)) {
                return [
                    'message' => 'Edit Pekerjaan Detail Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            if ($this->db->insert('tb_thn_pekerjaan_detail', $params)) {
                return [
                    'message' => 'Tambah Pekerjaan Detail Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Pekerjaan Detail Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getHargaById($id)
    {
        return $this->db->select("
            tb_thn_harga.id,
            tb_spesifikasi_item.kodeKelompok,
            tb_spesifikasi_item.UraianSpesifikasi,
            tb_spesifikasi_item.satuan,
            tb_jenis_item.NamaJenis,
            tb_kelompok_item.UraianKelompok,
            tb_kelompok_item.tipe,
            tb_thn_harga.TahunHarga,
            tb_thn_harga.harga
        ")
            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
            ->where("tb_thn_harga.id", $id)
            ->get("tb_thn_harga")->row();
    }


    public function getDetailByKegiatan($id)
    {
        $row = $this->db->select('id_thn_harga,total_item,tb_thn_kegiatan.kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan')
            ->join('tb_thn_kegiatan', 'tb_thn_kegiatan.id = tb_thn_pekerjaan_detail.id_thn_kegiatan')
            ->join('tb_kegiatan', 'tb_kegiatan.id = tb_thn_kegiatan.idKegiatan')
            ->where('tb_thn_pekerjaan_detail.id_thn_kegiatan', $id)
            ->get('tb_thn_pekerjaan_detail')
            ->row();

        return [
            'detail_ids'   => json_decode($row->id_thn_harga),
            'total_item'   => json_decode($row->total_item),
            'kegiatan_text' => $row->kodeKelompok . ' - ' . $row->UraianKegiatan . ' - (' . $row->satuan . ') - ' . $row->tahunPekerjaan
        ];
    }

    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_standar_biaya_thn_detail', ['id' => $id])) {
            $cek = $this->db->select('id,id_thn_pekerjaan_detail')
                ->like('id_thn_pekerjaan_detail', '"' . $id . '"')
                ->get('tb_standar_biaya_thn_detail')
                ->result_array();

            if ($cek) {
                foreach ($cek as $key => $value) {
                    $id_thn_pekerjaan_detail = json_decode($value['id_thn_pekerjaan_detail']);
                    $thn_harga = [];
                    $banyak_item = [];

                    foreach ($id_thn_pekerjaan_detail as $ky => $val) {
                        if ($val != $id) {
                            $thn_harga[] = $val;
                        }
                    }
                    $data[] = [
                        'id' => $value['id'],
                        'id_thn_pekerjaan_detail' => json_encode($thn_harga)
                    ];
                }

                $this->db->update_batch('tb_standar_biaya_thn_detail', $data, 'id');
            }

            return [
                'message' => 'Delete success',
                'status' => 200,
            ];
        }

        return [
            'message' => 'Delete failed, please refresh page!',
            'status' => 400
        ];
    }

    public function getheader()
    {
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan",);
        return $header;
    }
}
