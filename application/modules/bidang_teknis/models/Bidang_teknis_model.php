<?php
class Bidang_teknis_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_bidang_teknis.id, tb_bidang_teknis.idOpd, tb_bidang_teknis.idBidangTeknis, tb_bidang_teknis.namaBidangTeknis, tb_opd.namaOpd');
        $this->db->from('tb_bidang_teknis');
        $this->db->join('tb_opd', 'tb_bidang_teknis.idOpd = tb_opd.idOpd', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;

        $this->db->order_by('idBidangTeknis', 'ASC');
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
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                ->where('id !=', $id)
                ->where('idBidangTeknis', $params['idBidangTeknis'])
                ->get('tb_bidang_teknis')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Bidang Teknis Gagal! Kode Bidang Teknis ' . $params['idBidangTeknis'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_bidang_teknis', $params)) {
                return [
                    'message' => 'Edit Bidang Teknis Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Bidang Teknis Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $get_data = $this->db->select('*')
                ->where('idBidangTeknis', $params['idBidangTeknis'])
                ->get('tb_bidang_teknis')->first_row();

            if ($cek) {
                return [
                    'message' => 'Tambah Bidang Teknis Gagal! Kode Bidang Teknis ' . $params['idBidangTeknis'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_bidang_teknis', $params)) {
                return [
                    'message' => 'Tambah Bidang Teknis Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Bidang Teknis Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);

        $this->db->select('tb_bidang_teknis.id, tb_bidang_teknis.idOpd, tb_bidang_teknis.idBidangTeknis, tb_bidang_teknis.namaBidangTeknis, tb_opd.namaOpd');
        $this->db->from('tb_bidang_teknis');
        $this->db->join('tb_opd', 'tb_bidang_teknis.idOpd = tb_opd.idOpd', 'left');
        $this->db->where('tb_bidang_teknis.id', $id);

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
        if ($this->db->delete('tb_bidang_teknis', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "OPD" => "namaOpd", "Kode Bidang Teknis" => "idBidangTeknis", "Nama Bidang Teknis" => "namaBidangTeknis");
        return $header;
    }
}
