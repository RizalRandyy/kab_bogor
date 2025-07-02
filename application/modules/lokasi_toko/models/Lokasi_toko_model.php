<?php
class Lokasi_toko_model extends CI_Model
{
    public function getData($params, $users)
    {
        // ✅ Tambahkan pengecekan default
        $limit  = isset($params['limit']) ? $params['limit'] : 10;
        $offset = isset($params['offset']) ? $params['offset'] : 1;
        $keyword = isset($params['keyword']) ? (array)json_decode($params['keyword']) : [];

        $start = ($offset - 1) * $limit;

        $this->db->select('id,nama_toko,tautan,koordinat');

        if (!empty($keyword)) {
            foreach ($keyword as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('nama_toko', 'ASC');
        $get_data = $this->db->limit($limit, $start)->get('tb_lokasi')->result_array();
        $get_count = $tot->get('tb_lokasi')->num_rows();

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

    public function getLocationAll()
    {
        $this->db->select('id, nama_toko, tautan, koordinat');
        $this->db->order_by('nama_toko', 'ASC');
        $data = $this->db->get('tb_lokasi')->result_array();

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $data[$key]['id'] = encrypt_url($value['id']);
            }
        }

        return [
            'status' => !empty($data) ? 200 : 500,
            'message' => !empty($data) ? null : 'Data Tidak Ada!',
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            if ($this->db->where('id', $id)->update('tb_lokasi', $params)) {
                return [
                    'message' => 'Edit Lokasi Toko Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Lokasi Toko Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            if ($this->db->insert('tb_lokasi', $params)) {
                return [
                    'message' => 'Tambah Lokasi Toko Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Lokasi Toko Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,nama_toko,tautan')
            ->where('id', $id);

        $data =  $this->db->get('tb_lokasi')->row();

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
        if ($this->db->delete('tb_lokasi', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Nama Toko" => "nama_toko");
        return $header;
    }
}
