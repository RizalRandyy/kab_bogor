<?php
class Spesifikasi_harga_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_thn_harga.id,tb_thn_harga.kodeKelompok,TahunHarga,harga,tb_spesifikasi_item.UraianSpesifikasi,tb_spesifikasi_item.satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.UraianKelompok,tb_kelompok_item.tipe')
                ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
                ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id");

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    if($key == "UraianKelompok"){
                        $this->db->like('tb_kelompok_item.UraianKelompok', $value);
                    }elseif($key == "NamaJenis"){
                        $this->db->like('tb_jenis_item.NamaJenis', $value);
                    }elseif($key == "UraianSpesifikasi"){
                        $this->db->like('tb_spesifikasi_item.UraianSpesifikasi', $value);
                    }elseif($key == "satuan"){
                        $this->db->like('tb_spesifikasi_item.satuan', $value);
                    }elseif($key == "kodeKelompok"){
                        $this->db->like('tb_thn_harga.kodeKelompok', $value);
                    }else{
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('tb_thn_harga.TahunHarga DESC, tb_thn_harga.kodeKelompok ASC')->group_by('tb_thn_harga.id');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_thn_harga')->result_array();
        $get_count = $tot->get('tb_thn_harga')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);
                $get_data[$key]['kelompok_item'] = $value['kodeKelompok'];
                $get_data[$key]['harga'] = 'Rp.'.number_format($value['harga'], 0, '', '.');
            }
        }
        
        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkel_spesifikasi()
    {
        $get_data = $this->db->select("tb_spesifikasi_item.id,tb_spesifikasi_item.kodeKelompok,idSpesifikasi,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->order_by('tb_spesifikasi_item.kodeKelompok')->get('tb_spesifikasi_item')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $spesifikasi_item = $this->db->where('id', $params['idSpesifikasi'])
                                    ->get('tb_spesifikasi_item')->first_row();

        $kodeKelompok = $spesifikasi_item->kodeKelompok;

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                            ->where('id !=', $id)
                            ->where('idSpesifikasi', $params['idSpesifikasi'])
                            ->where('TahunHarga', $params['TahunHarga'])
                            ->get('tb_thn_harga')->first_row();

            if($cek){
                return [
                    'message' => 'Edit Harga Gagal! Kode Item '.$kodeKelompok.' & Tahun '.$params['TahunHarga'].' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->where('id', $id)->update('tb_thn_harga', $params)) {
                return [
                    'message' => 'Edit Jenis Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Harga Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $get_data = $this->db->select('*')
                            ->where('idSpesifikasi', $params['idSpesifikasi'])
                            ->where('TahunHarga', $params['TahunHarga'])
                            ->get('tb_thn_harga')->first_row();

            if($cek){
                return [
                    'message' => 'Tambah Harga Gagal! Kode Item '.$kodeKelompok.' & Tahun '.$params['TahunHarga'].' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->insert('tb_thn_harga', $params)) {
                return [
                    'message' => 'Tambah Harga Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Harga Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idSpesifikasi,kodeKelompok,TahunHarga,harga')
            ->where('id', $id);

        $data =  $this->db->get('tb_thn_harga')->row();

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
        
        if ($this->db->delete('tb_thn_harga', ['id' => $id])) {

            $cek = $this->db->select('id,id_thn_harga, total_item')
                            ->like('id_thn_harga', '"'.$id.'"')
                            ->get('tb_thn_pekerjaan_detail')
                            ->result_array();

            if($cek){
                foreach ($cek as $key => $value) {
                    $id_thn_harga = json_decode($value['id_thn_harga']);
                    $total_item = json_decode($value['total_item']);
                    $thn_harga = [];
                    $banyak_item = [];

                    foreach ($id_thn_harga as $ky => $val) {
                        if($val != $id){
                            $thn_harga[] = $val;
                            $banyak_item[] = $total_item[$ky];
                        }
                    }
                    $data[] = ['id' => $value['id'],
                            'id_thn_harga' => json_encode($thn_harga),
                            'total_item' => json_encode($banyak_item)
                        ];
                }

                $this->db->update_batch('tb_thn_pekerjaan_detail', $data, 'id');

            }

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

    public function cek($where, $table = '')
    {
        $data =  $this->db->where($where)->get($table)->first_row();

        return $data;
    }

    public function insert_import($params, $table)
    {
        if ($this->db->insert($table, $params)){
            $id = $this->db->insert_id();
        }else{
            $id = '';
        }

        return $id;
    }

    public function getheader(){
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok","Nama Kelompok" => "UraianKelompok", "Nama Item" => "NamaJenis", "Spesifikasi Item" => "UraianSpesifikasi", "Satuan" => "satuan", "Tahun" => "TahunHarga", "Harga" => "harga");  
        return $header;
    }
}
