<?php
class Asb_model extends CI_Model
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
        $kegiatan = $this->db->select('tb_thn_kegiatan.id,kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan')
                            ->join("tb_kegiatan", "tb_thn_kegiatan.idKegiatan = tb_kegiatan.id")
                            ->order_by('kodeKelompok')->
                            get('tb_thn_kegiatan')->result_array();

        $kel_spesifikasi = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->order_by('tb_spesifikasi_item.kodeKelompok')
                            ->get('tb_thn_harga')->result_array();

        if (!empty($kel_spesifikasi)) {
            foreach ($kel_spesifikasi as $key => $value) {
                $kel_spesifikasi[$key]['harga'] = 'Rp.'.number_format($value['harga'], 0, '', '.');
                $kel_spesifikasi[$key]['value_harga'] = $value['harga'];
            }
        }

        return [
            'kegiatan' => !empty($kegiatan) ? $kegiatan : [],
            'kel_spesifikasi' => !empty($kel_spesifikasi) ? $kel_spesifikasi : []
        ];
    }

    public function getReqById($id)
    {
        $id = decrypt_url($id);

        $data =  $this->db->select('id,id_standar_biaya_thn,id_thn_pekerjaan_detail')
                            ->where('id', $id)
                            ->get('tb_standar_biaya_thn_detail')->row();

        if($data){
            $data->id_thn_pekerjaan_detail = json_decode($data->id_thn_pekerjaan_detail);

            $kegiatan = $this->db->select('tb_standar_biaya_thn.id,kodeKelompok,UraianKegiatan,satuan,tahunASB')
                            ->join("tb_standar_biaya", "tb_standar_biaya_thn.idASB = tb_standar_biaya.id")
                            ->where("tb_standar_biaya_thn.id", $data->id_standar_biaya_thn)
                            ->order_by('kodeKelompok')->
                            get('tb_standar_biaya_thn')->row();

            $kel_spesifikasi = $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
                                    ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
                                    ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                                    ->where_in("tb_thn_pekerjaan_detail.id", $data->id_thn_pekerjaan_detail)
                                    ->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC')
                                    ->get('tb_thn_pekerjaan_detail')->result_array();

            // $kel_spesifikasi = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
            //                 ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
            //                 ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
            //                 ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
            //                 ->where_in("tb_thn_harga.id", $data->id_thn_harga)
            //                 ->order_by('tb_thn_harga.id')
            //                 ->get('tb_thn_harga')->result_array();

            $total_all = 0;
            if (!empty($kel_spesifikasi)) {
                foreach ($kel_spesifikasi as $key => $value) {
                    $id_harga = json_decode($value['id_thn_harga']);
                    $total_item = json_decode($value['total_item']);
                    $total_satuan = [];
                    
                    foreach ($id_harga as $ky => $val) {
                        $data_harga = $this->db->query("SELECT SUM(harga * ".$total_item[$ky].") as total FROM tb_thn_harga WHERE id = '".$val."'")->first_row();
                        $total_satuan[] = $data_harga->total;
                    }
                    $total[$key] = array_sum($total_satuan);

                    $kel_spesifikasi[$key]['harga'] = 'Rp.'.number_format($total[$key], 0, '', '.');
                    $kel_spesifikasi[$key]['value_harga'] = $total[$key];
                    unset($kel_spesifikasi[$key]['id_thn_harga']);
                    unset($kel_spesifikasi[$key]['total_item']);

                    $spesifikasi[$key]['value'] = $kel_spesifikasi[$key]['kodeKelompok'].' - '.$kel_spesifikasi[$key]['UraianKegiatan'].' - ('.$kel_spesifikasi[$key]['satuan'].') - '.$kel_spesifikasi[$key]['tahunPekerjaan'];
                    $spesifikasi[$key]['harga'] = $kel_spesifikasi[$key]['harga'];
                }

                $total_all = array_sum($total);
            }

            unset($data->id_thn_pekerjaan_detail);
            unset($data->id_standar_biaya_thn);
            unset($data->id);

            $data->kegiatan = $kegiatan->kodeKelompok.' - '.$kegiatan->UraianKegiatan.' - ('.$kegiatan->satuan.') - '.$kegiatan->tahunASB;
            $data->spesifikasi = $spesifikasi;
            $data->total = 'Rp.'.number_format($total_all, 0, '', '.');
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function getheader(){
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunASB",);  
        return $header;
    }
}
