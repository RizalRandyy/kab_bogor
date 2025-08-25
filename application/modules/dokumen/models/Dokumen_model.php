<?php
class Dokumen_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('d.id, d.nama_dokumen, d.id_jenis_dokumen, j.jenis_dokumen as nama_jenis_dokumen, d.tahun, d.deskripsi, d.dokumen');
        $this->db->from('tb_detail_dokumen d');
        $this->db->join('tb_jenis_dokumen j', 'j.id = d.id_jenis_dokumen', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    $this->db->like($key, $value);
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('d.id', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get()->result_array();
        $get_count = $tot->get()->num_rows();

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

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('d.id, d.nama_dokumen, d.id_jenis_dokumen, j.jenis_dokumen as nama_jenis_dokumen, d.tahun, d.deskripsi, d.dokumen');
        $this->db->from('tb_detail_dokumen d');
        $this->db->join('tb_jenis_dokumen j', 'j.id = d.id_jenis_dokumen', 'left');
        $this->db->where('d.id', $id);

        $data = $this->db->get()->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
            $data->id_jenis_dokumen = (int) $data->id_jenis_dokumen;
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function getJenisDokumen()
    {
        return $this->db->select('id, jenis_dokumen')
            ->order_by('jenis_dokumen', 'ASC')
            ->get('tb_jenis_dokumen')
            ->result_array();
    }

    public function getheader()
    {
        $header  = array("No" => 'reset', "Nama Dokumen" => "nama_dokumen", "Jenis" => "nama_jenis_dokumen", "Tahun" => "tahun", "Deskripsi" => "deskripsi", "Download File" => "dokumen");
        return $header;
    }
}
