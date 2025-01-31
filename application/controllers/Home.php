<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		/**
		* 언어셋 설정
		*/
		$this->load->model("adm_model");
		$this->load->helper("string");
	}

	public function index()
	{
		$this->intro();

	}

	public function intro()
	{
		$total_num = $this->adm_model->getTotalWorkCount();

		$data = array(
			"total_num"	=>	number_format($total_num)
		);
		$this->parser->parse('intro',$data);
	}

	public function clientDbProc()
	{
		$c_name = $this->input->post("c_name");
		$c_email = $this->input->post("c_email");
		$c_phone = $this->input->post("c_phone");
		$c_text = $this->input->post("c_text");
		$reg_date = date("Y-m-d H:i:s");

		$data = array(
			"c_name"	=>	$c_name,
			"c_email"	=>	$c_email,
			"c_phone"	=>	$c_phone,
			"c_text"	=>	$c_text,
			"reg_date"	=>	$reg_date,
		);

		$this->adm_model->insertClientDb($data);

		echo '{"result":"success"}';
		exit;
	}





}
