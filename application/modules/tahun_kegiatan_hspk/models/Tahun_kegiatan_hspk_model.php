<?php
class Tahun_kegiatan_hspk_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_thn_kegiatan.id,tahunPekerjaan,kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan')
                ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id');

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
        $this->db->order_by('tb_thn_kegiatan.idKegiatan ASC, tahunPekerjaan DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_thn_kegiatan')->result_array();
        $get_count = $tot->get('tb_thn_kegiatan')->num_rows();

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
        $get_data = $this->db->select('id,idKegiatan,UraianKegiatan,satuan')
                            ->order_by('idKegiatan')->get('tb_kegiatan')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $kelompok_item = $this->db->where('id', $params['idKegiatan'])
                                    ->get('tb_kegiatan')->first_row();

        $kodeKelompok = $kelompok_item->idKegiatan;

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                            ->join('tb_kegiatan','tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                            ->where('tb_thn_kegiatan.id !=', $id)
                            ->where('tb_thn_kegiatan.idKegiatan', $params['idKegiatan'])
                            ->where('tahunPekerjaan', $params['tahunPekerjaan'])
                            ->get('tb_thn_kegiatan')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if($cek){
                return [
                    'message' => 'Edit Tahun Kegiatan Gagal! Kode Kegiatan '.$kodeKelompok.' & Tahun '.$params['tahunPekerjaan'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_thn_kegiatan', $params)) {
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
                            ->join('tb_kegiatan','tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                            ->where('tb_thn_kegiatan.idKegiatan', $params['idKegiatan'])
                            ->where('tahunPekerjaan', $params['tahunPekerjaan'])
                            ->get('tb_thn_kegiatan')->first_row();

            $params['kodeKelompok'] = $kodeKelompok;

            if($cek){
                return [
                    'message' => 'Tambah Tahun Kegiatan Gagal! Kode Kegiatan '.$kodeKelompok.' & Tahun '.$params['tahunPekerjaan'].' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_thn_kegiatan', $params)) {
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
        $this->db->select('id,idKegiatan,tahunPekerjaan')
            ->where('id', $id);

        $data =  $this->db->get('tb_thn_kegiatan')->row();

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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan",);  
        return $header;
    }
}
