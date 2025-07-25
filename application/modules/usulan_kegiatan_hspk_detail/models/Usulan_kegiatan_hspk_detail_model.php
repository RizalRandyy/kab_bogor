<?php
class Usulan_kegiatan_hspk_detail_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
        tb_usulan_thn_pekerjaan_detail.id,
        tb_usulan_kegiatan.idKegiatan,
        tb_usulan_kegiatan.UraianKegiatan,
        tb_usulan_kegiatan.satuan,
        tb_usulan_kegiatan.tahunPekerjaan,
        tb_usulan_kegiatan.idOpd,
        tb_usulan_kegiatan.status,
        tb_opd.namaOpd,
        tb_usulan_kegiatan.idBidangTeknis,
        tb_bidang_teknis.namaBidangTeknis,
        tb_usulan_thn_pekerjaan_detail.id_thn_harga,
        tb_usulan_thn_pekerjaan_detail.total_item
    ')
            ->from('tb_usulan_thn_pekerjaan_detail')
            ->join('tb_usulan_kegiatan', 'tb_usulan_thn_pekerjaan_detail.id_thn_kegiatan = tb_usulan_kegiatan.id')
            ->join('tb_bidang_teknis', 'tb_usulan_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left')
            ->join('tb_opd', 'tb_usulan_kegiatan.idOpd = tb_opd.idOpd', 'left')
            ->where('tb_usulan_thn_pekerjaan_detail.id_thn_harga !=', '[]')
            ->where('tb_usulan_thn_pekerjaan_detail.id_thn_harga is not null', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == 'UraianKegiatan') {
                        $this->db->like('tb_usulan_kegiatan.UraianKegiatan', $value);
                    } elseif ($key == 'satuan') {
                        $this->db->like('tb_usulan_kegiatan.satuan', $value);
                    } elseif ($key == 'namaBidangTeknis') {
                        $this->db->like('tb_bidang_teknis.namaBidangTeknis', $value);
                    } elseif ($key == 'namaOpd') {
                        $this->db->like('tb_opd.namaOpd', $value);
                    } elseif ($key == 'tahunPekerjaan') {
                        $this->db->like('tb_usulan_kegiatan.tahunPekerjaan', $value);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('tb_usulan_kegiatan.idKegiatan ASC, tb_usulan_kegiatan.tahunPekerjaan DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get()->result_array();
        $get_count = $tot->get()->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                $id_harga = json_decode($value['id_thn_harga']);
                $total_item = json_decode($value['total_item']);
                $total_satuan = [];

                foreach ($id_harga as $ky => $val) {
                    $data_harga = $this->db->query("
                    SELECT SUM(harga * " . $total_item[$ky] . ") as total 
                    FROM tb_thn_harga 
                    WHERE id = '" . $val . "'
                ")->first_row();
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
        $kegiatan = $this->db->select('
    tb_usulan_kegiatan.id,
    tb_usulan_kegiatan.idKegiatan,
    tb_usulan_kegiatan.UraianKegiatan,
    tb_usulan_kegiatan.satuan,
    tb_usulan_kegiatan.tahunPekerjaan,
    tb_usulan_kegiatan.idBidangTeknis,
    tb_bidang_teknis.namaBidangTeknis,
    tb_usulan_kegiatan.idOpd,
    tb_opd.namaOpd
')
            ->from('tb_usulan_kegiatan')
            ->join('tb_bidang_teknis', 'tb_usulan_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left')
            ->join('tb_opd', 'tb_usulan_kegiatan.idOpd = tb_opd.idOpd', 'left')
            ->order_by('tb_usulan_kegiatan.idKegiatan', 'ASC')
            ->get()
            ->result_array();

        $kel_spesifikasi = $this->db->select("
            tb_thn_harga.id,
            tb_spesifikasi_item.kodeKelompok,
            TahunHarga,
            harga,
            UraianSpesifikasi,
            satuan,
            tb_jenis_item.NamaJenis,
            tb_kelompok_item.tipe,
            tb_kelompok_item.UraianKelompok
        ")
            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
            ->order_by('tb_spesifikasi_item.kodeKelompok')
            ->get('tb_thn_harga')
            ->result_array();

        if (!empty($kel_spesifikasi)) {
            foreach ($kel_spesifikasi as $key => $value) {
                $kel_spesifikasi[$key]['harga'] = 'Rp.' . number_format($value['harga'], 0, '', '.');
                $kel_spesifikasi[$key]['value_harga'] = $value['harga'];
            }
        }

        return [
            'kegiatan' => !empty($kegiatan) ? $kegiatan : [],
            'kel_spesifikasi' => !empty($kel_spesifikasi) ? $kel_spesifikasi : []
        ];
    }

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            if ($this->db->where('id', $id)->update('tb_usulan_thn_pekerjaan_detail', $params)) {
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

            if ($this->db->insert('tb_usulan_thn_pekerjaan_detail', $params)) {
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

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,id_thn_kegiatan,total_item,id_thn_harga')
            ->where('id', $id);

        $data =  $this->db->get('tb_usulan_thn_pekerjaan_detail')->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
            $data->id_thn_harga = json_decode($data->id_thn_harga);
            $total_item = json_decode($data->total_item);

            foreach ($data->id_thn_harga as $key => $value) {
                $total[$value] = $total_item[$key];
            }
            $data->total_item = $total;
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }


    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_usulan_thn_pekerjaan_detail', ['id' => $id])) { // CEK LAGI NANTI
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

    public function setujuiUsulan($id, $user)
    {
        $id = decrypt_url($id);

        if (!$id || !$user) {
            return ['status' => false, 'message' => 'Kode kegiatan atau user tidak valid'];
        }

        $this->db->select('d.*, k.id, k.idKegiatan, k.idBidangTeknis, k.UraianKegiatan, k.satuan, k.tahunPekerjaan');
        $this->db->from('tb_usulan_thn_pekerjaan_detail d');
        $this->db->join('tb_usulan_kegiatan k', 'k.id = d.id_thn_kegiatan', 'left');
        $this->db->where('d.id', $id);
        $usulan = $this->db->get()->row();


        if (!$usulan) {
            return ['status' => false, 'message' => 'Data usulan tidak ditemukan'];
        }

        if ($usulan->status === 'disetujui') {
            return ['status' => false, 'message' => 'Usulan ini sudah disetujui sebelumnya'];
        }

        $this->db->select('tb_thn_pekerjaan_detail.*');
        $this->db->from('tb_thn_pekerjaan_detail');
        $this->db->join('tb_thn_kegiatan', 'tb_thn_kegiatan.id = tb_thn_pekerjaan_detail.id_thn_kegiatan');
        $this->db->where('tb_thn_kegiatan.idKegiatan', $usulan->idKegiatan);
        $kegiatanExists = $this->db->get()->row();

        if ($kegiatanExists) {
            return ['status' => false, 'message' => 'HSPK detail sudah tersedia di data HSPK'];
        }

        $newKegiatanId = $this->db->select_max('id')->get('tb_kegiatan')->row()->id + 1;
        $newThnKegiatanId = $this->db->select_max('id')->get('tb_thn_kegiatan')->row()->id + 1;
        $newThnKegiatanDetailId = $this->db->select_max('id')->get('tb_thn_pekerjaan_detail')->row()->id + 1;

        $now = date('Y-m-d H:i:s');

        $kegiatanData = [
            'id'               => $newKegiatanId,
            'idKegiatan'       => $usulan->idKegiatan,
            'idBidangTeknis'   => $usulan->idBidangTeknis,
            'UraianKegiatan'   => $usulan->UraianKegiatan,
            'satuan'           => $usulan->satuan,
            'updated_by'       => $user['id'],
            'updated_at'       => $now,
        ];

        $thnKegiatanData = [
            'id'            => $newThnKegiatanId,
            'idKegiatan'    => $newKegiatanId,
            'tahunPekerjaan' => $usulan->tahunPekerjaan,
            'kodeKelompok'  => $usulan->idKegiatan,
            'updated_by'    => $user['id'],
            'updated_at'    => $now,
        ];

        $thnKegiatanDetailData = [
            'id'            => $newThnKegiatanDetailId,
            'id_thn_kegiatan' => $newThnKegiatanId,
            'id_thn_harga' => $usulan->id_thn_harga, // Cek
            'total_item'  => $usulan->total_item,  // Cek
            'updated_by'    => $user['id'],
            'updated_at'    => $now,
        ];

        $this->db->insert('tb_kegiatan', $kegiatanData);
        $this->db->insert('tb_thn_kegiatan', $thnKegiatanData);
        $this->db->insert('tb_thn_pekerjaan_detail', $thnKegiatanDetailData);
        $this->db->where('idKegiatan', $usulan->idKegiatan)
            ->update('tb_usulan_kegiatan', ['status' => 'disetujui']);
        $this->db->where('id_thn_kegiatan', $usulan->id)
            ->update('tb_usulan_thn_pekerjaan_detail', ['status' => 'disetujui']);

        if ($this->db->trans_status() === false) {
            return ['status' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'];
        }

        return ['status' => true, 'message' => 'Usulan berhasil disetujui.'];
    }

    public function getheader()
    {
        $header  = array("No" => 'reset', "Kode Item" => "idKegiatan", "Bidang" => "namaBidangTeknis", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan", "OPD / Dinas Pengusul" => "namaOpd");
        return $header;
    }
}
