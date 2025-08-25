<?php
class Jenis_dokumen_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('id,jenis_dokumen');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('jenis_dokumen', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_jenis_dokumen')->result_array();
        $get_count = $tot->get('tb_jenis_dokumen')->num_rows();

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

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                ->where('id !=', $id)
                ->where('id', $id)
                ->get('tb_jenis_dokumen')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Jenis Dokumen Gagal! Id Jenis Dokumen ' . $params['id'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_jenis_dokumen', $params)) {
                return [
                    'message' => 'Edit Jenis Dokumen Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Jenis Dokumen Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            if ($this->db->insert('tb_jenis_dokumen', $params)) {
                return [
                    'message' => 'Tambah Jenis Dokumen Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Jenis Dokumen Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,jenis_dokumen')
            ->where('id', $id);

        $data =  $this->db->get('tb_jenis_dokumen')->row();

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
        if ($this->db->delete('tb_jenis_dokumen', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Jenis Dokumen" => "jenis_dokumen");
        return $header;
    }
}
