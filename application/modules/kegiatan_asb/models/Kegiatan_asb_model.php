<?php
class Kegiatan_asb_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_standar_biaya.id, idASB, tb_standar_biaya.idOpd, UraianKegiatan, satuan, tb_opd.namaOpd');
        $this->db->from('tb_standar_biaya');
        $this->db->join('tb_opd', 'tb_standar_biaya.idOpd = tb_opd.idOpd', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key === 'namaOpd') {
                        $this->db->like('tb_opd.namaOpd', $value);
                    } else {
                        $this->db->like('tb_standar_biaya.' . $key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;

        $this->db->order_by('idASB ASC');
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
                ->where('idASB', $params['idASB'])
                ->get('tb_standar_biaya')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Kegiatan Gagal! Kode Kegiatan ' . $params['idASB'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_standar_biaya', $params)) {
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
                ->where('idASB', $params['idASB'])
                ->get('tb_standar_biaya')->first_row();

            if ($cek) {
                return [
                    'message' => 'Tambah Kegiatan Gagal! Kode Kegiatan ' . $params['idASB'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_standar_biaya', $params)) {
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

        $this->db->select('tb_standar_biaya.id, idASB, tb_standar_biaya.idOpd, UraianKegiatan, satuan, tb_opd.namaOpd');
        $this->db->from('tb_standar_biaya');
        $this->db->join('tb_opd', 'tb_standar_biaya.idOpd = tb_opd.idOpd', 'left');
        $this->db->where('tb_standar_biaya.id', $id);

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

        $asb = $this->db->get_where('tb_standar_biaya', ['id' => $id])->row();

        if (!$asb) {
            return [
                'message' => 'ASB tidak ditemukan.',
                'status' => 404
            ];
        }

        $idAsb = $asb->idASB;

        $usulanAsbList = $this->db->get_where('tb_usulan_standar_biaya', ['idASB' => $idAsb])->result();

        if (!empty($usulanAsbList)) {
            $idUsulanList = array_map(function ($item) {
                return $item->id;
            }, $usulanAsbList);

            $this->db->where_in('id', $idUsulanList);
            $this->db->where('status', 'disetujui');
            $this->db->update('tb_usulan_standar_biaya', ['status' => 'usulan']);

            // $this->db->where_in('id_thn_kegiatan', $idUsulanList);
            // $this->db->update('tb_usulan_thn_pekerjaan_detail', ['status' => 'usulan']);
        }

        $this->db->delete('tb_standar_biaya_thn', ['idASB' => $id]);

        if ($this->db->delete('tb_standar_biaya', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Kegiatan" => "idASB", "Bidang" => "namaOpd", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan");
        return $header;
    }
}
