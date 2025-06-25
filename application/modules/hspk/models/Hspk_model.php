<?php
class Hspk_model extends CI_Model
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

        $data =  $this->db->select('id,id_thn_kegiatan,total_item,id_thn_harga')
                            ->where('id', $id)
                            ->get('tb_thn_pekerjaan_detail')->row();

        if($data){
            $data->id_thn_harga = json_decode($data->id_thn_harga);
            $banyak = json_decode($data->total_item);

            $kegiatan = $this->db->select('tb_thn_kegiatan.id,kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan')
                            ->join("tb_kegiatan", "tb_thn_kegiatan.idKegiatan = tb_kegiatan.id")
                            ->where("tb_thn_kegiatan.id", $data->id_thn_kegiatan)
                            ->order_by('kodeKelompok')
                            ->get('tb_thn_kegiatan')->row();

            $kel_spesifikasi = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->where_in("tb_thn_harga.id", $data->id_thn_harga)
                            ->order_by('tb_thn_harga.id')
                            ->get('tb_thn_harga')->result_array();
            
            $total_all = 0;
            if (!empty($kel_spesifikasi)) {
                foreach ($kel_spesifikasi as $key => $value) {

                    $kel_spesifikasi[$key]['harga'] = 'Rp.'.number_format($value['harga'], 0, '', '.');

                    $kel_spesifikasi[$key]['banyak'] = $banyak[$key];

                    $total[$key] = ((int)$value['harga'] * (int)$banyak[$key]);

                    $kel_spesifikasi[$key]['total'] = 'Rp.'.number_format($total[$key], 0, '', '.');

                    unset($kel_spesifikasi[$key]['id']);
                }
                $total_all = array_sum($total);
            }

            unset($data->id_thn_harga);
            unset($data->total_item);
            unset($data->id_thn_kegiatan);
            unset($data->id);

            $data->kegiatan = $kegiatan->kodeKelompok.' - '.$kegiatan->UraianKegiatan.' - ('.$kegiatan->satuan.') - '.$kegiatan->tahunPekerjaan;
            $data->spesifikasi = $kel_spesifikasi;
            $data->total = 'Rp.'.number_format($total_all, 0, '', '.');
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function getheader(){
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan",);  
        return $header;
    }
}
