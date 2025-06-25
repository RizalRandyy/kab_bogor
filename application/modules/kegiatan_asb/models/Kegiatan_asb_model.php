<?php
class Kegiatan_asb_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('id,idASB,UraianKegiatan,satuan');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    $this->db->like($key, $value);
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('idASB ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_standar_biaya')->result_array();
        $get_count = $tot->get('tb_standar_biaya')->num_rows();

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
                            ->where('idASB', $params['idASB'])
                            ->get('tb_standar_biaya')->first_row();

            if($cek){
                return [
                    'message' => 'Edit Kegiatan Gagal! Kode Kegiatan '.$params['idASB'].' sudah ada!',
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

            if($cek){
                return [
                    'message' => 'Tambah Kegiatan Gagal! Kode Kegiatan '.$params['idASB'].' sudah ada!',
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

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idASB,UraianKegiatan,satuan')
            ->where('id', $id);
            
        $data =  $this->db->get('tb_standar_biaya')->row();

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

    public function getheader(){
        $header  = array("No" => 'reset', "Kode Kegiatan" => "idASB","Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan");  
        return $header;
    }
}
