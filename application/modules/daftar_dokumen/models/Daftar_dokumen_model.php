<?php
class Daftar_dokumen_model extends CI_Model
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

    public function saveData($params)
    {
        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                ->where('id !=', $id)
                ->where('id', $id)
                ->get('tb_detail_dokumen')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Daftar Dokumen Gagal! Id Daftar Dokumen ' . $params['id'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_detail_dokumen', $params)) {
                return [
                    'message' => 'Edit Daftar Dokumen Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Daftar Dokumen Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {

            if ($this->db->insert('tb_detail_dokumen', $params)) {
                return [
                    'message' => 'Tambah Daftar Dokumen Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Daftar Dokumen Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
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


    public function deleteReq($id)
    {
        $id = decrypt_url($id);

        $dokumen = $this->db->select('dokumen')
            ->where('id', $id)
            ->get('tb_detail_dokumen')
            ->row();

        if ($dokumen && !empty($dokumen->dokumen)) {
            $filePath = FCPATH . 'resources/uploads/dokumen/' . $dokumen->dokumen;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($this->db->delete('tb_detail_dokumen', ['id' => $id])) {
            return [
                'message' => 'Delete success',
                'status'  => 200,
            ];
        }

        return [
            'message' => 'Delete failed, please refresh page!',
            'status'  => 400
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
