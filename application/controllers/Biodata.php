<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('./application/third_party/phpoffice/phpspreadsheet/vendor/autoload.php'); // get data library of phpspreadsheet

// -- configuration of phpofice phpsreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Biodata extends CI_Controller {
	private $_table_BIO = 'tbl_bio';
	public $data = []; // set public data

	function __construct() {
		parent::__construct();
		$this->data['menu_parent'] = '';
		$this->data['menu_child'] = '';

		$this->data['css_outline'] = '';
		$this->data['css_inline'] = '';

		$this->data['js_outline'] = '';
		$this->data['js_inline'] = '';

		$this->load->model('M_Biodata');
		$this->load->model('M_Gender');

		if (Accesscontrol_Helper::Is_Loggin_In() == false) {
			$this->session->set_flashdata('error', 'anda harus login');
			redirect(base_url() . 'main/sign_in'); // redirect to page sign in
		} else {
			return;
		}
	}

	public function index() {
		// set title
		$this->data['title'] = 'Biodata - Index';

		$this->data['menu_parent'] = 'Biodata';
		$this->data['menu_child'] = 'Index';

		$this->data['css_outline'] = '
			<!-- DataTables -->
			<link href="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
			<link href="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

			<!-- Responsive datatable examples -->
			<link href="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		';
		$this->data['css_inline'] = '';

		$this->data['js_outline'] = '
			<!-- Required datatable js -->
			<script src="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/jquery.dataTables.min.js"></script>
			<script src="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/dataTables.bootstrap4.min.js"></script>

			<!-- Responsive examples -->
			<script src="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/dataTables.responsive.min.js"></script>
			<script src="'. base_url('assets/theme/Veltrix_v2.1/') .'plugins/datatables/responsive.bootstrap4.min.js"></script>
		';
		$this->data['js_inline'] = '
			// configuration of datatables
			var tables_bio =$("#datatables_bio").DataTable({
				"processing": false, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables server-side processing mode.
				"order": [], //Initial no order.

				// Load data for the tables content from an Ajax source
				"ajax": {
					"url": "'. base_url('biodata/ajax_list') .'",
					"type": "POST"
				},

				//Set column definition initialisation properties.
				"columnDefs": [
					{
						"targets": [ 0 ], //first column / numbering column
						"orderable": false, //set not orderable
					},
				],
			});

			// auto reload datatables per miliseconds
			setInterval( function () {
				tables_bio.ajax.reload( null, false ); // user paging is not reset on reload
			}, 1000 ); // set interval of miliseconds

			// btn refresh datatables
			$(document).on("click", ".btn-refresh-tbl-bio", function() {
				tables_bio.ajax.reload( null, false ); // user paging is not reset on reload
			});
		';

		// load view dashboard
		$this->load->view('layout/header', $this->data);
		$this->load->view('apps/biodata/index', $this->data);
		$this->load->view('layout/footer', $this->data);
	}

	public function ajax_list() {
		$list = $this->M_Biodata->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $list_value) {
            $no++;
            $row = array();
			$row[] = '
				<div class="text-center">
					<a href="'. base_url('biodata/ex_pdf/'. $list_value->BioUniqueId) .'" target="_blank" class="btn btn-xs btn-primary"><i class="fas fa-file-pdf"></i> Convert PDF</a>
					<a href="'. base_url('biodata/edit/'. $list_value->BioUniqueId) .'" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
					<a href="'. base_url('biodata/delete/'. $list_value->BioUniqueId) .'" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>
				</div>
			';
            $row[] = $no .'.';
            $row[] = $list_value->BioName;
			$row[] = '
				<div class="text-right">
					'. $list_value->BioBirthPlace .', '. $list_value->BioBirthDate .'
				</div>
			';
            $row[] = $list_value->BioEmail;
            $row[] = $list_value->BioPhoneNum;
            $row[] = $list_value->BioReligion;
            $row[] = $list_value->BioNationaly;
            $row[] = $list_value->BioEducation;
            $row[] = $list_value->GenderName;
            $row[] = $list_value->BioStatusMarital;

            $data[] = $row;
        }

        $output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_Biodata->count_all(),
			"recordsFiltered" => $this->M_Biodata->count_filtered(),
			"data" => $data,
		);

        //output to json format
        echo json_encode($output);
	}

	public function create() {
		// set title
		$this->data['title'] = 'Biodata - Add New Data';

		$this->data['menu_parent'] = 'Biodata';
		$this->data['menu_child'] = 'Add';

		$this->data['css_outline'] = '
			<!-- DataTables -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

			<!-- Bootstrap Validator  -->
			<link rel="stylesheet" href="'. base_url() .'assets/plugin/more/bootstrapValidator_v0.5.0/dist/css/bootstrapValidator.min.css">

			<!-- Bootstrap Datepicker -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

			<!-- Select2 -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/select2/dist/css/select2.min.css">

			<!-- Bootstrap Tag -->
			<link rel="stylesheet" href="'. base_url('assets/plugin/more/bootstrap-tagsinput-v0.8.0/') .'dist/bootstrap-tagsinput.css">
		';
		$this->data['css_inline'] = '
			.bootstrap-tagsinput { width: 100%; }
		';

		$this->data['js_outline'] = '
			<!-- DataTables -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

			<!-- Bootstrap Validator -->
			<script src="'. base_url() .'assets/plugin/more/bootstrapValidator_v0.5.0/dist/js/bootstrapValidator.min.js"></script>

			<!-- Bootstrap Datepicker -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

			<!-- Select2 -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/select2/dist/js/select2.full.min.js"></script>

			<!-- Bootstrap Tag -->
			<script src="'. base_url('assets/plugin/more/bootstrap-tagsinput-v0.8.0/') .'dist/bootstrap-tagsinput.min.js"></script>

			<!-- Typeahead -->
			<script src="'. base_url('assets/plugin/more/typeahead.js-v0.11.1/') .'dist/typeahead.bundle.min.js"></script>

			<!-- Asset JS -->
			<script src="'. base_url('assets/js/') .'app.js"></script>
		';
		$this->data['js_inline'] = '
			//Date picker
			$(".datepicker").datepicker({
				autoclose: true
			});

			//Initialize Select2 Elements
			$(".select2").select2({
				allowClear: true,
				width: "100%",
			});

			//BootstrapValidator
			$("#form-add-new-bio").bootstrapValidator();
		';

		// get data gender
		$get_all_gender = $this->M_Gender->get_all();

		$this->data['rslt_gender'] = $get_all_gender;

		// load view dashboard
		$this->load->view('layout/header', $this->data);
		$this->load->view('layout/sidebar', $this->data);
		$this->load->view('apps/biodata/create', $this->data);
		$this->load->view('layout/footer', $this->data);
	}

	public function create_process() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->load->helper('security');

			$clean = $this->security->xss_clean($this->input->post()); // clean xss

			// validation data clean xss
			$clean['BioSkill'] = str_replace(',', ', ', $clean['BioSkill']);
			$clean['BioSkill'] = ucwords($clean['BioSkill']);

			$clean['BioLanguage'] = str_replace(',', ', ', $clean['BioLanguage']);
			$clean['BioLanguage'] = ucwords($clean['BioLanguage']);

			$clean['BioExperince'] = str_replace(',', ', ', $clean['BioExperince']);
			$clean['BioExperince'] = ucwords($clean['BioExperince']);

			$clean['BioHobby'] = str_replace(',', ', ', $clean['BioHobby']);
			$clean['BioHobby'] = ucwords($clean['BioHobby']);

			$clean['BioQuote'] = str_replace(',', ', ', $clean['BioQuote']);
			$clean['BioQuote'] = ucwords($clean['BioQuote']);

			$clean['BioEducation'] = implode(',', $clean['BioEducation']);;


			$clean['BioName'] = ucwords($clean['BioName']);
			$clean['BioBirthPlace'] = ucfirst($clean['BioBirthPlace']);
			$clean['BioAddress'] = ucfirst($clean['BioAddress']);
			$clean['BioAddressCurrent'] = ucfirst($clean['BioAddressCurrent']);

			$arry = [
				'BioUniqueId' => Accesscontrol_Helper::UniqIdReal(),
				'BioStatus' => 1,
				'BioCreated' => date('Y-m-d H:i:s'),
				'BioCreatedId' => $this->session->userdata('UsrId'),
			];

			$merge = array_merge($clean, $arry);

			$insert = $this->M_Biodata->create($merge);

			if ($insert > 0) {
				$this->session->set_flashdata('success', 'Berhasil menambahkan Biodata');
				redirect(base_url('biodata/create'));
			} else {
				$this->session->set_flashdata('error', 'Gagal menambahkan Biodata');
				redirect(base_url('biodata/create'));
			}

			// check value from form
			// echo '<pre>';
			// print_r($merge);
			// echo '</pre>';
		} else {
			redirect(base_url('biodata/index'));
		}
	}

	public function edit($uniqueId = null) {
		if (!isset($uniqueId)) redirect('biodata/index'); // if uniqueid null, go to index

		// set title
		$this->data['title'] = 'Biodata - Edit New Data';

		$this->data['menu_parent'] = 'Biodata';
		$this->data['menu_child'] = 'Edit';

		$this->data['css_outline'] = '
			<!-- DataTables -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

			<!-- Bootstrap Validator  -->
			<link rel="stylesheet" href="'. base_url() .'assets/plugin/more/bootstrapValidator_v0.5.0/dist/css/bootstrapValidator.min.css">

			<!-- Bootstrap Datepicker -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

			<!-- Select2 -->
			<link rel="stylesheet" href="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/select2/dist/css/select2.min.css">

			<!-- Bootstrap Tag -->
			<link rel="stylesheet" href="'. base_url('assets/plugin/more/bootstrap-tagsinput-v0.8.0/') .'dist/bootstrap-tagsinput.css">
		';
		$this->data['css_inline'] = '
			.bootstrap-tagsinput { width: 100%; }
		';

		$this->data['js_outline'] = '
			<!-- DataTables -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

			<!-- Bootstrap Validator -->
			<script src="'. base_url() .'assets/plugin/more/bootstrapValidator_v0.5.0/dist/js/bootstrapValidator.min.js"></script>

			<!-- Bootstrap Datepicker -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

			<!-- Select2 -->
			<script src="'. base_url('assets/theme/AdminLTE-v.2.4/') .'bower_components/select2/dist/js/select2.full.min.js"></script>

			<!-- Bootstrap Tag -->
			<script src="'. base_url('assets/plugin/more/bootstrap-tagsinput-v0.8.0/') .'dist/bootstrap-tagsinput.min.js"></script>

			<!-- Typeahead -->
			<script src="'. base_url('assets/plugin/more/typeahead.js-v0.11.1/') .'dist/typeahead.bundle.min.js"></script>

			<!-- Asset JS -->
			<script src="'. base_url('assets/js/') .'app.js"></script>
		';
		$this->data['js_inline'] = '
			//Date picker
			$(".datepicker").datepicker({
				autoclose: true
			});

			//Initialize Select2 Elements
			$(".select2").select2({
				allowClear: true,
				width: "100%",
			});

			//BootstrapValidator
			$("#form-add-new-bio").bootstrapValidator();
		';

		// get data gender
		$get_all_gender = $this->M_Gender->get_all();

		$this->data['rslt_gender'] = $get_all_gender;

		$get_bio = $this->M_Biodata->get_by_uniqueid(
			[
				'BioUniqueId' => $uniqueId,
			]
		); // get data bio

		if (!isset($get_bio)) redirect('biodata/index'); // if data null, go to index

		$this->data['rslt_bio'] = $get_bio; // set data bio to view

		// load view dashboard
		$this->load->view('layout/header', $this->data);
		$this->load->view('layout/sidebar', $this->data);
		$this->load->view('apps/biodata/edit', $this->data);
		$this->load->view('layout/footer', $this->data);
	}

	public function update() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->load->helper('security');

			$clean = $this->security->xss_clean($this->input->post()); // clean xss

			// validation data clean xss
			$clean['BioSkill'] = str_replace(',', ', ', $clean['BioSkill']);
			$clean['BioSkill'] = ucwords($clean['BioSkill']);

			$clean['BioLanguage'] = str_replace(',', ', ', $clean['BioLanguage']);
			$clean['BioLanguage'] = ucwords($clean['BioLanguage']);

			$clean['BioExperince'] = str_replace(',', ', ', $clean['BioExperince']);
			$clean['BioExperince'] = ucwords($clean['BioExperince']);

			$clean['BioHobby'] = str_replace(',', ', ', $clean['BioHobby']);
			$clean['BioHobby'] = ucwords($clean['BioHobby']);

			$clean['BioQuote'] = str_replace(',', ', ', $clean['BioQuote']);
			$clean['BioQuote'] = ucwords($clean['BioQuote']);

			$clean['BioEducation'] = implode(',', $clean['BioEducation']);;


			$clean['BioName'] = ucwords($clean['BioName']);
			$clean['BioBirthPlace'] = ucfirst($clean['BioBirthPlace']);
			$clean['BioAddress'] = ucfirst($clean['BioAddress']);
			$clean['BioAddressCurrent'] = ucfirst($clean['BioAddressCurrent']);

			$get_bio = $this->M_Biodata->get_by_uniqueid([ 'BioUniqueId' => $clean['BioUniqueId'] ]);

			if (!isset($get_bio->BioModify) || !isset($get_bio->BioModifyId)) {
				$Modify = date('Y-m-d H:i:s');
				$ModifyId = $this->session->userdata('UsrId');
			} else {
				$Modify = $get_bio->BioModify .','. date('Y-m-d H:i:s');
				$ModifyId = $get_bio->BioModifyId .','. $this->session->userdata('UsrId');
			}

			$arry = [
				'BioModify' => $Modify,
				'BioModifyId' => $ModifyId,
			];

			$merge = array_merge($clean, $arry);

			$update = $this->M_Biodata->update($merge);

			if ($update > 0) {
				$this->session->set_flashdata('success', 'Berhasil update Data Biodata');
				redirect(base_url('biodata/edit/'. $clean['BioUniqueId']));
			} else {
				$this->session->set_flashdata('error', 'Gagal update Data Biodata');
				redirect(base_url('biodata/edit/'. $clean['BioUniqueId']));
			}

			// check value from form
			// echo '<pre>';
			// print_r($merge);
			// echo '</pre>';
		} else {
			redirect(base_url('biodata/index'));
		}
	}

	public function delete($uniqueId = null) {
		if (!isset($uniqueId)) redirect('biodata/index'); // if uniqueid null, go to index

		$get_bio = $this->M_Biodata->get_by_uniqueid(
			[
				'BioUniqueId' => $uniqueId,
			]
		); // get data bio

		if (!isset($get_bio)) redirect('biodata/index'); // if data null, go to index

		// process delete data
		$this->db->delete($this->_table_BIO,
			[
				'BioUniqueId' => $uniqueId,
			]
		);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Berhasil delete Data Biodata');
			redirect(base_url('biodata/index'));
		} else {
			$this->session->set_flashdata('error', 'Gagal delete Data Biodata');
			redirect(base_url('biodata/index'));
		}
	}

	// export to pdf with library fpdf
	public function ex_pdf($uniqueId = null) {
		ob_start(); // start content

		$get_bio = $this->M_Biodata->get_by_uniqueid(['BioUniqueId' => $uniqueId]); // get data bio by uniqueId

		$this->load->library('PDF'); // load pdf library

		$fpdf = new FPDF('L', 'mm', 'A4'); // config sheet of pdf

		$fpdf->SetAutoPageBreak(false); // set of page break (auto new page). true -> to new page. false -> all in one page

		$fpdf->AddPage(); // make new page. page 1

		$fpdf->SetFont('Times', 'B', 16); // set font
		$fpdf->Cell(100, 10, 'here is the text', 1, 1); // set column. max width = 279

		$fpdf->SetFont('Times', '', 16); // set font
		$fpdf->setFillColor(119, 235, 52);
		$fpdf->Cell(100, 10, 'here is the text', 1, 0, 'R', 1);

		$fpdf->Output('I', 'Biodata.pdf'); // set the output of pdf. view/download/document type
	}

	// export to excel with library phpspreadsheet
	public function ex_excel() {
		$rslt_bio = $this->M_Biodata->get_all_data(); // geta all of data biodata

		$spreadsheet = new Spreadsheet(); // dec var of spreadsheet

		$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri'); // set font
		$spreadsheet->getDefaultStyle()->getFont()->setSize(12); // set size of font

		// -- config of columns
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
			->setCellValue('B1', 'UniqueId')
			->setCellValue('C1', 'Nama')
			->setCellValue('D1', 'Tempat_Tanggal_Lahir')
			->setCellValue('E1', 'Email')
			->setCellValue('F1', 'No_Telp')
			->setCellValue('G1', 'Agama')
			->setCellValue('H1', 'Kebangsaan')
			->setCellValue('I1', 'Pendidikan')
			->setCellValue('J1', 'Jenis_Kelamin')
			->setCellValue('K1', 'Status');

		$spreadsheet->getActiveSheet()->setAutoFilter('A1:K1'); // make filter in excel data
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal('center'); // set align in column
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true); // set strong of text
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK); // make style border in column

		// -- config auto size
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

		$rowIndex = 2;
		$number = 1;

		foreach($rslt_bio as $rslt_data):
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'. $rowIndex, $number)
				->setCellValue('B'. $rowIndex, $rslt_data->BioUniqueId)
				->setCellValue('C'. $rowIndex, $rslt_data->BioName)
				->setCellValue('D'. $rowIndex, $rslt_data->BioBirthPlace .', '. $rslt_data->BioBirthDate)
				->setCellValue('E'. $rowIndex, $rslt_data->BioEmail)
				->setCellValue('F'. $rowIndex, $rslt_data->BioPhoneNum)
				->setCellValue('G'. $rowIndex, $rslt_data->BioReligion)
				->setCellValue('H'. $rowIndex, $rslt_data->BioNationaly)
				->setCellValue('I'. $rowIndex, $rslt_data->BioEducation)
				->setCellValue('J'. $rowIndex, $rslt_data->BioGender)
				->setCellValue('K'. $rowIndex, $rslt_data->BioStatusMarital);

			$spreadsheet->getActiveSheet()->getCell('A' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('B' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('C' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('D' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('E' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('F' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('G' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('H' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('I' . $rowIndex)->getStyle()->setQuotePrefix(true);
			$spreadsheet->getActiveSheet()->getCell('J' . $rowIndex)->getStyle()->setQuotePrefix(true);

			$spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15.75);

			$rowIndex++;
			$number++;
		endforeach;

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// header('Content-Disposition: attachment;filename="EmployeeList-'.Accesscontrol_Helper::UniqIdReal().'.xlsx"');
		header('Content-Disposition: attachment;filename="Biodata-' . date('dmY') . '.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		ob_end_clean();
		$writer->save('php://output');
		exit;


		// echo '<pre>';
		// foreach ($rslt_bio as $rslt_data) {
		// 	print_r($rslt_data);
		// }
		// echo '</pre>';
	}
}