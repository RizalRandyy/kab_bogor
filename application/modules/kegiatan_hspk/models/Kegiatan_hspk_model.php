<?php
class Kegiatan_hspk_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_kegiatan.id, tb_kegiatan.idKegiatan, tb_kegiatan.idBidangTeknis, tb_kegiatan.UraianKegiatan, tb_kegiatan.satuan, tb_bidang_teknis.namaBidangTeknis');
        $this->db->from('tb_kegiatan');
        $this->db->join('tb_bidang_teknis', 'tb_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;

        $this->db->order_by('tb_kegiatan.idKegiatan', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get()->result_array();
        $get_count = $tot->get()->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);
            }
        }

        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getBidangTeknis()
    {
        $get_data = $this->db->select('id,idOpd,idBidangTeknis,namaBidangTeknis')
            ->order_by('idBidangTeknis')->get('tb_bidang_teknis')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                ->where('id !=', $id)
                ->where('idKegiatan', $params['idKegiatan'])
                ->get('tb_kegiatan')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Kegiatan Gagal! Kode Kegiatan ' . $params['idKegiatan'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_kegiatan', $params)) {
                return [
                    'message' => 'Edit Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $get_data = $this->db->select('*')
                ->where('idKegiatan', $params['idKegiatan'])
                ->get('tb_kegiatan')->first_row();

            // if ($cek) {
            //     return [
            //         'message' => 'Tambah Kegiatan Gagal! Kode Kegiatan ' . $params['idKegiatan'] . ' sudah ada!',
            //         'status' => 500,
            //     ];
            // }

            if ($this->db->insert('tb_kegiatan', $params)) {
                return [
                    'message' => 'Tambah Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);

        $this->db->select('tb_kegiatan.id, tb_kegiatan.idKegiatan, tb_kegiatan.idBidangTeknis, tb_bidang_teknis.namaBidangTeknis, tb_kegiatan.UraianKegiatan, tb_kegiatan.satuan');
        $this->db->from('tb_kegiatan');
        $this->db->join('tb_bidang_teknis', 'tb_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left');
        $this->db->where('tb_kegiatan.id', $id);

        $data = $this->db->get()->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
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

        $kegiatan = $this->db->get_where('tb_kegiatan', ['id' => $id])->row();

        if (!$kegiatan) {
            return [
                'message' => 'Kegiatan tidak ditemukan.',
                'status' => 404
            ];
        }

        $idKegiatan = $kegiatan->idKegiatan;

        $usulanKegiatanList = $this->db->get_where('tb_usulan_kegiatan', ['idKegiatan' => $idKegiatan])->result();

        if (!empty($usulanKegiatanList)) {
            $idUsulanList = array_map(function ($item) {
                return $item->id;
            }, $usulanKegiatanList);

            $this->db->where_in('id', $idUsulanList);
            $this->db->where('status', 'disetujui');
            $this->db->update('tb_usulan_kegiatan', ['status' => 'usulan']);

            $this->db->where_in('id_thn_kegiatan', $idUsulanList);
            $this->db->update('tb_usulan_thn_pekerjaan_detail', ['status' => 'usulan']);
        }

        if ($this->db->delete('tb_kegiatan', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Kegiatan" => "idKegiatan", "Bidang" => "namaBidangTeknis", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan");
        return $header;
    }
}
