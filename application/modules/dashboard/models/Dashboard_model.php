<?php
class Dashboard_model extends CI_Model
{
	public function getData($year = '2023')
	{	
		// $qry = $this->db->query("SELECT MIN(harga) AS min_harga, MAX(harga) AS max_harga FROM tb_thn_harga")->result_array();

		$year = [];
		$data = [];
		$list = [];
		$qry = $this->db->query("SELECT TahunPekerjaan FROM tb_thn_kegiatan GROUP BY TahunPekerjaan ORDER BY TahunPekerjaan")->result_array();

		if($qry){
			foreach ($qry as $key => $value) {
				$year[] = $value['TahunPekerjaan'];
				$data[] = array("year" => $value['TahunPekerjaan'], "value" => 0);
			}

			$get_id = $this->db->query("SELECT idItem FROM tb_manajemen_dashboard")->first_row();

			$id = json_decode($get_id->idItem);
			// echo "<pre>"; print_r($id); exit;

			$qry = $this->db->select("*")
							->join("tb_spesifikasi_item spesifikasi ", "harga.idSpesifikasi = spesifikasi.id")
							->join("tb_jenis_item jenis ", "spesifikasi.idJenisItem = jenis.id")
							->join("tb_kelompok_item kelompok ", "jenis.idKelompokItem = kelompok.id")
							->where_in("spesifikasi.id", $id)
							->where_in("TahunHarga", $year)
							->order_by("idJenisItem, TahunHarga")
							->get("tb_thn_harga harga")
							->result_array();

			// $qry = $this->db->select("*")
			// 				->join("tb_jenis_item item", "spesifikasi.idJenisItem = item.id")
			// 				->join("tb_kelompok_item kelompok", "item.idKelompokItem = kelompok.id")
			// 				->join("tb_thn_harga harga", "harga.idSpesifikasi = spesifikasi.id")
			// 				->where_in("spesifikasi.id", $id)
			// 				->where_in("TahunHarga", $year)
			// 				->order_by("idJenisItem, TahunHarga")
			// 				->get("tb_spesifikasi_item spesifikasi")
			// 				->result_array();

			// print_r($this->db->last_query()); exit;
			// echo "<pre>"; print_r($qry); exit;

			foreach ($qry as $key2 => $value2) {
				$harga = 0;
				foreach ($year as $key3 => $value3) {
					// print_r($value3); echo " - <pre>"; print_r($value2['TahunHarga']); exit;
					if((int)$value3 == (int)$value2['TahunHarga']){
						$list[$value2['UraianKelompok']." - ".$value2['UraianSpesifikasi']." (".$value2['satuan'].")"][$value3] = ["year" => $value3,
															"harga" => $value2['harga']];

						if($value2['harga']){
							$harga = (int)$value2['harga'];
						}
					} else{
						$list[$value2['UraianKelompok']." - ".$value2['UraianSpesifikasi']." (".$value2['satuan'].")"][$value3] = ["year" => $value3,
															"harga" => $harga];
					}

					// if($value2['harga']){
					// 	$harga = (int)$value2['harga'];
					// }


					// $list[$value2['UraianKelompok']." - ".$value2['UraianSpesifikasi']." (".$value2['satuan'].")"][$value3] = ["year" => $value3,
					// 										"harga" => $harga];
					
				}
				
			}

			foreach ($qry as $key4 => $value4) {
				$list[$value4['UraianKelompok']." - ".$value4['UraianSpesifikasi']." (".$value4['satuan'].")"][$value4['TahunHarga']] = ["year" => $value4['TahunHarga'],
															"harga" => (int)$value4['harga']];
				
			}

			// foreach ($list as $key5 => $value5) {
			// 	echo "<pre>"; print_r($key5); exit;
			// 	$header = $key5
			// 	for
			// 	$data = 
			// }

		}



		// echo "<pre>"; print_r($list); exit;

		return $list;
	}

	public function getDataLokasi($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('id,nama_toko,tautan');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($value){
                    $this->db->like($key, $value);
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('nama_toko', 'ASC');
        $get_data = $this->db->limit($params['limit'], $start)->get('tb_lokasi')->result_array();
        $get_count = $tot->get('tb_lokasi')->num_rows();

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

    public function getheader(){
        $header  = array("No" => 'reset', "Nama Toko" => "nama_toko");  
        return $header;
    }
}
?>