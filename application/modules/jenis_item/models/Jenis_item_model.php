<?php
class Jenis_item_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_jenis_item.id,kodeKelompok,NamaJenis,tb_kelompok_item.UraianKelompok as NamaKelompok')
                ->join('tb_kelompok_item', 'tb_jenis_item.idKelompokItem = tb_kelompok_item.id');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    if($key == 'NamaKelompok'){
                        $this->db->like('tb_kelompok_item.UraianKelompok', $value);
                    } else{
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('tb_jenis_item.kodeKelompok', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_jenis_item')->result_array();
        $get_count = $tot->get('tb_jenis_item')->num_rows();

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

    public function getkel_item()
    {
        $get_data = $this->db->select('id,IdKelItem,UraianKelompok,tipe')
                            ->order_by('idKelItem')->get('tb_kelompok_item')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $kelompok_item = $this->db->where('id', $params['idKelompokItem'])
                                    ->get('tb_kelompok_item')->first_row();

        $kodeKelompok = $kelompok_item->IdKelItem.'.'.$params['idJenisBarang'];

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                            ->join('tb_kelompok_item','tb_jenis_item.idKelompokItem = tb_kelompok_item.id')
                            ->where('tb_jenis_item.id !=', $id)
                            ->where('idKelompokItem', $params['idKelompokItem'])
                            ->where('idJenisBarang', $params['idJenisBarang'])
                            ->get('tb_jenis_item')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if($cek){
                return [
                    'message' => 'Edit Jenis Item Gagal! Kode Jenis Barang '.$kodeKelompok.' - '.$params['idJenisBarang'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_jenis_item', $params)) {
                return [
                    'message' => 'Edit Jenis Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Jenis Item Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            $cek = $this->db->select('*')
                            ->join('tb_kelompok_item','tb_jenis_item.idKelompokItem = tb_kelompok_item.id')
                            ->where('idKelompokItem', $params['idKelompokItem'])
                            ->where('idJenisBarang', $params['idJenisBarang'])
                            ->get('tb_jenis_item')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if($cek){
                return [
                    'message' => 'Tambah Jenis Item Gagal! Kode Jenis Barang '.$kodeKelompok.' - '.$params['idJenisBarang'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_jenis_item', $params)) {
                return [
                    'message' => 'Tambah Jenis Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Jenis Item Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idKelompokItem,IdJenisBarang,NamaJenis')
            ->where('id', $id);

        $data =  $this->db->get('tb_jenis_item')->row();

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
        if ($this->db->delete('tb_jenis_item', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Nama Kelompok" => "NamaKelompok", "Nama Jenis" => "NamaJenis");  
        return $header;
    }
}
