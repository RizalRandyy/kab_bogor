<?php
class Lokasi_toko_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('id,nama_toko,tautan');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    $this->db->like($key, $value);
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('nama_toko', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_lokasi')->result_array();
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

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,nama_toko,tautan')
            ->where('id', $id);

        $data =  $this->db->get('tb_lokasi')->row();

        if($data){
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

    public function getheader(){
        $header  = array("No" => 'reset', "Nama Toko" => "nama_toko");  
        return $header;
    }

}
