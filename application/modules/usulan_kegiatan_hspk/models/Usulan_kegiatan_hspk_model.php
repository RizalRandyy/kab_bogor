<?php
class Usulan_kegiatan_hspk_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
        tb_usulan_kegiatan.id,
        tb_usulan_kegiatan.idKegiatan,
        tb_usulan_kegiatan.idBidangTeknis,
        tb_usulan_kegiatan.UraianKegiatan,
        tb_usulan_kegiatan.satuan,
        tb_usulan_kegiatan.tahunPekerjaan,
        tb_usulan_kegiatan.idOpd,
        tb_bidang_teknis.namaBidangTeknis,
        tb_opd.namaOpd
    ');
        $this->db->from('tb_usulan_kegiatan');
        $this->db->join('tb_bidang_teknis', 'tb_usulan_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left');
        $this->db->join('tb_opd', 'tb_usulan_kegiatan.idOpd = tb_opd.idOpd', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;

        $this->db->order_by('tb_usulan_kegiatan.idKegiatan', 'ASC');
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

    public function getOpd()
    {
        $get_data = $this->db->select('id,idOpd,namaOpd')
            ->order_by('idOpd')->get('tb_opd')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $cek_kegiatan_tetap = $this->db->where('idKegiatan', $params['idKegiatan'])
            ->get('tb_kegiatan')->first_row();

        if ($cek_kegiatan_tetap) {
            return [
                'message' => 'Gagal! Kode Kegiatan ' . $params['idKegiatan'] . ' sudah menjadi kegiatan tetap dan tidak bisa diubah/usulkan kembali!',
                'status' => 500,
            ];
        }

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                ->where('id !=', $id)
                ->where('idKegiatan', $params['idKegiatan'])
                ->get('tb_usulan_kegiatan')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Kegiatan Gagal! Kode Kegiatan ' . $params['idKegiatan'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_usulan_kegiatan', $params)) {
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
            $cek = $this->db->select('*')
                ->where('idKegiatan', $params['idKegiatan'])
                ->get('tb_usulan_kegiatan')->first_row();

            if ($cek) {
                return [
                    'message' => 'Tambah Kegiatan Gagal! Kode Kegiatan ' . $params['idKegiatan'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_usulan_kegiatan', $params)) {
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

        $this->db->select('
        tb_usulan_kegiatan.id, 
        tb_usulan_kegiatan.idKegiatan, 
        tb_usulan_kegiatan.idBidangTeknis, 
        tb_bidang_teknis.namaBidangTeknis, 
        tb_usulan_kegiatan.UraianKegiatan, 
        tb_usulan_kegiatan.satuan,
        tb_usulan_kegiatan.tahunPekerjaan,
        tb_usulan_kegiatan.idOpd,
        tb_opd.namaOpd
    ');
        $this->db->from('tb_usulan_kegiatan');
        $this->db->join('tb_bidang_teknis', 'tb_usulan_kegiatan.idBidangTeknis = tb_bidang_teknis.idBidangTeknis', 'left');
        $this->db->join('tb_opd', 'tb_usulan_kegiatan.idOpd = tb_opd.idOpd', 'left');
        $this->db->where('tb_usulan_kegiatan.id', $id);

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
        if ($this->db->delete('tb_usulan_kegiatan', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Kegiatan" => "idKegiatan", "Bidang" => "namaBidangTeknis", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "TahunPekerjaan" => "tahunPekerjaan", "OPD / Dinas Pengusul" => "namaOpd");
        return $header;
    }
}
