<?php
class Usulan_kegiatan_asb_detail_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
    tb_usulan_standar_biaya_thn_detail.id,
    tb_usulan_standar_biaya_thn_detail.status,
    tb_usulan_standar_biaya.tahunASB,
    tb_usulan_standar_biaya.idASB,
    tb_usulan_standar_biaya.UraianKegiatan,
    tb_usulan_standar_biaya.idOpd,
    opd_pelaksana.namaOpd AS namaOpdPelaksana,
    tb_usulan_standar_biaya.satuan,
    tb_usulan_standar_biaya.idOpdPengusul,
    opd_pengusul.namaOpd AS namaOpdPengusul,
    tb_usulan_standar_biaya_thn_detail.id_thn_pekerjaan_detail
');

        $this->db->from('tb_usulan_standar_biaya_thn_detail');
        $this->db->join('tb_usulan_standar_biaya', 'tb_usulan_standar_biaya.id = tb_usulan_standar_biaya_thn_detail.id_standar_biaya_thn');
        $this->db->join('tb_opd opd_pelaksana', 'opd_pelaksana.idOpd = tb_usulan_standar_biaya.idOpd', 'left');
        $this->db->join('tb_opd opd_pengusul', 'opd_pengusul.idOpd = tb_usulan_standar_biaya.idOpdPengusul', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    switch ($key) {
                        case 'UraianKegiatan':
                            $this->db->like('tb_usulan_standar_biaya.UraianKegiatan', $value);
                            break;
                        case 'satuan':
                            $this->db->like('tb_usulan_standar_biaya.satuan', $value);
                            break;
                        case 'namaOpdPelaksana':
                            $this->db->like('opd_pelaksana.namaOpd', $value);
                            break;
                        case 'namaOpdPengusul':
                            $this->db->like('opd_pengusul.namaOpd', $value);
                            break;
                        case 'tahunASB':
                            $this->db->where('tb_usulan_standar_biaya.tahunASB', $value);
                            break;
                        case 'kodeKelompok':
                            $this->db->like('tb_usulan_standar_biaya.idASB', $value);
                            break;
                        default:
                            $this->db->like($key, $value);
                            break;
                    }
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('tb_usulan_standar_biaya.idASB ASC, tb_usulan_standar_biaya.tahunASB DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get()->result_array();
        $get_count = $tot->get()->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);

                $id_thn_pekerjaan_detail = json_decode($value['id_thn_pekerjaan_detail']);
                $total_satuan = [];

                foreach ($id_thn_pekerjaan_detail as $ky => $val) {
                    $hspk_detail = $this->db->query("SELECT * FROM tb_thn_pekerjaan_detail WHERE id = ?", [$val])->first_row();

                    $id_harga = json_decode($hspk_detail->id_thn_harga);
                    $total_item = json_decode($hspk_detail->total_item);

                    foreach ($id_harga as $ky2 => $val2) {
                        $data_harga = $this->db->query("SELECT SUM(harga * ?) as total FROM tb_thn_harga WHERE id = ?", [$total_item[$ky2], $val2])->first_row();
                        $total_satuan[] = $data_harga->total;
                    }
                }

                $total = array_sum($total_satuan);
                $get_data[$key]['harga'] = 'Rp.' . number_format($total, 0, '', '.');
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
        // Ambil data dari tabel standar biaya dan OPD
        $kegiatan = $this->db->select('
            tb_usulan_standar_biaya.id,
            tb_usulan_standar_biaya.idASB,
            tb_usulan_standar_biaya.UraianKegiatan,
            tb_usulan_standar_biaya.satuan,
            tb_usulan_standar_biaya.idOpd,
            tb_opd.namaOpd,
            tb_usulan_standar_biaya.tahunASB,
            tb_usulan_standar_biaya.idOpdPengusul
        ')
            ->from('tb_usulan_standar_biaya')
            ->join("tb_opd", "tb_usulan_standar_biaya.idOpd = tb_opd.idOpd", "left")
            ->order_by('tb_usulan_standar_biaya.idASB')
            ->get()
            ->result_array();

        // Ambil data spesifikasi dari kegiatan dan detail pekerjaan
        $kel_spesifikasi = $this->db->select('
            tb_thn_pekerjaan_detail.id,
            tahunPekerjaan,
            tb_thn_kegiatan.kodeKelompok,
            tb_kegiatan.UraianKegiatan,
            tb_kegiatan.satuan,
            id_thn_harga,
            total_item
        ')
            ->from('tb_thn_pekerjaan_detail')
            ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
            ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
            ->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC')
            ->get()
            ->result_array();

        // Hitung harga total per item
        if (!empty($kel_spesifikasi)) {
            foreach ($kel_spesifikasi as $key => $value) {
                $id_harga = json_decode($value['id_thn_harga']);
                $total_item = json_decode($value['total_item']);
                $total_satuan = [];

                foreach ($id_harga as $ky => $val) {
                    $data_harga = $this->db->query(
                        "SELECT SUM(harga * ?) AS total FROM tb_thn_harga WHERE id = ?",
                        [$total_item[$ky], $val]
                    )->first_row();
                    $total_satuan[] = $data_harga->total;
                }

                $total = array_sum($total_satuan);
                $kel_spesifikasi[$key]['harga'] = 'Rp.' . number_format($total, 0, '', '.');
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

            if ($this->db->where('id', $id)->update('tb_usulan_standar_biaya_thn_detail', $params)) {
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

            if ($this->db->insert('tb_usulan_standar_biaya_thn_detail', $params)) {
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

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('id,id_standar_biaya_thn,id_thn_pekerjaan_detail')
            ->where('id', $id);

        $data =  $this->db->get('tb_usulan_standar_biaya_thn_detail')->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
            $data->id_thn_pekerjaan_detail = json_decode($data->id_thn_pekerjaan_detail);
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
        ];
    }

    public function setujuiUsulan($id, $user)
    {
        $id = decrypt_url($id);

        if (!$id || !$user) {
            return ['status' => false, 'message' => 'Kode kegiatan atau user tidak valid'];
        }

        $this->db->select('d.*, k.id, k.idASB, k.idOpd, k.UraianKegiatan, k.satuan, k.tahunASB, k.idOpdPengusul');
        $this->db->from('tb_usulan_standar_biaya_thn_detail d');
        $this->db->join('tb_usulan_standar_biaya k', 'k.id = d.id_standar_biaya_thn', 'left');
        $this->db->where('d.id', $id);
        $usulan = $this->db->get()->row();


        if (!$usulan) {
            return ['status' => false, 'message' => 'Data usulan tidak ditemukan'];
        }

        if ($usulan->status === 'disetujui') {
            return ['status' => false, 'message' => 'Usulan ini sudah disetujui sebelumnya'];
        }

        $this->db->select('tb_standar_biaya_thn_detail.*');
        $this->db->from('tb_standar_biaya_thn_detail');
        $this->db->join('tb_standar_biaya_thn', 'tb_standar_biaya_thn.id = tb_standar_biaya_thn_detail.id_standar_biaya_thn');
        $this->db->where('tb_standar_biaya_thn.idASB', $usulan->idASB);
        $asbExists = $this->db->get()->row();

        if ($asbExists) {
            return ['status' => false, 'message' => 'ASB detail sudah tersedia di data ASB'];
        }

        $newAsbId = $this->db->select_max('id')->get('tb_standar_biaya')->row()->id + 1;
        $newThnAsbId = $this->db->select_max('id')->get('tb_standar_biaya_thn')->row()->id + 1;
        $newThnAsbDetailId = $this->db->select_max('id')->get('tb_standar_biaya_thn_detail')->row()->id + 1;

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

        $thnAsbDetailData = [
            'id'                              => $newThnAsbDetailId,
            'id_standar_biaya_thn'            => $newThnAsbId,
            'id_thn_pekerjaan_detail'         => $usulan->id_thn_pekerjaan_detail, // Cek
            'updated_by'                      => $user['id'],
            'updated_at'                      => $now,
        ];

        $this->db->insert('tb_standar_biaya', $asbData);
        $this->db->insert('tb_standar_biaya_thn', $thnAsbData);
        $this->db->insert('tb_standar_biaya_thn_detail', $thnAsbDetailData);
        $this->db->where('idASB', $usulan->idASB)
            ->update('tb_usulan_standar_biaya', ['status' => 'disetujui']);
        $this->db->where('id_standar_biaya_thn', $usulan->id)
            ->update('tb_usulan_standar_biaya_thn_detail', ['status' => 'disetujui']);

        if ($this->db->trans_status() === false) {
            return ['status' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'];
        }

        return ['status' => true, 'message' => 'Usulan berhasil disetujui.'];
    }


    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_usulan_standar_biaya_thn_detail', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "idASB", "Bidang" => "namaOpdPelaksana", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunASB", "OPD / Dinas Pengusul" => "namaOpdPengusul");
        return $header;
    }
}
