<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public $data = []; // set public data

	function __construct()
	{
		parent::__construct();
		$this->data['menu_parent'] = '';
		$this->data['menu_child'] = '';

		$this->data['css_outline'] = '';
		$this->data['css_inline'] = '';

		$this->data['js_outline'] = '';
		$this->data['js_inline'] = '';

		$this->load->model('M_Biodata');
		$this->load->model('M_Main');
		$this->load->model('M_Gender');

		if (Accesscontrol_Helper::Is_Loggin_In() == false) {
			$this->session->set_flashdata('error', 'anda harus login');
			redirect(base_url() . 'main/sign_in'); // redirect to page sign in
		} else {
			return;
		}
	}

	public function index()
	{
		// set title
		$this->data['title'] = 'Dashboard';

		$this->data['menu_parent'] = 'Dashboard';
		$this->data['menu_child'] = 'Index';

		$this->data['total_biodata'] = $this->M_Biodata->count_all();
		$this->data['total_login_activity'] = $this->M_Activity->Count_LAL();
		$this->data['total_user'] = $this->M_Main->count_all();
		$this->data['total_user_login'] = $this->M_Main->count_selected($this->session->userdata('UsrId'));

		$this->data['education_sd'] = $this->M_Biodata->get_where_by('SD');
		$this->data['education_smp'] = $this->M_Biodata->get_where_by('SMP');
		$this->data['education_smasmk'] = $this->M_Biodata->get_where_by('SMA/SMK');
		$this->data['education_d3'] = $this->M_Biodata->get_where_by('D3');
		$this->data['education_s1'] = $this->M_Biodata->get_where_by('S1');
		$this->data['education_s2'] = $this->M_Biodata->get_where_by('S2');
		$this->data['education_s3'] = $this->M_Biodata->get_where_by('S3');


		$this->data['css_outline'] = '
			<!--Chartist Chart CSS -->
			<link rel="stylesheet" href="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/chartist/css/chartist.min.css">
		';
		$this->data['css_inline'] = '';


		$this->data['js_outline'] = '
			<!--Chartist Chart-->
			<script src="'. base_url('assets/theme/Veltrix_v2.1/'). 'plugins/chartist/js/chartist.min.js"></script>
			<script src="'. base_url('assets/theme/Veltrix_v2.1/'). 'plugins/chartist/js/chartist-plugin-tooltip.min.js"></script>

			<!-- peity JS -->
			<script src="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/peity-chart/jquery.peity.min.js"></script>

			<script src="'. base_url('assets/theme/Veltrix_v2.1/horizontal/') .'assets/pages/dashboard.js"></script>
		';
		$this->data['js_inline'] = '
			new Chartist.Line("#chart-with-area-pendidikan", {
				labels: ["SD", "SMP", "SMA/SMK", "D3", "S1", "S2", "S3"],
				series: [
					['.$this->data['education_sd'].', '.$this->data['education_smp'].', '.$this->data['education_smasmk'].', '.$this->data['education_d3'].', '.$this->data['education_s1'].', '.$this->data['education_s2'].', '.$this->data['education_s3'].']
				]
				}, {
				low: 0,
				showArea: true,
				plugins: [
					Chartist.plugins.tooltip()
				]
			});
		';

		// load view dashboard
		$this->load->view('layout/header', $this->data);
		$this->load->view('dashboard/dashboard', $this->data);
		$this->load->view('layout/footer', $this->data);
	}

	public function index2()
	{
		// set title
		$this->data['title'] = 'Dashboard2';

		$this->data['menu_parent'] = 'Dashboard';
		$this->data['menu_child'] = 'Index';

		// load view dashboard
		$this->load->view('layout/header', $this->data);
		$this->load->view('dashboard/dashboard', $this->data);
		$this->load->view('layout/footer', $this->data);
	}
}
