<?php
class Perkiraan_hps_model extends CI_Model
{
    public function getData($params, $users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
            ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
            ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
            ->where('id_thn_harga !=', '[]')
            ->where('id_thn_harga is not null', null);

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if ($value) {
                    if ($key == 'UraianKegiatan') {
                        $this->db->like('tb_kegiatan.UraianKegiatan', $value);
                    } elseif ($key == 'satuan') {
                        $this->db->like('tb_kegiatan.satuan', $value);
                    } else {
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
                    $data_harga = $this->db->query("SELECT SUM(harga * " . $total_item[$ky] . ") as total FROM tb_thn_harga WHERE id = '" . $val . "'")->first_row();
                    $total_satuan[] = $data_harga->total;
                }
                $total = array_sum($total_satuan);

                $get_data[$key]['harga'] = 'Rp.' . number_format($total, 0, '', '.');
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

    public function getSshByHspk($id)
    {
        $id = decrypt_url($id);

        $kegiatan = $this->db->select('
            tk.id,
            tk.kodeKelompok,
            k.UraianKegiatan,
            k.satuan,
            tk.tahunPekerjaan
        ')
            ->from('tb_thn_pekerjaan_detail tpd')
            ->join('tb_thn_kegiatan tk', 'tpd.id_thn_kegiatan = tk.id')
            ->join('tb_kegiatan k', 'tk.idKegiatan = k.id')
            ->where('tpd.id', $id)
            ->order_by('tk.kodeKelompok')
            ->order_by('tk.tahunPekerjaan', 'DESC')
            ->get()
            ->result_array();

        if (empty($kegiatan)) {
            return [
                'status' => 500,
                'kegiatan' => [],
                'kel_spesifikasi' => []
            ];
        }

        $detail = $this->db->select('id_thn_harga, total_item')
            ->from('tb_thn_pekerjaan_detail')
            ->where('id', $id)
            ->get()
            ->row_array();

        $harga_ids = [];
        $total_items = [];

        if (!empty($detail) && isset($detail['id_thn_harga'])) {
            $harga_ids = json_decode($detail['id_thn_harga'], true);
        }

        if (!empty($detail) && isset($detail['total_item'])) {
            $total_items = json_decode($detail['total_item'], true);
        }

        $qty_map = [];

        foreach ($harga_ids as $i => $hid) {
            $qty_map[$hid] = $total_items[$i] ?? 0;
        }

        if (empty($harga_ids)) {
            return [
                'status' => 200,
                'kegiatan' => $kegiatan,
                'kel_spesifikasi' => []
            ];
        }

        $kel_spesifikasi = $this->db->select("
        s.id AS id_spesifikasi,
        k.id AS id_kelompok,
        k.idKelItem AS kodeKelItem,
        k.UraianKelompok,
        k.tipe,
        k.kriteria,
        j.namaJenis,
        s.UraianSpesifikasi,
        s.satuan,
        h.id AS id_harga, 
        h.harga AS value_harga
    ")
            ->from("tb_spesifikasi_item s")
            ->join("tb_jenis_item j", "j.id = s.idJenisItem", "left")
            ->join("tb_kelompok_item k", "k.id = j.idKelompokItem", "left")
            ->join("tb_thn_harga h", "h.idSpesifikasi = s.id", "left")
            ->where_in("h.id", $harga_ids)
            ->order_by("k.tipe ASC")
            ->order_by("k.kriteria ASC")
            ->order_by("k.idKelItem ASC")
            ->order_by("s.id ASC")
            ->get()
            ->result_array();


        foreach ($kel_spesifikasi as &$row) {

            $qty = $qty_map[$row['id_harga']] ?? 0;

            $row['total_item'] = $qty;
            $row['subtotal'] = $row['value_harga'] * $qty;

            $row['harga'] = $row['value_harga']
                ? 'Rp.' . number_format($row['value_harga'], 0, '', '.')
                : '-';

            $row['subtotal_rp'] = 'Rp.' . number_format($row['subtotal'], 0, '', '.');
        }

        return [
            'status' => empty($kegiatan) ? 500 : 200,
            'kegiatan' => $kegiatan ?: [],
            'kel_spesifikasi' => $kel_spesifikasi ?: []
        ];
    }

    public function getAsb()
    {
        $asb = $this->db->select('
            tb_standar_biaya_thn_detail.id,
            tb_standar_biaya_thn.tahunASB,
            tb_standar_biaya_thn.kodeKelompok,
            tb_standar_biaya.UraianKegiatan,
            tb_standar_biaya.satuan,
            tb_standar_biaya.idASB
        ')
            ->from('tb_standar_biaya_thn_detail')
            ->join(
                'tb_standar_biaya_thn',
                'tb_standar_biaya_thn_detail.id_standar_biaya_thn = tb_standar_biaya_thn.id'
            )
            ->join(
                'tb_standar_biaya',
                'tb_standar_biaya_thn.idASB = tb_standar_biaya.id'
            )
            ->where('tb_standar_biaya_thn_detail.id_thn_pekerjaan_detail IS NOT NULL', null, false)
            ->where('tb_standar_biaya_thn_detail.id_thn_pekerjaan_detail !=', '[]')
            ->order_by('tb_standar_biaya_thn.kodeKelompok', 'ASC')
            ->order_by('tb_standar_biaya_thn.tahunASB', 'DESC')
            ->get()
            ->result_array();

        $kel_spesifikasi = $this->db->select("
        s.id AS id_spesifikasi,
        k.id AS id_kelompok,
        k.idKelItem AS kodeKelItem,
        k.UraianKelompok,
        k.tipe,
        k.kriteria,
        j.namaJenis,
        s.UraianSpesifikasi,
        s.satuan,
        h.harga AS value_harga
    ")
            ->from("tb_spesifikasi_item s")
            ->join("tb_jenis_item j", "j.id = s.idJenisItem", "left")
            ->join("tb_kelompok_item k", "k.id = j.idKelompokItem", "left")
            ->join("tb_thn_harga h", "h.idSpesifikasi = s.id", "left")
            ->order_by("k.tipe ASC")
            ->order_by("k.kriteria ASC")
            ->order_by("k.idKelItem ASC")
            ->order_by("s.id ASC")
            ->get()
            ->result_array();




        // Format harga
        foreach ($kel_spesifikasi as &$row) {
            $row['harga'] = $row['value_harga'] ? 'Rp.' . number_format($row['value_harga'], 0, '', '.') : '-';
        }

        foreach ($asb as &$row) {
            $row['id'] = encrypt_url($row['id']);
        }

        return [
            'asb' => $asb ?: [],
            'kel_spesifikasi' => $kel_spesifikasi ?: [],
        ];
    }

    public function getReqAsbById($id)
    {
        $id = decrypt_url($id);

        $data =  $this->db->select('id,id_standar_biaya_thn,id_thn_pekerjaan_detail')
            ->where('id', $id)
            ->get('tb_standar_biaya_thn_detail')->row();

        if ($data) {
            $data->id_thn_pekerjaan_detail = json_decode($data->id_thn_pekerjaan_detail);

            $kegiatan = $this->db->select('tb_standar_biaya_thn.id,kodeKelompok,UraianKegiatan,satuan,tahunASB')
                ->join("tb_standar_biaya", "tb_standar_biaya_thn.idASB = tb_standar_biaya.id")
                ->where("tb_standar_biaya_thn.id", $data->id_standar_biaya_thn)
                ->order_by('kodeKelompok')->get('tb_standar_biaya_thn')->row();

            $kel_spesifikasi = $this->db->select('tb_thn_pekerjaan_detail.id,tahunPekerjaan,tb_thn_kegiatan.kodeKelompok,tb_kegiatan.UraianKegiatan,tb_kegiatan.satuan,id_thn_harga,total_item')
                ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
                ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
                ->where_in("tb_thn_pekerjaan_detail.id", $data->id_thn_pekerjaan_detail)
                ->order_by('tb_thn_kegiatan.kodeKelompok ASC, tb_thn_kegiatan.tahunPekerjaan DESC')
                ->get('tb_thn_pekerjaan_detail')->result_array();

            // $kel_spesifikasi = $this->db->select("tb_thn_harga.id,tb_spesifikasi_item.kodeKelompok,TahunHarga,harga,UraianSpesifikasi,satuan,tb_jenis_item.NamaJenis,tb_kelompok_item.tipe,tb_kelompok_item.UraianKelompok")
            //                 ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
            //                 ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
            //                 ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
            //                 ->where_in("tb_thn_harga.id", $data->id_thn_harga)
            //                 ->order_by('tb_thn_harga.id')
            //                 ->get('tb_thn_harga')->result_array();

            $total_all = 0;
            if (!empty($kel_spesifikasi)) {
                foreach ($kel_spesifikasi as $key => $value) {
                    $id_harga = json_decode($value['id_thn_harga']);
                    $total_item = json_decode($value['total_item']);
                    $total_satuan = [];

                    foreach ($id_harga as $ky => $val) {
                        $data_harga = $this->db->query("SELECT SUM(harga * " . $total_item[$ky] . ") as total FROM tb_thn_harga WHERE id = '" . $val . "'")->first_row();
                        $total_satuan[] = $data_harga->total;
                    }
                    $total[$key] = array_sum($total_satuan);

                    $kel_spesifikasi[$key]['harga'] = 'Rp.' . number_format($total[$key], 0, '', '.');
                    $kel_spesifikasi[$key]['value_harga'] = $total[$key];
                    unset($kel_spesifikasi[$key]['id_thn_harga']);
                    unset($kel_spesifikasi[$key]['total_item']);

                    $spesifikasi[$key]['id'] = encrypt_url($kel_spesifikasi[$key]['id']);
                    $spesifikasi[$key]['kodeKelompok']   = $kel_spesifikasi[$key]['kodeKelompok'];
                    $spesifikasi[$key]['UraianKegiatan'] = $kel_spesifikasi[$key]['UraianKegiatan'];
                    $spesifikasi[$key]['satuan']         = $kel_spesifikasi[$key]['satuan'];
                    $spesifikasi[$key]['tahunASB']       = $kel_spesifikasi[$key]['tahunPekerjaan'];
                    $spesifikasi[$key]['harga']          = $kel_spesifikasi[$key]['harga'];
                }

                $total_all = array_sum($total);
            }

            unset($data->id_thn_pekerjaan_detail);
            unset($data->id_standar_biaya_thn);
            unset($data->id);

            $data->kegiatan = $kegiatan->kodeKelompok . ' - ' . $kegiatan->UraianKegiatan . ' - (' . $kegiatan->satuan . ') - ' . $kegiatan->tahunASB;
            $data->spesifikasi = $spesifikasi;
            $data->total = 'Rp.' . number_format($total_all, 0, '', '.');
        }

        return [
            'status' => empty($data) ? 500 : 200,
            'message' => empty($data) ? 'Data Tidak Ditemukan!' : null,
            'data' => !empty($data) ? $data : [],
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

    public function getHargaById($id)
    {
        return $this->db->select("
            tb_thn_harga.id,
            tb_spesifikasi_item.kodeKelompok,
            tb_spesifikasi_item.UraianSpesifikasi,
            tb_spesifikasi_item.satuan,
            tb_jenis_item.NamaJenis,
            tb_kelompok_item.UraianKelompok,
            tb_kelompok_item.tipe,
            tb_thn_harga.TahunHarga,
            tb_thn_harga.harga
        ")
            ->join("tb_spesifikasi_item", "tb_thn_harga.idSpesifikasi = tb_spesifikasi_item.id")
            ->join("tb_jenis_item", "tb_spesifikasi_item.idJenisItem = tb_jenis_item.id")
            ->join("tb_kelompok_item", "tb_jenis_item.idKelompokItem = tb_kelompok_item.id")
            ->where("tb_thn_harga.id", $id)
            ->get("tb_thn_harga")->row();
    }

    public function getDetailByKegiatan($id)
    {
        $row = $this->db->select('id_thn_harga,total_item,tb_thn_kegiatan.kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan')
            ->join('tb_thn_kegiatan', 'tb_thn_kegiatan.id = tb_thn_pekerjaan_detail.id_thn_kegiatan')
            ->join('tb_kegiatan', 'tb_kegiatan.id = tb_thn_kegiatan.idKegiatan')
            ->where('tb_thn_pekerjaan_detail.id_thn_kegiatan', $id)
            ->get('tb_thn_pekerjaan_detail')
            ->row();

        return [
            'detail_ids'   => json_decode($row->id_thn_harga),
            'total_item'   => json_decode($row->total_item),
            'kegiatan_text' => $row->kodeKelompok . ' - ' . $row->UraianKegiatan . ' - (' . $row->satuan . ') - ' . $row->tahunPekerjaan
        ];
    }

    public function getReqById($id)
    {
        $id = decrypt_url($id);

        // pastikan id array untuk where_in
        if (!is_array($id)) {
            $id = [$id];
        }

        $rows = $this->db->select('
            tb_thn_pekerjaan_detail.id,
            tahunPekerjaan,
            tb_thn_kegiatan.kodeKelompok,
            tb_kegiatan.UraianKegiatan,
            tb_kegiatan.satuan,
            id_thn_harga,
            total_item
        ')
            ->join('tb_thn_kegiatan', 'tb_thn_pekerjaan_detail.id_thn_kegiatan = tb_thn_kegiatan.id')
            ->join('tb_kegiatan', 'tb_thn_kegiatan.idKegiatan = tb_kegiatan.id')
            ->where_in('tb_thn_pekerjaan_detail.id', $id)
            ->order_by('tb_thn_kegiatan.kodeKelompok ASC')
            ->get('tb_thn_pekerjaan_detail')
            ->result_array();

        if (empty($rows)) {
            return [
                'status' => 500,
                'message' => 'Data Tidak Ditemukan!',
                'data' => []
            ];
        }

        $spesifikasi = [];
        $total_all = 0;

        foreach ($rows as $row) {

            $id_harga   = json_decode($row['id_thn_harga'], true);
            $total_item = json_decode($row['total_item'], true);

            $subtotal = 0;
            foreach ($id_harga as $i => $hid) {
                $harga = $this->db->select('harga')
                    ->where('id', $hid)
                    ->get('tb_thn_harga')
                    ->row()
                    ->harga;

                $subtotal += $harga * $total_item[$i];
            }

            $kegiatan = $row['UraianKegiatan'];

            $spesifikasi[] = [
                'id'    => encrypt_url($row['id']),
                'value' => $row['kodeKelompok'] . ' - ' . $row['UraianKegiatan'] . ' (' . $row['satuan'] . ') - ' . $row['tahunPekerjaan'],
                'harga' => 'Rp.' . number_format($subtotal, 0, '', '.')
            ];

            $total_all += $subtotal;
        }

        return [
            'status' => 200,
            'data' => [
                'kegiatan' => $kegiatan,
                // 'spesifikasi' => $spesifikasi,
                // 'total' => 'Rp.' . number_format($total_all, 0, '', '.')
            ]
        ];
    }

    public function deleteReq($id)
    {
        $id = decrypt_url($id);
        if ($this->db->delete('tb_standar_biaya_thn_detail', ['id' => $id])) {
            $cek = $this->db->select('id,id_thn_pekerjaan_detail')
                ->like('id_thn_pekerjaan_detail', '"' . $id . '"')
                ->get('tb_standar_biaya_thn_detail')
                ->result_array();

            if ($cek) {
                foreach ($cek as $key => $value) {
                    $id_thn_pekerjaan_detail = json_decode($value['id_thn_pekerjaan_detail']);
                    $thn_harga = [];
                    $banyak_item = [];

                    foreach ($id_thn_pekerjaan_detail as $ky => $val) {
                        if ($val != $id) {
                            $thn_harga[] = $val;
                        }
                    }
                    $data[] = [
                        'id' => $value['id'],
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

    public function getheader()
    {
        $header  = array("No" => 'reset', "Kode Item" => "kodeKelompok", "Uraian Kegiatan" => "UraianKegiatan", "Satuan" => "satuan", "Tahun" => "tahunPekerjaan",);
        return $header;
    }
}
