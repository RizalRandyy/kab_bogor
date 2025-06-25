<?php
class Spesifikasi_item_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_spesifikasi_item.id,tb_spesifikasi_item.kodeKelompok,UraianSpesifikasi,satuan,tb_kelompok_item.UraianKelompok,tb_jenis_item.NamaJenis')
                ->join('tb_jenis_item', 'tb_spesifikasi_item.idJenisItem = tb_jenis_item.id')
                ->join('tb_kelompok_item', 'tb_jenis_item.idKelompokItem = tb_kelompok_item.id');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    if($key == "kodeKelompok"){
                        $this->db->like('tb_spesifikasi_item.kodeKelompok', $value);
                    }elseif($key == "UraianKelompok"){
                        $this->db->like('tb_kelompok_item.UraianKelompok', $value);
                    }elseif($key == "NamaJenis"){
                        $this->db->like('tb_jenis_item.NamaJenis', $value);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('id', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_spesifikasi_item')->result_array();
        $get_count = $tot->get('tb_spesifikasi_item')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);
                $get_data[$key]['idKelompok'] = $value['kodeKelompok'];
            }
        }
        
        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkel_item()
    {
        $get_data = $this->db->select('tb_jenis_item.id,tb_kelompok_item.IdKelItem,tb_kelompok_item.UraianKelompok,idJenisBarang,NamaJenis,tipe')
                            ->join('tb_kelompok_item', 'tb_jenis_item.idKelompokItem = tb_kelompok_item.id')
                            ->order_by('idJenisBarang')->get('tb_jenis_item')->result_array();

        foreach ($get_data as $key => $value) {
            $get_data[$key]['IdKelItem'] = $value['IdKelItem'].'.'.$value['idJenisBarang'];

        }

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $jenis_item = $this->db->where('id', $params['idJenisItem'])
                                    ->get('tb_jenis_item')->first_row();

        $kodeKelompok = $jenis_item->kodeKelompok.'.'.$params['idSpesifikasi'];

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                            ->where('tb_spesifikasi_item.id !=', $id)
                            ->where('kodeKelompok', $kodeKelompok)
                            ->get('tb_spesifikasi_item')->first_row();

            if($cek){
                return [
                    'message' => 'Edit SSH Gagal! Kode Item '.$kodeKelompok.' & Kode Spesifikasi '.$params['idSpesifikasi'].' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->where('id', $id)->update('tb_spesifikasi_item', $params)) {
                return [
                    'message' => 'Edit Jenis Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit SSH Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $this->db->select('*')
                            ->where('kodeKelompok', $kodeKelompok)
                            ->get('tb_spesifikasi_item')->first_row();

            if($cek){
                return [
                    'message' => 'Tambah SSH Gagal! Kode Item '.$jenis_item->kodeKelompok.' & Kode Spesifikasi '.$params['idSpesifikasi'].' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->insert('tb_spesifikasi_item', $params)) {
                return [
                    'message' => 'Tambah SSH Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah SSH Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idJenisItem,idSpesifikasi,UraianSpesifikasi,satuan')
            ->where('id', $id);

        $data =  $this->db->get('tb_spesifikasi_item')->row();

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
        if ($this->db->delete('tb_spesifikasi_item', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok","Nama Kelompok" => "UraianKelompok", "Nama Jenis" => "NamaJenis", "Usaraian Spesifikasi" => "UraianSpesifikasi", "Satuan" => "satuan");  
        return $header;
    }
}
