<?php
class Kegiatan_asb_detail_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('
        tb_standar_biaya_thn_detail.id,
        tahunASB,
        tb_standar_biaya_thn.kodeKelompok,
        tb_standar_biaya.UraianKegiatan,
        tb_standar_biaya.idOpd,
        tb_opd.namaOpd,
        tb_standar_biaya.satuan,
        id_thn_pekerjaan_detail
    ')
            ->from('tb_standar_biaya_thn_detail')
            ->join('tb_standar_biaya_thn', 'tb_standar_biaya_thn_detail.id_standar_biaya_thn = tb_standar_biaya_thn.id')
            ->join('tb_standar_biaya', 'tb_standar_biaya_thn.idASB = tb_standar_biaya.id')
            ->join('tb_opd', 'tb_standar_biaya.idOpd = tb_opd.idOpd', 'left')
            ->where('id_thn_pekerjaan_detail !=', '[]')
            ->where('id_thn_pekerjaan_detail IS NOT NULL', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == 'UraianKegiatan') {
                        if ($value) {
                            if ($key == 'UraianKegiatan') {
                                $this->db->like('tb_standar_biaya.UraianKegiatan', $value);
                            } elseif ($key == 'satuan') {
                            } elseif ($key == 'satuan') {
                                $this->db->like('tb_standar_biaya.satuan', $value);
                            } elseif ($key == 'namaOpd') {
                                $this->db->like('tb_opd.namaOpd', $value);
                            } else {
                                $this->db->like($key, $value);
                            }
                        }
                    }
                }
            }
        }


        $tot = clone $this->db;
        $this->db->order_by('tb_standar_biaya_thn.tahunASB DESC, tb_standar_biaya_thn.kodeKelompok ASC');
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
            tb_standar_biaya_thn.id,
            tb_standar_biaya_thn.kodeKelompok,
            tb_standar_biaya.UraianKegiatan,
            tb_standar_biaya.satuan,
            tb_standar_biaya.idOpd,
            tb_opd.namaOpd,
            tb_standar_biaya_thn.tahunASB
        ')
            ->from('tb_standar_biaya_thn')
            ->join("tb_standar_biaya", "tb_standar_biaya_thn.idASB = tb_standar_biaya.id")
            ->join("tb_opd", "tb_standar_biaya.idOpd = tb_opd.idOpd", "left")
            ->order_by('tb_standar_biaya_thn.kodeKelompok')
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

            if ($this->db->where('id', $id)->update('tb_standar_biaya_thn_detail', $params)) {
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

            if ($this->db->insert('tb_standar_biaya_thn_detail', $params)) {
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
        $this->db->select('id, id_standar_biaya_thn, id_thn_pekerjaan_detail');
        $this->db->where('id', $id);
        $data = $this->db->get('tb_standar_biaya_thn_detail')->row();

        if ($data) {
            $data->id = encrypt_url($data->id);
            $data->id_thn_pekerjaan_detail = json_decode($data->id_thn_pekerjaan_detail);

            $detail = [];
            $total = 0;
            $assoc_total_item = [];

            foreach ($data->id_thn_pekerjaan_detail as $id_pekerjaan_detail) {
                $pekerjaan_detail = $this->db
                    ->select('tb_thn_pekerjaan_detail.*, tb_thn_kegiatan.kodeKelompok, tb_thn_kegiatan.tahunPekerjaan, tb_kegiatan.UraianKegiatan AS kegiatanHSPK, tb_kegiatan.satuan AS satuanHSPK')
                    ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id', 'left')
                    ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id', 'left')
                    ->where('tb_thn_pekerjaan_detail.id', $id_pekerjaan_detail)
                    ->get('tb_thn_pekerjaan_detail')->row();


                if (!$pekerjaan_detail) continue;

                $id_thn_harga_list = json_decode($pekerjaan_detail->id_thn_harga);
                $total_item_list = json_decode($pekerjaan_detail->total_item);

                if (is_array($id_thn_harga_list)) {
                    foreach ($id_thn_harga_list as $i => $id_harga) {
                        $harga_row = $this->db
                            ->select('tb_thn_harga.*, tb_kelompok_item.UraianKelompok AS namaItem, tb_kelompok_item.tipe')
                            ->join('tb_kelompok_item', 'LEFT(tb_thn_harga.kodeKelompok, 4) = tb_kelompok_item.idKelItem', 'left')
                            ->where('tb_thn_harga.id', $id_harga)
                            ->get('tb_thn_harga')->row();

                        if (!$harga_row) continue;

                        $qty = isset($total_item_list[$i]) ? (int)$total_item_list[$i] : 1;
                        $subtotal = $harga_row->harga * $qty;
                        $total += $subtotal;

                        $assoc_total_item[$id_harga] = $qty;

                        $detail[] = [
                            'kodeKelompok' => $harga_row->kodeKelompok ?? '',
                            'kegiatanHSPK' => $pekerjaan_detail->kegiatanHSPK,
                            'namaItem' => $harga_row->namaItem ?? 'N/A',
                            'spesifikasi' => $harga_row->tipe ?? '',
                            'satuan' => $pekerjaan_detail->satuanHSPK,
                            'tahunHarga' => $pekerjaan_detail->tahunPekerjaan,
                            'qty' => $qty,
                            'harga' => (int)$harga_row->harga,
                            'subtotal' => $subtotal
                        ];
                    }
                }
            }

            $data->total_item = $assoc_total_item;
            $data->detail = $detail;
            $data->total = $total;
            $data->id_thn_harga = array_keys($assoc_total_item);
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

        // 1. Ambil data detail
        $asbDetail = $this->db->get_where('tb_standar_biaya_thn_detail', ['id' => $id])->row();

        if (!$asbDetail) {
            return [
                'message' => 'ASB tidak ditemukan.',
                'status' => 404
            ];
        }

        // 2. Ambil dari tb_standar_biaya_thn
        $asbThn = $this->db->get_where('tb_standar_biaya_thn', ['id' => $asbDetail->id_standar_biaya_thn])->row();
        if (!$asbThn) {
            return [
                'message' => 'Data standar biaya tahun tidak ditemukan.',
                'status' => 404
            ];
        }

        // 3. Ambil idASB dari tb_standar_biaya
        $standarBiaya = $this->db->get_where('tb_standar_biaya', ['id' => $asbThn->idASB])->row();
        if (!$standarBiaya) {
            return [
                'message' => 'Data standar biaya tidak ditemukan.',
                'status' => 404
            ];
        }

        $idAsb = $standarBiaya->idASB;

        // 4. Cari semua usulan yang memiliki idASB ini
        $usulanList = $this->db->get_where('tb_usulan_standar_biaya', ['idASB' => $idAsb])->result();

        if (!empty($usulanList)) {
            $idUsulanList = array_map(function ($item) {
                return $item->id;
            }, $usulanList);

            // 5. Update status di tb_usulan_standar_biaya_thn_detail
            $this->db->where_in('id_standar_biaya_thn', $idUsulanList);
            $this->db->where('status', 'disetujui');
            $this->db->update('tb_usulan_standar_biaya_thn_detail', ['status' => 'usulan']);
        }

        // 6. Hapus data detail
        if ($this->db->delete('tb_standar_biaya_thn_detail', ['id' => $id])) {
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
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Bidang" => "namaOpd", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunASB",);
        return $header;
    }

    public function cek($where, $table)
    {
        return $this->db->get_where($table, $where)->row();
    }

    public function insert_import($params, $table)
    {
        if ($this->db->insert($table, $params)) {
            return $this->db->insert_id();
        }
        return '';
    }

    public function importData($sheetData)
    {
        $inserted   = 0;
        $updated_by = decrypt_url($this->data['users']['id']);
        $updated_at = date('Y-m-d H:i:s');
        $pekerjaanBuffer = [];

        for ($i = 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];

            // --------------
            $cek_asb = $this->db->get_where('tb_standar_biaya', [
                'idASB' => $row[0],
            ])->row();

            if (!$cek_asb) {
                $this->db->insert('tb_standar_biaya', [
                    'idASB'          => $row[0],
                    'idOpd'          => $row[12],
                    'UraianKegiatan' => $row[1],
                    'satuan'         => $row[2],
                    'updated_by'     => $updated_by,
                    'updated_at'     => $updated_at
                ]);
                $id_asb = $this->db->insert_id();
            } else {
                $id_asb = $cek_asb->id;
            }

            // ---------------
            $cek_asb_thn = $this->db->get_where('tb_standar_biaya_thn', [
                'idASB'        => $id_asb,
                'tahunASB'     => $row[3],
                'kodeKelompok' => $row[0]
            ])->row();

            if (!$cek_asb_thn) {
                $this->db->insert('tb_standar_biaya_thn', [
                    'idASB'        => $id_asb,
                    'tahunASB'     => $row[3],
                    'kodeKelompok' => $row[0],
                    'updated_by'   => $updated_by,
                    'updated_at'   => $updated_at
                ]);
                $id_asb_thn = $this->db->insert_id();
            } else {
                $id_asb_thn = $cek_asb_thn->id;
            }

            // -------------
            $kegiatan = $this->db->get_where('tb_kegiatan', [
                'idKegiatan' => $row[4]
            ])->row();

            if (!$kegiatan) {
                $this->db->insert('tb_kegiatan', [
                    'idKegiatan' => $row[4],
                    'idBidangTeknis' => 'BT0001',
                    'UraianKegiatan' => $row[5],
                    'satuan'        => $row[2],
                    'updated_by'    => $updated_by,
                    'updated_at'    => $updated_at
                ]);
                $idKegiatan = $this->db->insert_id();
            } else {
                $idKegiatan = $kegiatan->id;
            }

            // ----------------
            $thn_kegiatan = $this->db->get_where('tb_thn_kegiatan', [
                'kodeKelompok'   => $row[4],
                'tahunPekerjaan' => $row[6],
            ])->row();

            if (!$thn_kegiatan) {
                $this->db->insert('tb_thn_kegiatan', [
                    'idKegiatan'     => $idKegiatan,
                    'kodeKelompok'   => $row[4],
                    'tahunPekerjaan' => $row[6],
                    'updated_by'     => $updated_by,
                    'updated_at'     => $updated_at
                ]);
                $id_thn_kegiatan = $this->db->insert_id();
            } else {
                $id_thn_kegiatan = $thn_kegiatan->id;
            }

            // ------------------
            $kelompok = $this->db->get_where('tb_kelompok_item', [
                'idKelItem' => $row[7]
            ])->row();

            if ($kelompok) {
                $idKelompokItem = $kelompok->id;
            } else {
                $this->db->insert('tb_kelompok_item', [
                    'idKelItem'      => $row[7],
                    'UraianKelompok' => $row[8],
                    'tipe'           => $row[14],
                    'updated_by'     => $updated_by,
                    'updated_at'     => $updated_at
                ]);
                $idKelompokItem = $this->db->insert_id();
            }

            // -----------------
            $namaJenis   = trim($row[13]);
            $idJenisBarang = $row[13];

            $jenisItem = $this->db->get_where('tb_jenis_item', [
                'NamaJenis'      => $namaJenis,
                'idKelompokItem' => $idKelompokItem
            ])->row();

            if ($jenisItem) {
                $idJenisItem = $jenisItem->id;
            } else {
                $this->db->insert('tb_jenis_item', [
                    'idKelompokItem' => $idKelompokItem,
                    'kodeKelompok'   => $row[7],
                    'idJenisBarang'  => $idJenisBarang,
                    'NamaJenis'      => $namaJenis,
                    'updated_by'     => $updated_by,
                    'updated_at'     => $updated_at
                ]);
                $idJenisItem = $this->db->insert_id();
            }

            // ----------------
            $spesifikasi = $this->db->get_where('tb_spesifikasi_item', [
                'kodeKelompok'      => $row[7],
                'UraianSpesifikasi' => $row[8],
                'idJenisItem'       => $idJenisItem
            ])->row();

            if ($spesifikasi) {
                $idSpesifikasi = $spesifikasi->id;
            } else {
                $this->db->insert('tb_spesifikasi_item', [
                    'kodeKelompok'      => $row[7],
                    'UraianSpesifikasi' => $row[8],
                    'idJenisItem'       => $idJenisItem,
                    'idSpesifikasi'     => $row[14],
                    'satuan'            => $row[2],
                    'updated_by'        => $updated_by,
                    'updated_at'        => $updated_at
                ]);
                $idSpesifikasi = $this->db->insert_id();
            }




            // ----------
            $hargaRow = $this->db->get_where('tb_thn_harga', [
                'kodeKelompok' => $row[7],
                'tahunHarga'   => $row[3]
            ])->row();

            if (!$hargaRow) {
                $this->db->insert('tb_thn_harga', [
                    'idSpesifikasi' => $idSpesifikasi,
                    'kodeKelompok'  => $row[7],
                    'tahunHarga'    => $row[3],
                    'harga'         => $row[10],
                    'updated_by'    => $updated_by,
                    'updated_at'    => $updated_at
                ]);
                $id_harga = $this->db->insert_id();
            } else {
                $id_harga = $hargaRow->id;
            }

            $qty = (float) $row[9];

            // -------------- 
            $hspkKey = $row[4] . '|' . $row[6] . '|' . $id_asb_thn;

            if (!isset($pekerjaanBuffer[$hspkKey])) {
                $pekerjaanBuffer[$hspkKey] = [
                    'id_asb_thn'      => $id_asb_thn,
                    'id_thn_kegiatan' => $id_thn_kegiatan,
                    'id_harga'        => [],
                    'qty'             => []
                ];
            }

            $pekerjaanBuffer[$hspkKey]['id_harga'][] = $id_harga;
            $pekerjaanBuffer[$hspkKey]['qty'][]      = $qty;
        }

        //---------------
        foreach ($pekerjaanBuffer as $hspk) {
            $this->db->insert('tb_thn_pekerjaan_detail', [
                'id_thn_kegiatan' => $hspk['id_thn_kegiatan'],
                'id_thn_harga'    => json_encode(array_map('strval', $hspk['id_harga'])),
                'total_item'      => json_encode(array_map('strval', $hspk['qty'])),
                'updated_by'      => $updated_by,
                'updated_at'      => $updated_at
            ]);
            $id_pekerjaan_detail = $this->db->insert_id();

            $cek_detail = $this->db->get_where('tb_standar_biaya_thn_detail', [
                'id_standar_biaya_thn' => $hspk['id_asb_thn']
            ])->row();

            if ($cek_detail) {
                $existing = json_decode($cek_detail->id_thn_pekerjaan_detail, true);
                $existing[] = $id_pekerjaan_detail;

                $this->db->where('id', $cek_detail->id)
                    ->update('tb_standar_biaya_thn_detail', [
                        'id_thn_pekerjaan_detail' => json_encode(array_map('strval', $existing)),
                        'updated_by' => $updated_by,
                        'updated_at' => $updated_at
                    ]);
            } else {
                $this->db->insert('tb_standar_biaya_thn_detail', [
                    'id_standar_biaya_thn'    => $hspk['id_asb_thn'],
                    'id_thn_pekerjaan_detail' => json_encode([strval($id_pekerjaan_detail)]),
                    'updated_by'              => $updated_by,
                    'updated_at'              => $updated_at
                ]);
            }

            $inserted++;
        }

        return $inserted;
    }
}
