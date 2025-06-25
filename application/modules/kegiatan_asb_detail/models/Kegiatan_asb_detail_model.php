<?php
class Kegiatan_asb_detail_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_standar_biaya_thn_detail.id,tahunASB,tb_standar_biaya_thn.kodeKelompok,tb_standar_biaya.UraianKegiatan,tb_standar_biaya.satuan,id_thn_pekerjaan_detail')
                ->join('tb_standar_biaya_thn', 'tb_standar_biaya_thn_detail.id_standar_biaya_thn = tb_standar_biaya_thn.id')
                ->join('tb_standar_biaya', 'tb_standar_biaya_thn.idASB = tb_standar_biaya.id')
                ->where('id_thn_pekerjaan_detail !=','[]')
                ->where('id_thn_pekerjaan_detail is not null', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    if($key == 'UraianKegiatan'){
                        $this->db->like('tb_standar_biaya.UraianKegiatan', $value);
                    } elseif($key == 'satuan'){
                        $this->db->like('tb_standar_biaya.satuan', $value);
                    } else{
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('tb_standar_biaya_thn.kodeKelompok ASC, tb_standar_biaya_thn.tahunASB DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_standar_biaya_thn_detail')->result_array();

        $get_count = $tot->get('tb_standar_biaya_thn_detail')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                $id_thn_pekerjaan_detail = json_decode($value['id_thn_pekerjaan_detail']);
                $total_satuan = [];

                foreach ($id_thn_pekerjaan_detail as $ky => $val) {
                    $hspk_detail = $this->db->query("SELECT * FROM tb_thn_pekerjaan_detail WHERE id = '".$val."'")->first_row();

                    $id_harga = json_decode($hspk_detail->id_thn_harga);
                    $total_item = json_decode($hspk_detail->total_item);
                    
                    foreach ($id_harga as $ky2 => $val2) {
                        $data_harga = $this->db->query("SELECT SUM(harga * ".$total_item[$ky2].") as total FROM tb_thn_harga WHERE id = '".$val2."'")->first_row();
                        $total_satuan[] = $data_harga->total;
                    }
                }
                $total = array_sum($total_satuan);

                $get_data[$key]['harga'] = 'Rp.'.number_format($total, 0, '', '.');
                unset($get_data[$key]['id_thn_pekerjaan_detail']);
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
        $kegiatan = $this->db->select('tb_standar_biaya_thn.id,kodeKelompok,UraianKegiatan,satuan,tahunASB')
                            ->join("tb_standar_biaya", "tb_standar_biaya_thn.idASB = tb_standar_biaya.id")
                            ->order_by('kodeKelompok')->
                            get('tb_standar_biaya_thn')->result_array();

        $kel_spesifikasi = $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
                                    ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
                                    ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                                    ->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC')
                                    ->get('tb_thn_pekerjaan_detail')->result_array();

        if (!empty($kel_spesifikasi)) {
            foreach ($kel_spesifikasi as $key => $value) {
                $id_harga = json_decode($value['id_thn_harga']);
                $total_item = json_decode($value['total_item']);
                $total_satuan = [];
                
                foreach ($id_harga as $ky => $val) {
                    $data_harga = $this->db->query("SELECT SUM(harga * ".$total_item[$ky].") as total FROM tb_thn_harga WHERE id = '".$val."'")->first_row();
                    $total_satuan[] = $data_harga->total;
                }
                $total = array_sum($total_satuan);

                $kel_spesifikasi[$key]['harga'] = 'Rp.'.number_format($total, 0, '', '.');
                $kel_spesifikasi[$key]['value_harga'] = $total;
                unset($kel_spesifikasi[$key]['id_thn_harga']);
                unset($kel_spesifikasi[$key]['total_item']);
            }
        }

        return [
            'kegiatan' => !empty($kegiatan) ? $kegiatan : [],
            'kel_spesifikasi' => !empty($kel_spesifikasi) ? $kel_spesifikasi : []
        ];
    }

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            if ($this->db->where('id', $id)->update('tb_standar_biaya_thn_detail', $params)) {
                return [
                    'message' => 'Edit Pekerjaan Detail Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            if ($this->db->insert('tb_standar_biaya_thn_detail', $params)) {
                return [
                    'message' => 'Tambah Pekerjaan Detail Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Pekerjaan Detail Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,id_standar_biaya_thn,id_thn_pekerjaan_detail')
            ->where('id', $id);

        $data =  $this->db->get('tb_standar_biaya_thn_detail')->row();

        if($data){
            $data->id = encrypt_url($data->id);
            $data->id_thn_pekerjaan_detail = json_decode($data->id_thn_pekerjaan_detail);
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
        if ($this->db->delete('tb_standar_biaya_thn_detail', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunASB",);  
        return $header;
    }
}
