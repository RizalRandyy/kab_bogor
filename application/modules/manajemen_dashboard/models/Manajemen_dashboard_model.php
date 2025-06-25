<?php
class Manajemen_dashboard_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
                ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
                ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                ->where('id_thn_harga !=','[]')
                ->where('id_thn_harga is not null', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    if($key == 'UraianKegiatan'){
                        $this->db->like('tb_kegiatan.UraianKegiatan', $value);
                    } elseif($key == 'satuan'){
                        $this->db->like('tb_kegiatan.satuan', $value);
                    } else{
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_thn_pekerjaan_detail')->result_array();
        $get_count = $tot->get('tb_thn_pekerjaan_detail')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                $id_harga = json_decode($value['id_thn_harga']);
                $total_item = json_decode($value['total_item']);
                $total_satuan = [];

                foreach ($id_harga as $ky => $val) {
                    $data_harga = $this->db->query("SELECT SUM(harga * ".$total_item[$ky].") as total FROM tb_thn_harga WHERE id = '".$val."'")->first_row();
                    $total_satuan[] = $data_harga->total;
                }
                $total = array_sum($total_satuan);

                $get_data[$key]['harga'] = 'Rp.'.number_format($total, 0, '', '.');
                unset($get_data[$key]['id_thn_harga']);
                unset($get_data[$key]['total_item']);
            }
        }
        
        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkegiatan()
    {
        $qry = $this->db->select("tb_spesifikasi_item.id,tb_spesifikasi_item.kodeKelompok,idSpesifikasi,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->order_by('tb_spesifikasi_item.kodeKelompok')->get('tb_spesifikasi_item')->result_array();
        return [
            'data' => !empty($qry) ? $qry : []
        ];
    }

    public function saveData($params)
    {
        if ($this->db->where('id', 1)->update('tb_manajemen_dashboard', $params)) {
            return [
                'message' => 'Edit Manajemen Dashboard Berhasil',
                'status' => 200,
            ];
        }

        return [
            'message' => 'Edit Manajemen Dashboard (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
            'status' => 400,
        ];
    }

    public function getReqById($id,$users)
    {
        $data = $this->db->select("*")
                        ->get("tb_manajemen_dashboard")
                        ->row();

        if($data){
            $data->idItem = json_decode($data->idItem);
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

}
