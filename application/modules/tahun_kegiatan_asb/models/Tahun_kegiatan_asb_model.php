<?php
class Tahun_kegiatan_asb_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_standar_biaya_thn.id,tahunASB,kodeKelompok,tb_standar_biaya.UraianKegiatan,tb_standar_biaya.satuan')
                ->join('tb_standar_biaya', 'tb_standar_biaya_thn.idASB = tb_standar_biaya.id');

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
        $this->db->order_by('tb_standar_biaya_thn.idASB ASC, tahunASB DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_standar_biaya_thn')->result_array();
        $get_count = $tot->get('tb_standar_biaya_thn')->num_rows();

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

    public function getkegiatan()
    {
        $get_data = $this->db->select('id,idASB,UraianKegiatan,satuan')
                            ->order_by('idASB')->get('tb_standar_biaya')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $kelompok_item = $this->db->where('id', $params['idASB'])
                                    ->get('tb_standar_biaya')->first_row();

        $kodeKelompok = $kelompok_item->idASB;
        $params['kodeKelompok'] = $kodeKelompok;

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                            ->join('tb_standar_biaya','tb_standar_biaya_thn.idASB = tb_standar_biaya.id')
                            ->where('tb_standar_biaya_thn.id !=', $id)
                            ->where('tb_standar_biaya_thn.idASB', $params['idASB'])
                            ->where('tahunASB', $params['tahunASB'])
                            ->get('tb_standar_biaya_thn')->first_row();

            if($cek){
                return [
                    'message' => 'Edit Tahun Kegiatan Gagal! Kode Kegiatan '.$kodeKelompok.' & Tahun '.$params['tahunASB'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_standar_biaya_thn', $params)) {
                return [
                    'message' => 'Edit Tahun Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            $cek = $this->db->select('*')
                            ->join('tb_standar_biaya','tb_standar_biaya_thn.idASB = tb_standar_biaya.id')
                            ->where('tb_standar_biaya_thn.idASB', $params['idASB'])
                            ->where('tahunASB', $params['tahunASB'])
                            ->get('tb_standar_biaya_thn')->first_row();

            if($cek){
                return [
                    'message' => 'Tambah Tahun Kegiatan Gagal! Kode Kegiatan '.$kodeKelompok.' & Tahun '.$params['tahunASB'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_standar_biaya_thn', $params)) {
                return [
                    'message' => 'Tambah Tahun Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Tahun Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id,$users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,idASB,tahunASB')
            ->where('id', $id);

        $data =  $this->db->get('tb_standar_biaya_thn')->row();

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
        if ($this->db->delete('tb_thn_kegiatan', ['id' => $id])) {
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
