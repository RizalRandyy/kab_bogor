<?php
class Perkiraan_hps_model extends CI_Model
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
                            ->order_by('kodeKelompok')
                            ->order_by('tahunPekerjaan', 'DESC')
                            ->get('tb_thn_kegiatan')->result_array();

        $kel_spesifikasi = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->order_by('tb_spesifikasi_item.kodeKelompok')
                            ->order_by('TahunHarga', 'DESC')
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

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            if ($this->db->where('id', $id)->update('tb_thn_pekerjaan_detail', $params)) {
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

            if ($this->db->insert('tb_thn_pekerjaan_detail', $params)) {
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

    public function getReqById($id)
    {
        $data = "";
        $qry = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
                            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
                            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
                            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
                            ->where("tb_thn_harga.id",$id)
                            ->order_by('tb_spesifikasi_item.kodeKelompok')
                            ->get('tb_thn_harga')->first_row();

        if($qry){
            $data = $qry->kodeKelompok.' - '.$qry->UraianKelompok.' - '.$qry->NamaJenis.' - '.$qry->UraianSpesifikasi.' - '.$qry->satuan.' - ('.$qry->tipe.') - '.$qry->TahunHarga;
        }

        return $data;
    }


    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_standar_biaya_thn_detail', ['id' => $id])) {
            $cek = $this->db->select('id,id_thn_pekerjaan_detail')
                            ->like('id_thn_pekerjaan_detail', '"'.$id.'"')
                            ->get('tb_standar_biaya_thn_detail')
                            ->result_array();

            if($cek){
                foreach ($cek as $key => $value) {
                    $id_thn_pekerjaan_detail = json_decode($value['id_thn_pekerjaan_detail']);
                    $thn_harga = [];
                    $banyak_item = [];

                    foreach ($id_thn_pekerjaan_detail as $ky => $val) {
                        if($val != $id){
                            $thn_harga[] = $val;
                        }
                    }
                    $data[] = ['id' => $value['id'],
                            'id_thn_pekerjaan_detail' => json_encode($thn_harga)
                        ];
                }

                $this->db->update_batch('tb_standar_biaya_thn_detail', $data, 'id');

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

    public function getheader(){
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan",);  
        return $header;
    }
}
