<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require('././assets/plugins/SpreadsheetExcel/vendor/autoload.php');

class Kegiatan_hspk_detail extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Kegiatan_hspk_detail_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Kegiatan HSPK Detail';
		$this->data['page'] = 'kegiatan_hspk_detail';
		$this->data['version'] = 'kegiatan_hspk_detail';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/kegiatan_hspk_detail.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Kegiatan_hspk_detail_model->getData($this->get(NULL, TRUE), $this->data['users']);
		$return['header'] = $this->Kegiatan_hspk_detail_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' Kegiatan HSPK Detail';
		$this->data['page'] = 'tambah_kegiatan_hspk_detail';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id') ? $this->get('id', TRUE) : null;

		$this->data['js'] = array(
			'assets/js/app/tambah_kegiatan_hspk_detail.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/inputtags/js/bootstrap-tagsinput.js',
			'assets/plugins/select2/dist/js/select2.min.js'
		);

		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/inputtags/css/bootstrap-tagsinput.css',
			'assets/plugins/select2/dist/css/select2.css'
		];

		$this->template->load($this->data, null, 'form');
	}

	public function kegiatan_get()
	{
		$return = $this->Kegiatan_hspk_detail_model->getkegiatan();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$tahunPekerjaan = explode(",", $params['id_thn_harga']);
		$total_item = explode(",", $params['total_item']);

		$params['id_thn_harga'] = json_encode($tahunPekerjaan);
		$params['total_item'] = json_encode($total_item);

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Kegiatan_hspk_detail_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Kegiatan_hspk_detail_model->getReqById($this->get('id', TRUE), $this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Kegiatan_hspk_detail_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}

	public function download_files_get()
	{
		$this->load->helper('download');
		// Contents will be automatically read & exported
		force_download(FCPATH . "resources/template/Template Isian HSPK detail.xlsx", NULL);
	}

	public function import_post()
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

		$file 		 = $_FILES['template'];
		$tmp_name	 = $file['tmp_name'];

		$finfo = new finfo(FILEINFO_MIME_TYPE);

		if (false === $ext = array_search(
			$finfo->file($tmp_name),
			array(
				'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			),
			true
		)) {
			$return = [
				"status"    => 400,
				"message"   => "Tipe File tidak sesuai!"
			];

			$this->response($return, $return['status']);
			exit;
		}

		// echo "<pre>"; print_r($_FILES); exit;

		try {
			$oldErrorReporting = error_reporting(E_ALL & ~E_DEPRECATED);

			$reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($tmp_name);

			error_reporting($oldErrorReporting);

			$sheetData   = array_filter(array_map('array_filter', $spreadsheet->getActiveSheet()->toArray()));
		} catch (\Throwable $th) {
			$return = [
				'status' => 500,
				'message' => 'Cannot proceed file that has formula in it!',
			];

			$this->response($return, $return['status']);
			exit;
		}

		// Minimum Data is 2 row
		if (count($sheetData) < 2):
			$return = [
				'status' => 400,
				'message' => 'Data file harus memiliki 2 Row atau lebih!'
			];
			$this->response($return, $return['status']);
			exit;
		endif;

		// Maximum Data is 200 row
		if (count($sheetData) > 1001):
			$return = [
				'status' => 400,
				'message' => 'Data anda melebihi 1000 Row! silahkan dibagi menjadi beberapa bagian.'
			];
			$this->response($return, $return['status']);
			exit;
		endif;

		// echo "<pre>"; print_r($sheetData); exit;

		if (!empty($file['size'])) {
			$headerList = $sheetData[0];
			$trueFormat = array("KodeHSPK", "Bidang", "Keuntungan", "UraianKegiatan", "TahunHSPK", "SatuanKegiatan", "IdKelBrg", "UraianKelompok", "Tipe", "IdJenisBrg", "NamaJenis", "IdSpesifikasi", "UraianSpesifikasi", "Satuan", "TahunHarga", "HargaSatuan", "Koefisien", "JumlahHarga");

			if (count($headerList) == 18 && count(array_diff($trueFormat, $headerList)) == 0) {
				$updated_by = decrypt_url($this->data['users']['id']);
				$updated_at = date('Y-m-d H:i:s');

				for ($i = 1; $i < count($sheetData); $i++) {
					// echo "<pre>"; print_r($sheetData[$i]); exit;
					for ($j = 0; $j < 18; $j++) {
						if (empty($sheetData[$i][$j])) {
							$return = [
								'status' => 400,
								'message' => 'Data pada Row ' . $i . ' dan Column ' . $sheetData[0][$j] . ' tidak ada data!'
							];
							$this->response($return, $return['status']);
							exit;
						}
					}

					// Kegiatan HSPK
					$where = [
						'idKegiatan' => $sheetData[$i][0]
					];

					$cek_kegiatan = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_kegiatan');

					if ($cek_kegiatan) {
						if ($cek_kegiatan->UraianKegiatan != $sheetData[$i][3]) {
							if ($i == 1) {
								$msg = 'Data pada Row ' . $i . ' Column KodeHSPK ' . $sheetData[$i][0] . ' sudah digunakan oleh UraianKegiatan = ' . $cek_kegiatan->UraianKegiatan . '!';
							} else {
								$msg = 'Data pada Row ' . $i . ' Column KodeHSPK ' . $sheetData[$i][0] . ' sudah digunakan oleh UraianKegiatan = ' . $cek_kegiatan->UraianKegiatan . '! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
							}

							$return = [
								'status' => 500,
								'message' => $msg
							];
							$this->response($return, $return['status']);
							exit;
						}

						$kodeHspk = $cek_kegiatan->idKegiatan;
						$id_hspk = $cek_kegiatan->id;
					} else {
						// Ambil nama bidang teknis dari file Excel
						$nama_bidang_teknis = trim($sheetData[$i][1]);

						// Cari di tb_bidang_teknis berdasarkan nama
						$bidang = $this->db->where('namaBidangTeknis', $nama_bidang_teknis)
							->get('tb_bidang_teknis')
							->row();

						if (!$bidang) {
							$return = [
								'status' => 400,
								'message' => 'Bidang Teknis "' . $nama_bidang_teknis . '" tidak ditemukan di database!'
							];
							$this->response($return, $return['status']);
							exit;
						}

						$id_bidang_teknis = $bidang->idBidangTeknis;

						$data_hspk = [
							"idKegiatan"      => $sheetData[$i][0],
							"idBidangTeknis"  => $id_bidang_teknis,
							"UraianKegiatan"  => $sheetData[$i][3],
							"satuan"          => $sheetData[$i][5],
							"updated_by"      => $updated_by,
							"updated_at"      => $updated_at
						];

						$insert_hspk = $this->Kegiatan_hspk_detail_model->insert_import($data_hspk, 'tb_kegiatan');

						$kodeHspk = $sheetData[$i][0];
						$id_hspk = $insert_hspk;
					}

					// Tahun HSPK
					$where = [
						'idKegiatan' => $id_hspk,
						'TahunPekerjaan' => $sheetData[$i][4]
					];

					$cek_kegiatan = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_thn_kegiatan');

					if ($cek_kegiatan) {
						if ($cek_kegiatan->tahunPekerjaan != $sheetData[$i][4]) {
							if ($i == 1) {
								$msg = 'Data pada Row ' . $i . ' Column TahunPekerjaan ' . $sheetData[$i][4] . ' sudah digunakan !';
							} else {
								$msg = 'Data pada Row ' . $i . ' Column TahunPekerjaan ' . $sheetData[$i][4] . ' sudah digunakan ! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
							}
							$return = [
								'status' => 500,
								'message' => $msg
							];
							$this->response($return, $return['status']);
							exit;
						}

						$kodeHspk = $cek_kegiatan->kodeKelompok;
						$id_kegiatan = $cek_kegiatan->id;
					} else {

						$data_thn_kegiatan = [
							"idKegiatan" => $id_hspk,
							"TahunPekerjaan" => $sheetData[$i][4],
							"kodeKelompok" => $kodeHspk,
							"updated_by" => $updated_by,
							"updated_at" => $updated_at
						];

						$thn_kegiatan = $this->Kegiatan_hspk_detail_model->insert_import($data_thn_kegiatan, 'tb_thn_kegiatan');

						// $kodeKelompok = $kodeKelompok . '.' . $sheetData[$i][3];
						// $id_spesifikasi = $spesifikasi;
					}

					$where = [
						'kodeKelompok' => $sheetData[$i][6] . "." . $sheetData[$i][9] . "." . $sheetData[$i][11],
						'TahunHarga'   => $sheetData[$i][14]
					];

					$cek_ssh_harga = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_thn_harga');

					if (!$cek_ssh_harga) {

						// Kelompok Item
						$where = [
							'idKelItem' => $sheetData[$i][6]
						];

						$cek_kelompok = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_kelompok_item');

						if ($cek_kelompok) {
							if ($cek_kelompok->UraianKelompok != $sheetData[$i][7]) {
								if ($i == 1) {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column IdKelBrg ' . $sheetData[$i][6] .
										' sudah digunakan oleh UraianKelompok = ' . $cek_kelompok->UraianKelompok . '!';
								} else {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column IdKelBrg ' . $sheetData[$i][6] . ' sudah digunakan oleh UraianKelompok = ' . $cek_kelompok->UraianKelompok . '! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
								}

								$return = [
									'status' => 500,
									'message' => $msg
								];
								$this->response($return, $return['status']);
								exit;
							}

							$kodeKelompok = $cek_kelompok->IdKelItem;
							$id_kelompok = $cek_kelompok->id;
						} else {
							$data_kelompok = [
								"idKelItem" => $sheetData[$i][6],
								"UraianKelompok" => $sheetData[$i][7],
								"tipe" => $sheetData[$i][8],
								"updated_by" => $updated_by,
								"updated_at" => $updated_at
							];

							$insert_kelompok = $this->Kegiatan_hspk_detail_model->insert_import($data_kelompok, 'tb_kelompok_item');

							$kodeKelompok = $sheetData[$i][6];
							$id_kelompok = $insert_kelompok;
						}

						// Jenis Item
						$where = [
							'idKelompokItem' => $id_kelompok,
							'idJenisBarang' => $sheetData[$i][9]
						];

						$cek_jenis = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_jenis_item');

						if ($cek_jenis) {
							if ($cek_jenis->NamaJenis != $sheetData[$i][10]) {
								if ($i == 1) {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column IdJenisBrg ' . $sheetData[$i][9] . ' sudah digunakan oleh NamaJenis = ' . $cek_jenis->NamaJenis . '!';
								} else {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column IdJenisBrg ' . $sheetData[$i][9] . ' sudah digunakan oleh NamaJenis = ' . $cek_jenis->NamaJenis . ' ! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
								}
								$return = [
									'status' => 500,
									'message' => $msg
								];
								$this->response($return, $return['status']);
								exit;
							}

							$kodeKelompok = $cek_jenis->kodeKelompok;
							$id_jenis = $cek_jenis->id;
						} else {

							$data_jenis = [
								"idKelompokItem" => $id_kelompok,
								"idJenisBarang" => $sheetData[$i][9],
								"kodeKelompok" => $kodeKelompok . '.' . $sheetData[$i][9],
								"NamaJenis" => $sheetData[$i][10],
								"updated_by" => $updated_by,
								"updated_at" => $updated_at
							];

							$insert_jenis = $this->Kegiatan_hspk_detail_model->insert_import($data_jenis, 'tb_jenis_item');

							$kodeKelompok = $kodeKelompok . '.' . $sheetData[$i][9];
							$id_jenis = $insert_jenis;
						}
						// echo "<pre>"; print_r($sheetData[$i]); exit;

						// Spesifikasi Item
						$where = [
							'idJenisItem' => $id_jenis,
							'idSpesifikasi' => $sheetData[$i][11]
						];

						$cek_spesifikasi = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_spesifikasi_item');

						if ($cek_spesifikasi) {
							if ($cek_spesifikasi->UraianSpesifikasi != $sheetData[$i][12]) {
								if ($i == 1) {
									$msg = 'Data pada Row ' . $i . ' Column idSpesifikasi ' . $sheetData[$i][9] . ' sudah digunakan oleh UraianSpesifikasi = ' . $cek_spesifikasi->UraianSpesifikasi . '!';
								} else {
									$msg = 'Data pada Row ' . $i . ' Column idSpesifikasi ' . $sheetData[$i][9] . ' sudah digunakan oleh UraianSpesifikasi = ' . $cek_spesifikasi->UraianSpesifikasi . '! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
								}
								$return = [
									'status' => 500,
									'message' => $msg
								];
								$this->response($return, $return['status']);
								exit;
							}

							$kodeKelompok = $cek_spesifikasi->kodeKelompok;
							$id_spesifikasi = $cek_spesifikasi->id;
						} else {

							$data_jenis = [
								"idJenisItem" => $id_jenis,
								"idSpesifikasi" => $sheetData[$i][11],
								"kodeKelompok" => $kodeKelompok . '.' . $sheetData[$i][11],
								"UraianSpesifikasi" => $sheetData[$i][12],
								"satuan" => $sheetData[$i][13],
								"updated_by" => $updated_by,
								"updated_at" => $updated_at
							];

							$spesifikasi = $this->Kegiatan_hspk_detail_model->insert_import($data_jenis, 'tb_spesifikasi_item');

							$kodeKelompok = $kodeKelompok . '.' . $sheetData[$i][11];
							$id_spesifikasi = $spesifikasi;
						}
						// echo "<pre>"; print_r($sheetData[$i]); exit;

						// Spesifikasi Harga
						$where = [
							'idSpesifikasi' => $id_spesifikasi,
							'TahunHarga' => $sheetData[$i][14]
						];

						$cek_harga = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_thn_harga');

						if ($cek_harga) {
							if ($cek_harga->harga != $sheetData[$i][9]) {
								if ($i == 1) {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column TahunHarga ' . $sheetData[$i][14] . ' sudah digunakan dengan Harga = ' . $cek_harga->harga . '!';
								} else {
									$msg = 'Data pada Row ' . ($i + 1) . ' Column TahunHarga ' . $sheetData[$i][14] . ' sudah digunakan dengan Harga = ' . $cek_harga->harga . '! Tetapi Data pada row 1 s/d row ' . ($i - 1) . ' telah berhasil tersimpan.';
								}
								$return = [
									'status' => 500,
									'message' => $msg
								];
								$this->response($return, $return['status']);
								exit;
							}

							$kodeKelompok = $cek_harga->kodeKelompok;
							$id_harga = $cek_harga->id;
						} else {

							$data_jenis = [
								"idSpesifikasi" => $id_spesifikasi,
								"kodeKelompok" => $kodeKelompok,
								"TahunHarga" => $sheetData[$i][14],
								"harga" => $sheetData[$i][15],
								"updated_by" => $updated_by,
								"updated_at" => $updated_at
							];

							$spesifikasi = $this->Kegiatan_hspk_detail_model->insert_import($data_jenis, 'tb_thn_harga');

							$kodeKelompok = $kodeKelompok . '.' . $sheetData[$i][9];
							$id_spesifikasi = $spesifikasi;
						}
					}

					// Kegiatan HSPK Detail
					$where = [
						'idKegiatan' => $sheetData[$i][0]
					];

					$kegiatan = $this->db->where($where)
						->get('tb_kegiatan')
						->row();

					$thnKegiatan = $this->db->where('idKegiatan', $kegiatan->id)
						->get('tb_thn_kegiatan')
						->row();


					// $cek_kegiatan = $this->Kegiatan_hspk_detail_model->cek($where, 'tb_thn_pekerjaan_detail');
					$kodeKelompokItem = trim($sheetData[$i][6] . "." . $sheetData[$i][9] . "." . $sheetData[$i][11]);

					// Cari berdasarkan kode
					$kode_kelompok_item = $this->db->where('kodeKelompok', $kodeKelompokItem)
						->get('tb_thn_harga')
						->row();

					if (!$kode_kelompok_item) {
						$return = [
							'status' => 400,
							'message' => 'SSH dengan kode "' . $kode_kelompok_item . '" tidak ditemukan di database!'
						];
						$this->response($return, $return['status']);
						exit;
					}

					$id_item = $kode_kelompok_item->id;

					// cek apakah sudah ada data untuk id_thn_kegiatan ini
					$existing = $this->db->where('id_thn_kegiatan', $thnKegiatan->id)
						->get('tb_thn_pekerjaan_detail')
						->row();

					if ($existing) {
						// decode JSON lama
						$old_id_items = json_decode($existing->id_thn_harga, true) ?? [];
						$old_total_items = json_decode($existing->total_item, true) ?? [];

						// cek apakah id barang sudah ada
						$index = array_search($kode_kelompok_item->id, $old_id_items);

						if ($index !== false) {
							// kalau sudah ada → replace jumlah lama dengan jumlah baru
							$old_total_items[$index] = $sheetData[$i][16];
						} else {
							// kalau belum ada → tambahkan baru
							$old_id_items[] = $kode_kelompok_item->id;
							$old_total_items[] = $sheetData[$i][16];
						}

						// update row lama
						$update_data = [
							"id_thn_harga" => json_encode($old_id_items),
							"total_item"   => json_encode($old_total_items),
							"updated_by"   => $updated_by,
							"updated_at"   => $updated_at
						];

						$this->db->where('id', $existing->id)
							->update('tb_thn_pekerjaan_detail', $update_data);

						$id_hspk_detail = $existing->id;
					} else {
						// kalau belum ada, insert baru
						$id_items = [$kode_kelompok_item->id];
						$total_items = [$sheetData[$i][16]];

						$data_hspk_detail = [
							"id_thn_kegiatan" => $thnKegiatan->id,
							"id_thn_harga"    => json_encode($id_items),
							"total_item"      => json_encode($total_items),
							"updated_by"      => $updated_by,
							"keuntungan"      => $sheetData[$i][2],
							"updated_at"      => $updated_at
						];

						$insert_hspk_detail = $this->Kegiatan_hspk_detail_model
							->insert_import($data_hspk_detail, 'tb_thn_pekerjaan_detail');

						$id_hspk_detail = $insert_hspk_detail;
					}
				}

				$return = [
					'status'  => 200,
					'message' => 'Data berhasil tersimpan dengan total row ' . (count($sheetData) - 1)
				];
				$this->response($return, $return['status']);
				exit;
			} else {
				$return = [
					'status'  => 500,
					'message' => 'Data lampiran tidak sama dengan format sebenarnya, silakan unduh Template Data Isian SSH!'
				];
				$this->response($return, $return['status']);
				exit;
			};
		}
	}
}
