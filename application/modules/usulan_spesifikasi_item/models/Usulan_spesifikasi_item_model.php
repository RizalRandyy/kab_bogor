<?php
class Usulan_spesifikasi_item_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
        tb_usulan_spesifikasi_item.id,
        tb_usulan_spesifikasi_item.kodeKelompok,
        tb_usulan_spesifikasi_item.UraianSpesifikasi,
        tb_usulan_spesifikasi_item.satuan,
        tb_usulan_spesifikasi_item.TahunHarga,
        tb_usulan_spesifikasi_item.harga,
        tb_usulan_spesifikasi_item.dokumen,
        tb_usulan_spesifikasi_item.tautan,
        tb_usulan_spesifikasi_item.idOpd,
        tb_usulan_spesifikasi_item.status,
        tb_kelompok_item.UraianKelompok,
        tb_jenis_item.NamaJenis,
        tb_opd.namaOpd
    ')
            ->join('tb_jenis_item', 'tb_usulan_spesifikasi_item.idJenisItem = tb_jenis_item.id')
            ->join('tb_kelompok_item', 'tb_jenis_item.idKelompokItem = tb_kelompok_item.id')
            ->join('tb_opd', 'tb_usulan_spesifikasi_item.idOpd = tb_opd.idOpd', 'left');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == "kodeKelompok") {
                        $this->db->like('tb_usulan_spesifikasi_item.kodeKelompok', $value);
                    } elseif ($key == "UraianKelompok") {
                        $this->db->like('tb_kelompok_item.UraianKelompok', $value);
                    } elseif ($key == "NamaJenis") {
                        $this->db->like('tb_jenis_item.NamaJenis', $value);
                    } elseif ($key == "namaOpd") {
                        $this->db->like('tb_opd.namaOpd', $value);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }

        $tot = clone $this->db;
        $this->db->order_by('tb_usulan_spesifikasi_item.id', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_usulan_spesifikasi_item')->result_array();
        $get_count = $tot->get('tb_usulan_spesifikasi_item')->num_rows();

        if (!empty($get_data)) {
            foreach ($get_data as $key => $value) {
                $get_data[$key]['id'] = encrypt_url($value['id']);
                $get_data[$key]['idKelompok'] = $value['kodeKelompok'];
                $get_data[$key]['harga'] = 'Rp.' . number_format($value['harga'], 0, '', '.');
            }
        }

        return [
            'count' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
        ];
    }

    public function getkel_item()
    {
        $get_data = $this->db->select('tb_jenis_item.id,tb_kelompok_item.IdKelItem,tb_kelompok_item.UraianKelompok,idJenisBarang,NamaJenis,tipe')
            ->join('tb_kelompok_item', 'tb_jenis_item.idKelompokItem = tb_kelompok_item.id')
            ->order_by('idJenisBarang')->get('tb_jenis_item')->result_array();

        foreach ($get_data as $key => $value) {
            $get_data[$key]['IdKelItem'] = $value['IdKelItem'] . '.' . $value['idJenisBarang'];
        }

        return [
            'data' => !empty($get_data) ? $get_data : []
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

    public function saveData($params)
    {
        $jenis_item = $this->db->where('id', $params['idJenisItem'])
            ->get('tb_jenis_item')->first_row();

        $kodeKelompok = $jenis_item->kodeKelompok . '.' . $params['idSpesifikasi'];

        $sudahTetap = $this->db->where('kodeKelompok', $kodeKelompok)
            ->get('tb_spesifikasi_item')->first_row();

        if ($sudahTetap) {
            return [
                'message' => 'Gagal! Kode ' . $kodeKelompok . ' sudah menjadi SSH tetap dan tidak boleh diubah lagi!',
                'status' => 500,
            ];
        }

        if (!empty($params['id'])) {
            $id = decrypt_url($params['id']);
            unset($params['id']);

            $cek = $this->db->select('*')
                ->where('tb_usulan_spesifikasi_item.id !=', $id)
                ->where('kodeKelompok', $kodeKelompok)
                ->get('tb_usulan_spesifikasi_item')->first_row();

            if ($cek) {
                return [
                    'message' => 'Edit Usulan SSH Gagal! Kode Item ' . $kodeKelompok . ' & Kode Spesifikasi ' . $params['idSpesifikasi'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->where('id', $id)->update('tb_usulan_spesifikasi_item', $params)) {
                return [
                    'message' => 'Edit Usulan Spesifikasi Item Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Edit Usulan SSH Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        } else {
            $cek = $this->db->select('*')
                ->where('kodeKelompok', $kodeKelompok)
                ->get('tb_usulan_spesifikasi_item')->first_row();

            if ($cek) {
                return [
                    'message' => 'Tambah Usulan SSH Gagal! Kode Item ' . $jenis_item->kodeKelompok . ' & Kode Spesifikasi ' . $params['idSpesifikasi'] . ' sudah ada!',
                    'status' => 500,
                ];
            }

            $params['kodeKelompok'] = $kodeKelompok;

            if ($this->db->insert('tb_usulan_spesifikasi_item', $params)) {
                return [
                    'message' => 'Tambah Usulan SSH Berhasil',
                    'status' => 200,
                ];
            }

            return [
                'message' => 'Tambah Usulan SSH Gagal (error code 400), Silahkan muat ulang kembali halaman, atau hubungi admin!',
                'status' => 400,
            ];
        }
    }

    public function getReqById($id, $users)
    {
        $id = decrypt_url($id);
        $this->db->select('
            tb_usulan_spesifikasi_item.id,
            idJenisItem,
            idSpesifikasi,
            UraianSpesifikasi,
            satuan,
            TahunHarga,
            harga,
            dokumen,
            tautan,
            tb_usulan_spesifikasi_item.idOpd,
            tb_opd.namaOpd
        ');

        $this->db->from('tb_usulan_spesifikasi_item');
        $this->db->join('tb_opd', 'tb_opd.idOpd = tb_usulan_spesifikasi_item.idOpd', 'left');
        $this->db->where('tb_usulan_spesifikasi_item.id', $id);

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
        if ($this->db->delete('tb_usulan_spesifikasi_item', ['id' => $id])) {
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

    public function setujuiByKode($id, $user)
    {
        $id = decrypt_url($id);

        if (!$id || !$user) {
            return ['status' => false, 'message' => 'Kode kelompok atau user tidak valid'];
        }

        $usulan = $this->db->get_where('tb_usulan_spesifikasi_item', ['id' => $id])->row();
        if (!$usulan) {
            return ['status' => false, 'message' => 'Data usulan tidak ditemukan'];
        }

        if ($usulan->status === 'disetujui') {
            return ['status' => false, 'message' => 'Usulan ini sudah disetujui sebelumnya'];
        }

        $spesifikasiExists = $this->db->get_where('tb_spesifikasi_item', ['kodeKelompok' => $usulan->kodeKelompok])->row();
        if ($spesifikasiExists) {
            return ['status' => false, 'message' => 'Spesifikasi sudah tersedia di data SSH'];
        }

        $hargaExists = $this->db->get_where('tb_thn_harga', [
            'kodeKelompok' => $usulan->kodeKelompok,
            'TahunHarga'    => $usulan->TahunHarga
        ])->row();
        if ($hargaExists) {
            return ['status' => false, 'message' => 'Harga tahun ini sudah tersedia untuk spesifikasi ini'];
        }

        $newSpesifikasiId = $this->db->select_max('id')->get('tb_spesifikasi_item')->row()->id + 1;
        $newHargaId       = $this->db->select_max('id')->get('tb_thn_harga')->row()->id + 1;

        $now = date('Y-m-d H:i:s');

        $spesifikasiData = [
            'id'               => $newSpesifikasiId,
            'idJenisItem'      => $usulan->idJenisItem,
            'idSpesifikasi'    => $usulan->idSpesifikasi,
            'kodeKelompok'     => $usulan->kodeKelompok,
            'UraianSpesifikasi' => $usulan->UraianSpesifikasi,
            'satuan'           => $usulan->satuan,
            'updated_by'       => $user['id'],
            'updated_at'       => $now,
        ];

        $hargaData = [
            'id'            => $newHargaId,
            'idSpesifikasi' => $newSpesifikasiId,
            'kodeKelompok'  => $usulan->kodeKelompok,
            'TahunHarga'    => $usulan->TahunHarga,
            'harga'         => $usulan->harga,
            'updated_by'    => $user['id'],
            'updated_at'    => $now,
        ];

        $this->db->insert('tb_spesifikasi_item', $spesifikasiData);
        $this->db->insert('tb_thn_harga', $hargaData);
        $this->db->where('kodeKelompok', $usulan->kodeKelompok)
            ->update('tb_usulan_spesifikasi_item', ['status' => 'disetujui']);

        if ($this->db->trans_status() === false) {
            return ['status' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'];
        }

        return ['status' => true, 'message' => 'Usulan berhasil disetujui.'];
    }

    public function getheader()
    {
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Nama Kelompok" => "UraianKelompok", "Nama Jenis" => "NamaJenis", "Usaraian Spesifikasi" => "UraianSpesifikasi", "Satuan" => "satuan", "Tahun" => "TahunHarga", "Harga" => "harga", "dokumen" => "Download Dokumen", "tautan" => "Tautan","Nama OPD / Dinas Pengusul" => "namaOpd");
        return $header;
    }
}
