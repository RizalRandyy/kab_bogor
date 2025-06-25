<?php
class Kelompok_item_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('id,idKelItem,UraianKelompok,tipe');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    $this->db->like($key, $value);
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('idKelItem', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_kelompok_item')->result_array();
        $get_count = $tot->get('tb_kelompok_item')->num_rows();

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
                            ->where('idKelItem', $params['idKelItem'])
                            ->get('tb_kelompok_item')->first_row();

            if($cek){
                return [
                    'message' => 'Edit Kelompok Item Gagal! Kode Item '.$params['idKelItem'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_kelompok_item', $params)) {
                return [
                    'message' => 'Edit Kelompok Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Kelompok Item Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $get_data = $this->db->select('*')
                            ->where('idKelItem', $params['idKelItem'])
                            ->get('tb_kelompok_item')->first_row();

            if($cek){
                return [
                    'message' => 'Tambah Kelompok Item Gagal! Kode Item '.$params['idKelItem'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_kelompok_item', $params)) {
                return [
                    'message' => 'Tambah Kelompok Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Kelompok Item Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idKelItem,UraianKelompok,tipe')
            ->where('id', $id);

        $data =  $this->db->get('tb_kelompok_item')->row();

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
        if ($this->db->delete('tb_kelompok_item', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "idKelItem", "Uraian Kelompok" => "UraianKelompok", "Tipe" => "tipe");  
        return $header;
    }

}
