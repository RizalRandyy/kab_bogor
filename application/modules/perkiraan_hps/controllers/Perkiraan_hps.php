<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

	public function kegiatan_get()
	{
		$return = $this->Perkiraan_hps_model->getkegiatan();

		$this->response($return, 200);
	}

	public function getDetailByKegiatan_get()
	{
		$id = $this->get('id', TRUE);
		$data = $this->Perkiraan_hps_model->getDetailByKegiatan($id);
		$this->response($data, 200);
	}

	public function saveData_get()
	{
		$params = $this->get(NULL, TRUE);

		$idKegiatan = $this->get('kegiatan', TRUE);
		$id_thn_harga = explode(",", $this->get('id_thn_harga', TRUE));
		$harga_satuan = explode(",", $this->get('harga_satuan', TRUE));
		$total_item = explode(",", $this->get('total_item', TRUE));
		$percent = $this->get('percent', TRUE);

		$detailItems = [];
		$total_harga = [];

		foreach ($id_thn_harga as $key => $idHarga) {

			$item = $this->Perkiraan_hps_model->getHargaById($idHarga);
			$detailItems[$key] = $item;

			$total_harga[$key] = $harga_satuan[$key] * $total_item[$key];
		}

		$kegiatan = $this->db->select("kodeKelompok,UraianKegiatan,satuan,tahunPekerjaan")
			->join("tb_kegiatan", "tb_kegiatan.id = tb_thn_kegiatan.idKegiatan")
			->where("tb_thn_kegiatan.id", $idKegiatan)
			->get("tb_thn_kegiatan")->row();

		$kegiatan_text =
			$kegiatan->kodeKelompok . ' - ' .
			$kegiatan->UraianKegiatan . ' - (' .
			$kegiatan->satuan . ') - ' .
			$kegiatan->tahunPekerjaan;

		$total_percent = (array_sum($total_harga) / 100) * $percent;
		$total_all = array_sum($total_harga) + $total_percent;

		$result['data'] = [
			'kegiatan_text' => $kegiatan_text,
			'id_thn_harga' => $detailItems,
			'harga_satuan' => $harga_satuan,
			'total_item' => $total_item,
			'total_harga' => $total_harga,
			'jumlah_total' => array_sum($total_harga),
			'percent' => $percent,
			'total_percent' => $total_percent,
			'total_all' => $total_all,
			'updated_by' => $this->data['users']['full_name']
		];

		$logoPath = FCPATH . 'assets/img/logo-pemkab-bogor.png';
		$logoData = base64_encode(file_get_contents($logoPath));
		$mime = mime_content_type($logoPath);

		$result['data']['logo'] = "data:$mime;base64,$logoData";

		$this->load->library('pdf');
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->set_option('isRemoteEnabled', TRUE);
		$this->pdf->filename  = "Simulasi Perkiraan HSP Kabupaten Bogor.pdf";
		$this->pdf->attachment = true;
		$this->pdf->load_view('report/perkiraan_hps', $result);
	}
}
