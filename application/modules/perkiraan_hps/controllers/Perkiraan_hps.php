<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require('././assets/plugins/SpreadsheetExcel/vendor/autoload.php');
class Perkiraan_hps extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Perkiraan_hps_model');
	}

	public function index_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' SIMULASI PERKIRAAN HSP';
		$this->data['page'] = 'perkiraan_hps';
		$this->data['version'] = $this->uri->segment(1);
		$this->data['id'] = @$this->get('id') ?: null;

		$this->data['js'] = array(
			'assets/js/app/perkiraan_hps.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/inputtags/js/bootstrap-tagsinput.js',
			'assets/plugins/select2/dist/js/select2.min.js'
		);

		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/inputtags/css/bootstrap-tagsinput.css',
			'assets/plugins/select2/dist/css/select2.css'
		];

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'form', null, 'frontend');
	}

	public function sshByHspk_get()
	{
		$return = $this->Perkiraan_hps_model->getSshByHspk($this->get('id', TRUE));

		$this->response($return, 200);
	}

	public function getDetailByKegiatan_get()
	{
		$id = $this->get('id', TRUE);
		$data = $this->Perkiraan_hps_model->getDetailByKegiatan($id);
		$this->response($data, 200);
	}

	public function asb_get()
	{
		$return = $this->Perkiraan_hps_model->getAsb();

		$this->response($return, 200);
	}

	public function getAsbById_get()
	{
		$return = $this->Perkiraan_hps_model->getReqAsbById($this->get('id', TRUE));

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function getById_get()
	{
		$return = $this->Perkiraan_hps_model->getReqById($this->get('id', TRUE));

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function getSshById_get()
	{
		$return = $this->Perkiraan_hps_model->getSshById($this->get('id', TRUE));

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function downloadExcel()
	{
		$params = $this->get(NULL, TRUE);

		$idKegiatan = $this->get('kegiatan', TRUE);
		$id_thn_harga = explode(",", $this->get('id_thn_harga', TRUE));
		$harga_satuan = explode(",", $this->get('harga_satuan', TRUE));
		$total_item = explode(",", $this->get('total_item', TRUE));
		$percent = $this->get('percent', TRUE);

		$detailItems = [];
		$total_harga = [];

		foreach ($id_thn_harga as $i => $idHarga) {

			$item = $this->Perkiraan_hps_model->getHargaById($idHarga);
			$detailItems[] = $item;

			$total_harga[$i] = floatval($harga_satuan[$i]) * floatval($total_item[$i]);
		}

		$kegiatan = $this->db
			->select("kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan")
			->join("tb_kegiatan", "tb_kegiatan.id = tb_thn_kegiatan.idKegiatan")
			->where("tb_thn_kegiatan.id", $idKegiatan)
			->get("tb_thn_kegiatan")->row();

		$judul =
			$kegiatan->kodeKelompok . " - " .
			$kegiatan->UraianKegiatan . " (" . $kegiatan->satuan . ") - " .
			$kegiatan->tahunPekerjaan;

		$jumlah_total = array_sum($total_harga);
		$total_percent = ($jumlah_total * $percent) / 100;
		$total_all = $jumlah_total + $total_percent;

		if (ob_get_length()) ob_end_clean();

		error_reporting(0);
		ini_set('display_errors', 0);
		require FCPATH . 'assets/plugins/SpreadsheetExcel/vendor/autoload.php';

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', "SIMULASI PERKIRAAN HPS");
		$sheet->mergeCells('A1:F1');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

		$sheet->setCellValue('A2', $judul);
		$sheet->mergeCells('A2:F2');
		$sheet->getStyle('A2')->getFont()->setBold(true);
		$sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

		$sheet->setCellValue('A4', 'No');
		$sheet->setCellValue('B4', 'Uraian');
		$sheet->setCellValue('C4', 'Sat');
		$sheet->setCellValue('D4', 'Volume');
		$sheet->setCellValue('E4', 'Harga Satuan');
		$sheet->setCellValue('F4', 'Jumlah Harga');

		$sheet->getStyle('A4:F4')->getFont()->setBold(true);
		$sheet->getStyle('A4:F4')->getAlignment()->setHorizontal('center');
		$sheet->getStyle('A4:F4')->getBorders()->getAllBorders()->setBorderStyle('thin');

		$row = 5;
		foreach ($detailItems as $i => $item) {

			$sheet->setCellValue("A$row", $i + 1);
			$sheet->setCellValue("B$row", $item->UraianSpesifikasi);
			$sheet->setCellValue("C$row", $item->satuan);
			$sheet->setCellValue("D$row", $total_item[$i]);
			$sheet->setCellValue("E$row", $harga_satuan[$i]);
			$sheet->setCellValue("F$row", $total_harga[$i]);

			$sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle('thin');

			$row++;
		}

		$sheet->setCellValue("E$row", "Jumlah");
		$sheet->setCellValue("F$row", $jumlah_total);
		$sheet->getStyle("E$row")->getFont()->setBold(true);

		$row++;

		$sheet->setCellValue("E$row", "Biaya Umum + Profit ($percent%)");
		$sheet->setCellValue("F$row", $total_percent);

		$row++;

		$sheet->setCellValue("E$row", "TOTAL");
		$sheet->setCellValue("F$row", $total_all);
		$sheet->getStyle("E$row")->getFont()->setBold(true);

		// Format angka ribuan
		$sheet->getStyle("D5:F$row")
			->getNumberFormat()
			->setFormatCode('#,##0');

		// Auto width
		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// ===================
		// OUTPUT FILE
		// ===================
		$filename = "Simulasi-HPS.xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Simulasi-HPS.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

		// Wajib:
		if (ob_get_length()) ob_end_clean();

		$writer->save('php://output');
		exit;
	}
}
