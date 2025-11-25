<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Landing_page extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->load->model('Kegiatan_hspk_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Landing Page';
		$this->data['page'] = 'landing_page';
		$this->data['version'] = 'landing_page';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/css/carousel.css',
		];

		$this->data['js'] = array(
			'assets/js/app/landing_page.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index', null, 'frontend');
	}
}
