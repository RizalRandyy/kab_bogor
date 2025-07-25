<?php
class Usulan_kegiatan_asb_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
            tb_usulan_standar_biaya.id, 
            idASB, 
            tb_usulan_standar_biaya.idOpd, 
            UraianKegiatan, 
            satuan, 
            tahunASB, 
            status,
            tb_usulan_standar_biaya.idOpdPengusul, 
            opd_bidang.namaOpd AS namaBidang, 
            opd_pengusul.namaOpd AS namaOpdPengusul
        ');
        $this->db->from('tb_usulan_standar_biaya');
        $this->db->join('tb_opd AS opd_bidang', 'tb_usulan_standar_biaya.idOpd = opd_bidang.idOpd', 'left');
        $this->db->join('tb_opd AS opd_pengusul', 'tb_usulan_standar_biaya.idOpdPengusul = opd_pengusul.idOpd', 'left');


        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key === 'namaOpdPengusul') {
                        $this->db->like('opd_pengusul.namaOpd', $value);
                    } elseif ($key === 'namaBidang') {
                        $this->db->like('opd_bidang.namaOpd', $value);
                    } else {
                        $this->db->like('tb_usulan_standar_biaya.' . $key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;

        $this->db->order_by('idASB ASC');
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

    public function getOpd()
    {
        $get_data = $this->db->select('id,idOpd,namaOpd')
            ->order_by('idOpd')->get('tb_opd')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function getOpdPengusul()
    {
        $get_data = $this->db->select('id,idOpd,namaOpd')
            ->order_by('idOpd')->get('tb_opd')->result_array();

        return [
            'data' => !empty($get_data) ? $get_data : []
        ];
    }

    public function saveData($params)
    {
        $cek_asb_tetap = $this->db->where('idASB', $params['idASB'])
            ->get('tb_standar_biaya')->first_row();

        if ($cek_asb_tetap) {
            return [
                'message' => 'Gagal! Kode ASB ' . $params['idASB'] . ' sudah menjadi kegiatan tetap dan tidak bisa diubah/usulkan kembali!',
                'status' => 500,
            ];
        }

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $get_data = $this->db->select('*')
                ->where('id !=', $id)
                ->where('idASB', $params['idASB'])
                ->get('tb_usulan_standar_biaya')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Kegiatan Gagal! Kode Kegiatan ' . $params['idASB'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->where('id', $id)->update('tb_usulan_standar_biaya', $params)) {
                return [
                    'message' => 'Edit Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $get_data = $this->db->select('*')
                ->where('idASB', $params['idASB'])
                ->get('tb_usulan_standar_biaya')->first_row();

            if ($cek) {
                return [
                    'message' => 'Tambah Kegiatan Gagal! Kode Kegiatan ' . $params['idASB'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            if ($this->db->insert('tb_usulan_standar_biaya', $params)) {
                return [
                    'message' => 'Tambah Kegiatan Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Kegiatan Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function setujuiUsulan($id, $user)
    {
        $id = decrypt_url($id);

        if (!$id || !$user) {
            return ['status' => false, 'message' => 'Kode kegiatan atau user tidak valid'];
        }

        $usulan = $this->db->get_where('tb_usulan_standar_biaya', ['id' => $id])->row();

        if (!$usulan) {
            return ['status' => false, 'message' => 'Data usulan tidak ditemukan'];
        }

        if ($usulan->status === 'disetujui') {
            return ['status' => false, 'message' => 'Usulan ini sudah disetujui sebelumnya'];
        }

        $this->db->select('tb_standar_biaya.*');
        $this->db->from('tb_standar_biaya');
        $this->db->join('tb_standar_biaya_thn', 'tb_standar_biaya_thn.idASB = tb_standar_biaya.id');
        $this->db->where('tb_standar_biaya_thn.kodeKelompok', $usulan->idASB);
        $asbExists = $this->db->get()->row();

        if ($asbExists) {
            return ['status' => false, 'message' => 'ASB sudah tersedia di data ASB'];
        }

        $newAsbId = $this->db->select_max('id')->get('tb_standar_biaya')->row()->id + 1;
        $newThnAsbId = $this->db->select_max('id')->get('tb_standar_biaya_thn')->row()->id + 1;

        $now = date('Y-m-d H:i:s');

        $asbData = [
            'id'               => $newAsbId,
            'idASB'            => $usulan->idASB,
            'idOpd'            => $usulan->idOpd,
            'UraianKegiatan'   => $usulan->UraianKegiatan,
            'satuan'           => $usulan->satuan,
            'updated_by'       => $user['id'],
            'updated_at'       => $now,
        ];

        $thnAsbData = [
            'id'            => $newThnAsbId,
            'idASB'         => $newAsbId,
            'tahunASB'      => $usulan->tahunASB,
            'kodeKelompok'  => $usulan->idASB,
            'updated_by'    => $user['id'],
            'updated_at'    => $now,
        ];

        $this->db->insert('tb_standar_biaya', $asbData);
        $this->db->insert('tb_standar_biaya_thn', $thnAsbData);
        $this->db->where('idASB', $usulan->idASB)
            ->update('tb_usulan_standar_biaya', ['status' => 'disetujui']);

        if ($this->db->trans_status() === false) {
            return ['status' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'];
        }

        return ['status' => true, 'message' => 'Usulan berhasil disetujui.'];
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);

        $this->db->select('tb_usulan_standar_biaya.id, idASB, tb_usulan_standar_biaya.idOpd, UraianKegiatan, satuan, tahunASB, tb_usulan_standar_biaya.idOpdPengusul, tb_opd.namaOpd');
        $this->db->from('tb_usulan_standar_biaya');
        $this->db->join('tb_opd', 'tb_usulan_standar_biaya.idOpd = tb_opd.idOpd', 'left');
        $this->db->where('tb_usulan_standar_biaya.id', $id);

        $data = $this->db->get()->row();

        if ($data) {
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
        if ($this->db->delete('tb_usulan_standar_biaya', ['id' => $id])) {
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

    public function getheader()
    {
        $header  = array("No" => 'reset', "Kode Kegiatan" => "idASB", "Bidang" => "namaBidang", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunASB", "OPD / Dinas Pengusul" => 'namaOpdPengusul');
        return $header;
    }
}
