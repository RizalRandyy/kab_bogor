<?php
class Tahun_kegiatan_hspk_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
            tb_thn_kegiatan.id,
            tahunPekerjaan,
            kodeKelompok,
            tb_kegiatan.UraianKegiatan as UraianKegiatan,
            tb_kegiatan.idBidangTeknis as idBidangTeknis,
            tb_bidang_teknis.namaBidangTeknis as namaBidangTeknis,
            tb_kegiatan.satuan as satuan
        ')
            ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
            ->join('tb_bidang_teknis', 'tb_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == 'UraianKegiatan') {
                        $this->db->like('tb_kegiatan.UraianKegiatan', $value);
                    } elseif ($key == 'satuan') {
                        $this->db->like('tb_kegiatan.satuan', $value);
                    } elseif ($key == 'namaBidangTeknis') {
                        $this->db->like('tb_bidang_teknis.namaBidangTeknis', $value);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('tahunPekerjaan DESC')->order_by('tb_thn_kegiatan.idKegiatan ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_thn_kegiatan')->result_array();
        $get_count = $tot->get('tb_thn_kegiatan')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                // ===============================
                // HITUNG HARGA HSPK
                // ===============================
                $detail = $this->db
                    ->select('id_thn_harga, total_item')
                    ->where('id_thn_kegiatan', $value['id'])
                    ->where('id_thn_harga !=', '[]')
                    ->where('id_thn_harga is not null', null)
                    ->get('tb_thn_pekerjaan_detail')
                    ->result_array();

                if (empty($detail)) {
                    // Jika belum ada harga
                    $get_data[$key]['harga'] = '-';
                } else {
                    $grand_total = 0;

                    foreach ($detail as $d) {
                        $id_harga = json_decode($d['id_thn_harga']);
                        $total_item = json_decode($d['total_item']);

                        if (is_array($id_harga)) {
                            foreach ($id_harga as $ky => $val) {
                                $harga = $this->db
                                    ->select('harga')
                                    ->where('id', $val)
                                    ->get('tb_thn_harga')
                                    ->row();

                                if ($harga) {
                                    $grand_total += ($harga->harga * ($total_item[$ky] ?? 0));
                                }
                            }
                        }
                    }

                    $get_data[$key]['harga'] = $grand_total > 0
                        ? 'Rp.' . number_format($grand_total, 0, '', '.')
                        : '-';
                }
            }
        }
        // var_dump($get_data); die;

        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkegiatan()
    {
        $get_data = $this->db->select('
            tb_kegiatan.id,
            idKegiatan,
            tb_kegiatan.idBidangTeknis,
            tb_bidang_teknis.namaBidangTeknis,
            UraianKegiatan,
            satuan
        ')
            ->from('tb_kegiatan')
            ->join('tb_bidang_teknis', 'tb_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left')
            ->order_by('idKegiatan')
            ->get()
            ->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $kelompok_item = $this->db->where('id', $params['idKegiatan'])
            ->get('tb_kegiatan')->first_row();

        $kodeKelompok = $kelompok_item->idKegiatan;

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                ->where('tb_thn_kegiatan.id !=', $id)
                ->where('tb_thn_kegiatan.idKegiatan', $params['idKegiatan'])
                ->where('tahunPekerjaan', $params['tahunPekerjaan'])
                ->get('tb_thn_kegiatan')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if ($cek) {
                return [
                    'message' => 'Edit Tahun Kegiatan Gagal! Kode Kegiatan ' . $kodeKelompok . ' & Tahun ' . $params['tahunPekerjaan'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_thn_kegiatan', $params)) {
                return [
                    'message' => 'Edit Tahun Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            $cek = $this->db->select('*')
                ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                ->where('tb_thn_kegiatan.idKegiatan', $params['idKegiatan'])
                ->where('tahunPekerjaan', $params['tahunPekerjaan'])
                ->get('tb_thn_kegiatan')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if ($cek) {
                return [
                    'message' => 'Tambah Tahun Kegiatan Gagal! Kode Kegiatan ' . $kodeKelompok . ' & Tahun ' . $params['tahunPekerjaan'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_thn_kegiatan', $params)) {
                return [
                    'message' => 'Tambah Tahun Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idKegiatan,tahunPekerjaan,perbub_nomor')
            ->where('id', $id);

        $data =  $this->db->get('tb_thn_kegiatan')->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function getPerbub($tahun)
    {
        $row = $this->db->select('nomor_dokumen')
            ->from('tb_detail_dokumen')
            ->where('tahun', $tahun)
            ->where("id_jenis_dokumen", 3)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($row) {
            return [
                'status' => 200,
                'nomor_dokumen' => $row->nomor_dokumen
            ];
        } else {
            return [
                'status' => 404,
                'nomor_dokumen' => null
            ];
        }
    }

    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_thn_kegiatan', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Bidang" => "namaBidangTeknis", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan");
        return $header;
    }
}
