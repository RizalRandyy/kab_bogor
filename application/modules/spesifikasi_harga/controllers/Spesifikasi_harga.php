<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require('././assets/plugins/SpreadsheetExcel/vendor/autoload.php');

class Spesifikasi_harga extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Spesifikasi_harga_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Data Tahun Harga';
		$this->data['page'] = 'spesifikasi_harga';
		$this->data['version'] = 'spesifikasi_harga';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/spesifikasi_harga.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Spesifikasi_harga_model->getData($this->get(NULL, TRUE),$this->data['users']);
		$return['header'] = $this->Spesifikasi_harga_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment).' Tahun Harga';
		$this->data['page'] = 'tambah_spesifikasi_harga';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?$this->get('id', TRUE):null;

		$this->data['js'] = array(
			'assets/js/app/tambah_spesifikasi_harga.js?' . rand(),
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

	public function kel_spesifikasi_get()
	{
		$return = $this->Spesifikasi_harga_model->getkel_spesifikasi();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post($this->get('id'));

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Spesifikasi_harga_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Spesifikasi_harga_model->getReqById($this->get('id', TRUE),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Spesifikasi_harga_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}

	public function download_files_get()
	{
		$this->load->helper('download');
        // Contents will be automatically read & exported
        force_download(FCPATH . "resources/template/Tamplate Isian SSH.xlsx", NULL);
	}

	public function import_post()
	{
		$file 		 = $_FILES['template'];
		$tmp_name	 = $file['tmp_name'];	

		$finfo = new finfo(FILEINFO_MIME_TYPE);

        if(false === $ext = array_search(
            $finfo->file($tmp_name),
            array(
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ),
            true
        )){
            $return = [
                "status"    => 400,
                "message"   => "Tipe File tidak sesuai!" 
            ];

            $this->response($return, $return['status']);
            exit;
        }

        // echo "<pre>"; print_r($_FILES); exit;

		try {
            $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($tmp_name);
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
        if(count($sheetData) < 2):
            $return = [
            	'status' => 400, 
            	'message' => 'Data file harus memiliki 2 Row atau lebih!'
            ];
            $this->response($return, $return['status']);
            exit;
        endif;

        // Maximum Data is 200 row
        if(count($sheetData) > 1001):
            $return = [
            	'status' => 400, 
            	'message' => 'Data anda melebihi 1000 Row! silahkan dibagi menjadi beberapa bagian.'
            ];
            $this->response($return, $return['status']);
            exit;
        endif;

        // echo "<pre>"; print_r($sheetData); exit;

        if (!empty($file['size'])){
        	$headerList = $sheetData[0];
            $trueFormat = array("IdKelBrg", "UraianKelompok", "Tipe", "IdJenisBrg", "NamaJenis", "IdSpesifikasi", "UraianSpesifikasi", "Satuan", "TahunHarga", "Harga");
 
            if (count($headerList) == 10 && count(array_diff($trueFormat, $headerList)) == 0) {
            	$updated_by = decrypt_url($this->data['users']['id']);
				$updated_at = date('Y-m-d H:i:s');

            	for ($i = 1; $i < count($sheetData); $i++){
            		// echo "<pre>"; print_r($sheetData[$i]); exit;
            		for ($j = 0; $j < 10; $j++){
            			if(empty($sheetData[$i][$j])){
            				$return = [
				            	'status' => 400, 
				            	'message' => 'Data pada Row '. $i . ' dan Column '.$sheetData[0][$j].' tidak ada data!'
				            ];
				            $this->response($return, $return['status']);
				            exit;
            			}
            		}

            		// Kelompok Item
            		$where = [
            			'idKelItem' => $sheetData[$i][0]
            		];

            		$cek_kelompok = $this->Spesifikasi_harga_model->cek($where, 'tb_kelompok_item');

            		if($cek_kelompok){
            			if($cek_kelompok->UraianKelompok != $sheetData[$i][1]){
            				if($i == 1){
            					$msg = 'Data pada Row '. $i . ' Column IdKelBrg '. $sheetData[$i][0] . ' sudah digunakan oleh UraianKelompok = '.$cek_kelompok->UraianKelompok.'!';
            				}else{
            					$msg = 'Data pada Row '. $i . ' Column IdKelBrg '. $sheetData[$i][0] . ' sudah digunakan oleh UraianKelompok = '.$cek_kelompok->UraianKelompok.'! Tetapi Data pada row 1 s/d row '.($i-1).' telah berhasil tersimpan.';
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
            		}else{
            			$data_kelompok = [
            				"idKelItem" => $sheetData[$i][0],
            				"UraianKelompok" => $sheetData[$i][1],
            				"tipe" => $sheetData[$i][2],
            				"updated_by" => $updated_by,
            				"updated_at" => $updated_at
            			];

            			$insert_kelompok = $this->Spesifikasi_harga_model->insert_import($data_kelompok, 'tb_kelompok_item');

            			$kodeKelompok = $sheetData[$i][0];
            			$id_kelompok = $insert_kelompok;
            		}

            		// Jenis Item
            		$where = [
            			'idKelompokItem' => $id_kelompok,
            			'idJenisBarang' => $sheetData[$i][3]
            		];

            		$cek_jenis = $this->Spesifikasi_harga_model->cek($where, 'tb_jenis_item');

            		if($cek_jenis){
            			if($cek_jenis->NamaJenis != $sheetData[$i][4]){
            				if($i == 1){
            					$msg = 'Data pada Row '. $i . ' Column IdJenisBrg '. $sheetData[$i][3] . ' sudah digunakan oleh NamaJenis = '.$cek_jenis->NamaJenis.'!';
            				}else{
            					$msg = 'Data pada Row '. $i . ' Column IdKelBrg '. $sheetData[$i][3] . ' sudah digunakan oleh UraianKelompok = '.$cek_jenis->UraianKelompok.'! Tetapi Data pada row 1 s/d row '.($i-1).' telah berhasil tersimpan.';
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
            		}else{

            			$data_jenis = [
            				"idKelompokItem" => $id_kelompok,
            				"idJenisBarang" => $sheetData[$i][3],
            				"kodeKelompok" => $kodeKelompok.'.'.$sheetData[$i][3],
            				"NamaJenis" => $sheetData[$i][4],
            				"updated_by" => $updated_by,
            				"updated_at" => $updated_at
            			];

            			$insert_jenis = $this->Spesifikasi_harga_model->insert_import($data_jenis, 'tb_jenis_item');

            			$kodeKelompok = $kodeKelompok.'.'.$sheetData[$i][3];
            			$id_jenis = $insert_jenis;
            		}
// echo "<pre>"; print_r($sheetData[$i]); exit;

            		// Spesifikasi Item
            		$where = [
            			'idJenisItem' => $id_jenis,
            			'idSpesifikasi' => $sheetData[$i][5]
            		];

            		$cek_spesifikasi = $this->Spesifikasi_harga_model->cek($where, 'tb_spesifikasi_item');

            		if($cek_spesifikasi){
            			if($cek_spesifikasi->UraianSpesifikasi != $sheetData[$i][6]){
            				if($i == 1){
            					$msg = 'Data pada Row '. $i . ' Column idSpesifikasi '. $sheetData[$i][5] . ' sudah digunakan oleh UraianSpesifikasi = '.$cek_spesifikasi->UraianSpesifikasi.'!';
            				}else{
            					$msg = 'Data pada Row '. $i . ' Column idSpesifikasi '. $sheetData[$i][5] . ' sudah digunakan oleh UraianSpesifikasi = '.$cek_spesifikasi->UraianSpesifikasi.'! Tetapi Data pada row 1 s/d row '.($i-1).' telah berhasil tersimpan.';
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
            		}else{

            			$data_jenis = [
            				"idJenisItem" => $id_jenis,
            				"idSpesifikasi" => $sheetData[$i][5],
            				"kodeKelompok" => $kodeKelompok.'.'.$sheetData[$i][5],
            				"UraianSpesifikasi" => $sheetData[$i][6],
            				"satuan" => $sheetData[$i][7],
            				"updated_by" => $updated_by,
            				"updated_at" => $updated_at
            			];

            			$spesifikasi = $this->Spesifikasi_harga_model->insert_import($data_jenis, 'tb_spesifikasi_item');

            			$kodeKelompok = $kodeKelompok.'.'.$sheetData[$i][5];
            			$id_spesifikasi = $spesifikasi;
            		}
// echo "<pre>"; print_r($sheetData[$i]); exit;

            		// Spesifikasi Harga
            		$where = [
            			'idSpesifikasi' => $id_spesifikasi,
            			'TahunHarga' => $sheetData[$i][8]
            		];

            		$cek_harga = $this->Spesifikasi_harga_model->cek($where, 'tb_thn_harga');

            		if($cek_harga){
            			if($cek_harga->harga != $sheetData[$i][9]){
            				if($i == 1){
            					$msg = 'Data pada Row '. $i . ' Column TahunHarga '. $sheetData[$i][8] . ' sudah digunakan dengan Harga = '.$cek_harga->harga.'!';
            				}else{
            					$msg = 'Data pada Row '. $i . ' Column TahunHarga '. $sheetData[$i][8] . ' sudah digunakan dengan Harga = '.$cek_harga->harga.'! Tetapi Data pada row 1 s/d row '.($i-1).' telah berhasil tersimpan.';
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
            		}else{

            			$data_jenis = [
            				"idSpesifikasi" => $id_spesifikasi,
            				"kodeKelompok" => $kodeKelompok,
            				"TahunHarga" => $sheetData[$i][8],
            				"harga" => $sheetData[$i][9],
            				"updated_by" => $updated_by,
            				"updated_at" => $updated_at
            			];

            			$spesifikasi = $this->Spesifikasi_harga_model->insert_import($data_jenis, 'tb_thn_harga');

            			$kodeKelompok = $kodeKelompok.'.'.$sheetData[$i][3];
            			$id_spesifikasi = $spesifikasi;
            		}
            	}

            	$return = [
                    'status'  => 200,
                    'message' => 'Data berhasil tersimpan dengan total row '.(count($sheetData)-1)
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
