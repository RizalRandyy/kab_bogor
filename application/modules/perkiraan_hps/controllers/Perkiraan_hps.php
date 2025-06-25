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
		$this->data['title'] = ucfirst($segment).' SIMULASI PERKIRAAN HSP';
		$this->data['page'] = 'perkiraan_hps';
		$this->data['version'] = $this->uri->segment(1);
		$this->data['id'] = @$this->get('id')?:null;

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

	public function saveData_get()
	{
		$params = $this->get(NULL, TRUE);

		$tahunPekerjaan = explode(",",$this->get('id_thn_harga', TRUE));
		$harga_satuan = explode(",",$this->get('harga_satuan', TRUE));
		$total_item = explode(",",$this->get('total_item', TRUE));
        $percent = $this->get('percent', TRUE);
		$total_harga = [];

		foreach ($tahunPekerjaan as $key => $value) {
			$tahunPekerjaan[$key] = $this->Perkiraan_hps_model->getReqById($value);
			$total_harga[$key] = ($harga_satuan[$key] * $total_item[$key]);
		}

        $total_percent = (array_sum($total_harga)/100)*$percent;
        $total_all = array_sum($total_harga)+$total_percent;

		$params['id_thn_harga'] = $tahunPekerjaan;
		$params['harga_satuan'] = $harga_satuan;
		$params['total_item'] = $total_item;
		$params['total_harga'] = $total_harga;
        $params['jumlah_total'] = array_sum($total_harga);
        $params['percent'] = $percent;
        $params['total_percent'] = $total_percent;
		$params['total_all'] = $total_all;

		$params['updated_by'] = $this->data['users']['full_name'];
		$params['date'] = date('Y-m-d H:i:s');
		$result['data'] = $params;

        $this->load->library('pdf');

        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->set_option('isRemoteEnabled', TRUE);
        $this->pdf->filename = "Simulasi Perkiraan HSP Kabupaten Bogor.pdf";
        $this->pdf->attachment = true;
        $this->pdf->load_view('report/perkiraan_hps', $result);
	}
}
