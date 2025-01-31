<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors','0');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// $this->load->model("board_model");
		// $this->load->model("member_model");
		// $this->load->model("academi_model");
		// $this->load->model("content_model");

		$this->load->library('excel');

		$this->load->helper('load_controller');
		$uri = explode("/",uri_string());
		// login Check
    if( !$this->session->userdata("admin_id") ){
      if( $uri[count($uri)-1] != "login" && $uri[count($uri)-1] != "login_proc" ){
        //$this->msg("로그인 해주시기 바랍니다.");
        $this->goURL(base_url("admin/login"));
        exit;
      }
		}


	}

	public function index()
	{
		//login page redirect
		if( !$this->session->userdata("admin_id") ){
			$this->login();
		}else{
			if( $this->session->userdata("admin_level") == 1){
				//$this->goURL("/admin/main");
				$this->goURL("/admin/home/workList");
			}else{
				$this->goURL("/admin/home/workList");
			}

		}

	}

	public function clientDbList()
	{
		$depth1 = "admin";
		$depth2 = "db";
		$title = "협력제안리스트";
		$sub_title = "협력제안리스트";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$where = "";

		$page_size = 10;
		$page_list_size = 10;
		$whereData = array(
			"where"			=>	$where,
		);
		$list_total = $this->adm_model->getClientDbTotalCount($where);

		if( $list_total <= 0 )
		{
			$list_total = 0;
		}

		$total_page = ceil( $list_total / $page_size );

		$params = "";

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	$where,
			"limit"			=>	"LIMIT ".$num.",".$page_size
		);

		$clientDbList = $this->adm_model->getClientDbList($whereData);

		//넘버링
		$current_page = ceil ( ($num + 1) / $page_size );

		$start_page = floor ( ($current_page - 1) / $page_list_size ) * $page_list_size + 1;
		$end_page = $start_page + $page_list_size - 1;

		if ($total_page < $end_page)
		{
				$end_page = $total_page;
		}

		$prev_list = ($num-$page_size > 0 ) ? $num-$page_size:0;
		$next_list = ($num+$page_size < ($total_page-1)*$page_size) ? $num+$page_size:($total_page-1)*$page_size;
		//넘버링 끝
		$clientDbList = $this->add_counting($clientDbList,$list_total,$num);

		$paging = $this->make_paging2("/admin/home/clientDbList",$start_page,$end_page,$page_size,$num,$srcN,$total_page,$params);

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		//customSetting
		for($i = 0; $i < count($clientDbList); $i++)
		{
			$clientDbList[$i]['reg_date'] = date("Y-m-d",strtotime($clientDbList[$i]['reg_date']));
		}

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"clientDbList"	=>	$clientDbList,
			"paging"		=>	$paging,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"num"				=>	$num,
			"total_client_db"	=>	number_format($list_total)
		);




		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/client-db-list",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function main()
	{
		$depth1 = "admin";
		$depth2 = "home";
		$title = "HOME";
		$sub_title = "대쉬보드";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$datas = $this->adm_model->getTotalPrice();

		$content_data = array(
			"title"	=>	$title,
			"sub_title"	=>	$sub_title,
			"datas"	=>	$datas
		);



		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);


		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/main",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function companyMody()
	{
		$depth1 = "admin";
		$depth2 = "company";
		$title = "본사 관리";
		$sub_title = "본사 관리";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$companyData = $this->adm_model->getCompany();

		$companyData['title'] = $title;

		$content_data = $companyData;

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/company-mody",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function companyModyProc()
	{
		$company_name = $this->input->post("company_name");
		$business_no = $this->input->post("business_no");
		$business_1 = $this->input->post("business_1");
		$business_2 = $this->input->post("business_2");
		$owner_name = $this->input->post("owner_name");
		$phone = $this->input->post("phone");
		$fax = $this->input->post("fax");
		$bank = $this->input->post("bank");
		$zipcode = $this->input->post("zipcode");
		$addr_1 = $this->input->post("addr_1");
		$addr_2 = $this->input->post("addr_2");

		$data = array(
			"company_name"	=>	$company_name,
			"business_no"	=>	$business_no,
			"business_1"	=>	$business_1,
			"business_2"	=>	$business_2,
			"owner_name"	=>	$owner_name,
			"phone"	=>	$phone,
			"fax"	=>	$fax,
			"bank"	=>	$bank,
			"zipcode"	=>	$zipcode,
			"addr_1"	=>	$addr_1,
			"addr_2"	=>	$addr_2,
		);

		$result = $this->adm_model->modifyCompany($data);

		if($result){
				echo '{"result":"success"}';
		}else{
			echo '{"result":"failed"}';
		}


		exit;
	}

	//login
	public function login()
	{
		$content_data = array(
			"base_url"	=>	$this->BASE_URL
		);

		$this->parser->parse("admin/login",$content_data);
	}

	//login ajax
	public function login_proc()
	{
		$admin_id = $this->input->post("admin_id");
		$admin_password = $this->input->post("admin_password");

		if( empty($admin_id) ){
			echo '{ "result" : "failed" , "message" : "아이디를 입력 해 주세요." }';
			exit;
		}

		if( empty($admin_password) ){
			echo '{ "result" : "failed" , "message" : "비밀번호를 입력 해 주세요." }';
			exit;
		}

		//db id check
		$result = $this->adm_model->login($admin_id,$admin_password);

		if( $result["result"] == "success" ){

			//session에 저장
			$this->session->set_userdata("admin_id" , $admin_id);
			$this->session->set_userdata("admin_level",$result['admin_level']);


			$value = $this->security->get_csrf_hash();

			echo '{ "result" : "success", "csrf" : "'.$value.'"}';
			exit;
		} else {
			$message = "";

			switch($result["message"]){
				case "admin_id" :
					$message = "아이디를 확인 해 주세요.";
				break;

				case "admin_password":
					$message = "비밀번호를 확인 해 주세요.";
				break;
			}

			echo '{ "result" : "failed" , "message" : "'.$message.'" }';
			exit;
		}
	}

	//업체리스트
	public function clientList()
	{
		$depth1 = "admin";
		$depth2 = "client";
		$title = "업체리스트";
		$sub_title = "업체리스트";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$where = "";

		if(!empty($srcN)){
			if($srcType=="name"){
				$where .= "AND client_name LIKE '%{$srcN}%'";
			}else if($srcType=="business_no"){
				$where .= "AND business_no LIKE '%{$srcN}%'";
			}else{
				$where .= "AND client_name LIKE '%{$srcN}%' OR client_name LIKE '%{$srcN}%'";
			}
		}

		$page_size = 10;
		$page_list_size = 10;
		$whereData = array(
			"where"			=>	$where,
		);
		$list_total = $this->adm_model->getClientTotalCount($where);

		if( $list_total <= 0 )
		{
			$list_total = 0;
		}

		$total_page = ceil( $list_total / $page_size );

		$params = "";

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	$where,
			"limit"			=>	"LIMIT ".$num.",".$page_size
		);

		$clientList = $this->adm_model->getClientList($whereData);

		//넘버링
		$current_page = ceil ( ($num + 1) / $page_size );

		$start_page = floor ( ($current_page - 1) / $page_list_size ) * $page_list_size + 1;
		$end_page = $start_page + $page_list_size - 1;

		if ($total_page < $end_page)
		{
				$end_page = $total_page;
		}

		$prev_list = ($num-$page_size > 0 ) ? $num-$page_size:0;
		$next_list = ($num+$page_size < ($total_page-1)*$page_size) ? $num+$page_size:($total_page-1)*$page_size;
		//넘버링 끝
		$clientList = $this->add_counting($clientList,$list_total,$num);

		$paging = $this->make_paging2("/admin/home/clientList",$start_page,$end_page,$page_size,$num,$srcN,$total_page,$params);

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		//customSetting
		for($i = 0; $i < count($clientList); $i++)
		{
			$clientList[$i]['wdate'] = date("Y-m-d",strtotime($clientList[$i]['wdate']));
		}

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"clientList"	=>	$clientList,
			"paging"		=>	$paging,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"num"				=>	$num,
			"total_client"	=>	number_format($list_total)
		);




		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/client-list",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function clientWrite()
	{
		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$param = "num=".$num."&srcN=".$srcN."&srcType=".$srcType."&status=".$status;

		$depth1 = "admin";
		$depth2 = "client";
		$title = "업체등록";
		$sub_title = "업체등록";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"num"				=>	$num,
			"param"			=>	$param
		);

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/client-write",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function clientWriteProc()
	{
		$client_name = $this->input->post("client_name");
		$business_no = $this->input->post("business_no");
		$business_1 = $this->input->post("business_1");
		$business_2 = $this->input->post("business_2");
		$phone = $this->input->post("phone");
		$fax = $this->input->post("fax");
		$manager_name = $this->input->post("manager_name");
		$manager_phone = $this->input->post("manager_phone");
		$owner_name = $this->input->post("owner_name");
		$zipcode = $this->input->post("zipcode");
		$addr_1 = $this->input->post("addr_1");
		$addr_2 = $this->input->post("addr_2");
		$email = $this->input->post("email");
		$c_id = $this->input->post("c_id");
		$c_pw = $this->input->post("c_pw");

		$data = array(
			"client_name"	=>	$client_name,
			"business_no"	=>	$business_no,
			"business_1"	=>	$business_1,
			"business_2"	=>	$business_2,
			"owner_name"	=>	$owner_name,
			"phone"	=>	$phone,
			"fax"	=>	$fax,
			"manager_name"	=>	$manager_name,
			"manager_phone"	=>	$manager_phone,
			"email"	=>	$email,
			"c_id"	=>	$c_id,
			"c_pw"	=>	$c_pw,
			"zipcode"	=>	$zipcode,
			"addr_1"	=>	$addr_1,
			"addr_2"	=>	$addr_2,
			"wdate"	=>	date("Y-m-d H:i:s")
		);

		$result = $this->adm_model->insertClient($data);

		if($result['result'] == "success"){

			echo '{"result":"success"}';
		}else{
			echo '{"result":"failed"}';
		}
		exit;
	}

	public function clientModify($client_idx)
	{
		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$param = "num=".$num."&srcN=".$srcN."&srcType=".$srcType."&status=".$status;

		$depth1 = "admin";
		$depth2 = "client";
		$title = "업체수정";
		$sub_title = "업체수정";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$clientData = $this->adm_model->getClientData($client_idx);

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"num"				=>	$num,
			"param"			=>	$param,
			"clientData"	=>	$clientData,
			"client_idx"	=>	$client_idx
		);

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/client-modify",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function clientModifyProc()
	{
		$client_idx = $this->input->post("client_idx");
		$client_name = $this->input->post("client_name");
		$business_no = $this->input->post("business_no");
		$business_1 = $this->input->post("business_1");
		$business_2 = $this->input->post("business_2");
		$phone = $this->input->post("phone");
		$fax = $this->input->post("fax");
		$manager_name = $this->input->post("manager_name");
		$manager_phone = $this->input->post("manager_phone");
		$email = $this->input->post("email");
		$zipcode = $this->input->post("zipcode");
		$addr_1 = $this->input->post("addr_1");
		$addr_2 = $this->input->post("addr_2");
		$owner_name = $this->input->post("owner_name");
		$c_id = $this->input->post("c_id");
		$c_pw = $this->input->post("c_pw");

		$data = array(
			"client_name"	=>	$client_name,
			"business_no"	=>	$business_no,
			"business_1"	=>	$business_1,
			"business_2"	=>	$business_2,
			"phone"	=>	$phone,
			"fax"	=>	$fax,
			"manager_name"	=>	$manager_name,
			"manager_phone"	=>	$manager_phone,
			"email"	=>	$email,
			"zipcode"	=>	$zipcode,
			"addr_1"	=>	$addr_1,
			"addr_2"	=>	$addr_2,
			"owner_name"	=>	$owner_name,
			"c_id"	=>	$c_id,
			"c_pw"	=>	$c_pw,
			"wdate"	=>	date("Y-m-d H:i:s")
		);

		$result = $this->adm_model->modifyClient($data,$client_idx);

		echo '{"result":"success"}';
		exit;
	}

	public function clientDeleteProc()
	{
		$client_idx = $this->input->post("client_idx");
		$result = $this->adm_model->deleteClient($client_idx);

		echo '{"result":"success"}';
		exit;
	}

	public function deleteClients()
	{
		$chk = $this->input->post("chk");

		for($i = 0; $i< count($chk); $i++){
			$this->adm_model->deleteClient($chk[$i]);
		}

		echo '{"result":"success"}';
		exit;
	}

	//작업내역 리스트
	public function workList()
	{
		$depth1 = "admin";
		$depth2 = "work";
		$title = "작업내역 리스트";
		$sub_title = "작업내역 리스트";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');
		$f_year = $this->input->get("f_year");
		$f_month = $this->input->get("f_month");

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$f_year = $f_year ?? "";
		$f_month = $f_month ?? "";

		$params = "&srcType=$srcType&status=$status&f_year=$f_year&f_month=$f_month";

		if(!empty($f_year)){
			if(!empty($f_month)){
				$where = "AND DATE_FORMAT(reg_date,'%Y-%m') = '".$f_year."-".$f_month."'";
			}else{
				$where = "AND DATE_FORMAT(reg_date,'%Y') = '".$f_year."'";
			}

		}else{
			$where = "";
		}


		if(!empty($srcN)){
			if($srcType=="client_name"){
				$where .= "AND client.client_name LIKE '%{$srcN}%'";
			}else if($srcType=="product_name"){
				$where .= "AND work.product_name LIKE '%{$srcN}%'";
			}else if($srcType=="film_no"){
				$where .= "AND work.film_no LIKE '%{$srcN}%'";
			}else{
				$where .= "AND( work.product_name LIKE '%{$srcN}%' OR client.client_name LIKE '%{$srcN}%' OR work.film_no LIKE '%{$srcN}%')";
			}
		}

		if($status != "all"){
			$where .= " AND work.status = '{$status}'";
		}

		$page_size = 30;
		$page_list_size = 10;
		$whereData = array(
			"where"			=>	$where,
		);
		$list_total = $this->adm_model->getWorkTotalCount($where);

		if( $list_total <= 0 )
		{
			$list_total = 0;
		}

		$total_page = ceil( $list_total / $page_size );



		$whereData = array(
				"sort"			=>	"",
			"where"			=>	$where,
			"limit"			=>	"LIMIT ".$num.",".$page_size
		);

		$workList = $this->adm_model->getWorkList($whereData);

		//넘버링
		$current_page = ceil ( ($num + 1) / $page_size );

		$start_page = floor ( ($current_page - 1) / $page_list_size ) * $page_list_size + 1;
		$end_page = $start_page + $page_list_size - 1;

		if ($total_page < $end_page)
		{
				$end_page = $total_page;
		}

		$prev_list = ($num-$page_size > 0 ) ? $num-$page_size:0;
		$next_list = ($num+$page_size < ($total_page-1)*$page_size) ? $num+$page_size:($total_page-1)*$page_size;
		//넘버링 끝
		$workList = $this->add_counting($workList,$list_total,$num);

		$paging = $this->make_paging2("/admin/home/workList",$start_page,$end_page,$page_size,$num,$srcN,$total_page,$params);

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		//customSetting
		for($i = 0; $i < count($workList); $i++)
		{
			//$workList[$i]['reg_date'] = date("Y-m-d",strtotime($workList[$i]['reg_date']));
			if($this->session->userdata("admin_level") == 1 ){
				if($workList[$i]['ea_price']<=0 && $workList[$i]['total_price']<=0 && $workList[$i]['status']=="Q"){
					$workList[$i]['product_name'] = "<span style='color:red'>".$workList[$i]['product_name']."</span>";
				}
			}


			$workList[$i]['amount'] = number_format($workList[$i]['amount']);
			$workList[$i]['work_amount'] = number_format($workList[$i]['work_amount']);

			if($workList[$i]['work_amount'] > 0){
				if($workList[$i]['work_amount2'] > 1){
						$workList[$i]['work_amount'] = $workList[$i]['work_amount']." x ".$workList[$i]['work_amount2'];
				}

			}


			switch($workList[$i]['status']){
				case "R":
					$workList[$i]['status'] = "<span class='text-success font-weight-bold'>작업대기</span>";
				break;

				case "P":
					$workList[$i]['status'] = "<span class='text-primary font-weight-bold'>작업중</span>";
				break;

				case "C":
					$workList[$i]['status'] = "<span class='text-danger font-weight-bold'>작업완료</span>";
				break;

				case "Q":
					$workList[$i]['status'] = "<span class='text-warning font-weight-bold'>납품완료</span>";

				break;
			}
		}

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"workList"	=>	$workList,
			"paging"		=>	$paging,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"f_year"	=>	$f_year,
			"f_month"	=>	$f_month,
			"num"				=>	$num,
			"total_works"	=>	number_format($list_total)
		);




		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/work-list",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function workWrite()
	{
		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');
		$f_year = $this->input->get('f_year');
		$f_month = $this->input->get('f_month');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$f_year = $f_year ?? "";

		$f_month = $f_month ?? "";

		$param = "num=".$num."&srcN=".$srcN."&srcType=".$srcType."&status=".$status."&f_year=".$f_year."&f_month=".$f_month;

		$depth1 = "admin";
		$depth2 = "work";
		$title = "작업등록";
		$sub_title = "작업등록";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	"",
			"limit"			=>	""
		);

		$clientList = $this->adm_model->getClientList($whereData);

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"f_year"		=>	$f_year,
			"f_month"		=>	$f_month,
			"clientList"	=>	$clientList,
			"num"				=>	$num,
			"param"			=>	$param
		);

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/work-write",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function workWriteProc()
	{
		$client_idx = $this->input->post("client_idx");
		$product_name = $this->input->post("product_name");
		$op_no = $this->input->post("op_no");
		$film_no = $this->input->post("film_no");
		$ea_price = $this->input->post("ea_price");
		$content = $this->input->post("content");
		$amount = $this->input->post("amount");
		$work_amount = $this->input->post("work_amount");
		$work_amount2 = $this->input->post("work_amount2");
		$dong_amount = $this->input->post("dong_amount");
		$dong_price = $this->input->post("dong_price");
		$film_price = $this->input->post("film_price");
		$material = $this->input->post("material");
		$total_price = $this->input->post("total_price");
		$status = $this->input->post("status");
		$client_name = $this->input->post("client_name");
		$reg_date = date("Y-m-d H:i:s");



		$data = array(
			"client_idx"	=>	$client_idx,
			"product_name"	=>	$product_name,
			"op_no"	=>	$op_no,
			"film_no"	=>	$film_no,
			"ea_price"	=>	$ea_price,
			"content"	=>	$content,
			"amount"	=>	$amount,
			"work_amount"	=>	$work_amount,
			"work_amount2"	=>	$work_amount2,
			"dong_amount"	=>	$dong_amount,
			"dong_price"	=>	$dong_price,
			"film_price"	=>	$film_price,
			"material"	=>	$material,
			"total_price"	=>	$total_price,
			"status"	=>	$status,
			"client_name"	=>	$client_name,
			"reg_date"	=>	$reg_date,
			"mod_date"	=>	$reg_date
		);

		if($status == "C" || $status == "Q"){
			$confirm_date = date("Y-m-d H:i:s");
			$data["confirm_date"] = $confirm_date;
		}

		$result = $this->adm_model->insertWork($data);

		echo '{"result":"success"}';
		exit;
	}

	public function workModify($work_idx)
	{
		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$srcType = $this->input->get('srcType');
		$status = $this->input->get('status');
		$f_year = $this->input->get('f_year');
		$f_month = $this->input->get('f_month');


		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$srcType = $srcType ?? "all";

		$status = $status ?? "all";

		$f_year = $f_year ?? "";

		$f_month = $f_month ?? "";

		$param = "num=".$num."&srcN=".$srcN."&srcType=".$srcType."&status=".$status."&f_year=".$f_year."&f_month=".$f_month;

		$depth1 = "admin";
		$depth2 = "work";
		$title = "작업수정";
		$sub_title = "작업수정";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	"",
			"limit"			=>	""
		);

		$clientList = $this->adm_model->getClientList($whereData);
		$workData = $this->adm_model->getWorkData($work_idx);

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"srcType"		=>	$srcType,
			"srcN"			=>	$srcN,
			"status"		=>	$status,
			"f_year"		=>	$f_year,
			"f_month"		=>	$f_month,
			"clientList"	=>	$clientList,
			"workData"	=>	$workData,
			"work_idx"	=>	$work_idx,
			"num"				=>	$num,
			"param"			=>	$param
		);

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/work-modify",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function workModifyProc()
	{
		$work_idx = $this->input->post("work_idx");
		$client_idx = $this->input->post("client_idx");
		$product_name = $this->input->post("product_name");
		$op_no = $this->input->post("op_no");
		$film_no = $this->input->post("film_no");
		$ea_price = $this->input->post("ea_price");
		$content = $this->input->post("content");
		$amount = $this->input->post("amount");
		$work_amount = $this->input->post("work_amount");
		$work_amount2 = $this->input->post("work_amount2");
		$dong_amount = $this->input->post("dong_amount");
		$dong_price = $this->input->post("dong_price");
		$film_price = $this->input->post("film_price");
		$material = $this->input->post("material");
		$total_price = $this->input->post("total_price");
		$status = $this->input->post("status");
		$client_name = $this->input->post("client_name");
		$etc = $this->input->post("etc");
		$mod_date = date("Y-m-d H:i:s");
		$confirm_date = $this->input->post("confirm_date");

		$data = array(
			"client_idx"	=>	$client_idx,
			"product_name"	=>	$product_name,
			"op_no"	=>	$op_no,
			"film_no"	=>	$film_no,
			"ea_price"	=>	$ea_price,
			"content"	=>	$content,
			"amount"	=>	$amount,
			"work_amount"	=>	$work_amount,
			"work_amount2"	=>	$work_amount2,
			"dong_amount"	=>	$dong_amount,
			"dong_price"	=>	$dong_price,
			"film_price"	=>	$film_price,
			"material"	=>	$material,
			"total_price"	=>	$total_price,
			"status"	=>	$status,
			"client_name"	=>	$client_name,
			"etc"	=>	$etc,
			"mod_date"	=>	$mod_date,
			"confirm_date"	=>	$confirm_date
		);

		$result = $this->adm_model->updateWork($data,$work_idx);

		echo '{"result":"success"}';
		exit;
	}

	public function updateWorkStatus()
	{
		$chk = $this->input->post("chk");
		$work_status = $this->input->post("work_status");

		for($i=0; $i<count($chk); $i++){
			$result = $this->adm_model->changeStateWork($chk[$i],$work_status);
			if($result!="success"){
				echo '{"result":"failed"}';
				exit;
			}
		}

		echo '{"result":"success"}';
		exit;
	}

	public function deleteWorks()
	{
		$chk = $this->input->post("chk");
		for($i=0; $i<count($chk); $i++){
			$this->adm_model->deleteWork($chk[$i]);
		}

		echo '{"result":"success"}';
		exit;
	}

	public function deleteWork()
	{
		$work_idx = $this->input->post("work_idx");
		$this->adm_model->deleteWork($work_idx);

		echo '{"result":"success"}';
		exit;
	}

	public function getEaPrice()
	{
		$product_name = $this->input->post("product_name");

		$product_name = str_replace(" ","",$product_name);

		$ea_price = $this->adm_model->getEaPrice($product_name);

		echo '{"result":"'.$ea_price.'"}';
		exit;
	}

	public function workExcelPop()
	{
		$sub_title = "작업내역 엑셀 업로드";
		$depth1 = "";
		$depth2 = "";

		$this->CONFIG_DATA['depth1'] = $depth1;
		$this->CONFIG_DATA['depth2'] = $depth2;

  	$content_data = array(
      "base_url"  	=>  $this->BASE_URL,
  		"sub_title"		=>	$sub_title,
			"depth1"			=>	$depth1
    );

  	//header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
  	//contents
    $this->parser->parse("admin/work-excel-pop",$content_data);
	}

	public function workExcelProc()
	{
		$excel = load_controller('admin/excelAdm');
		$excel_file = $_FILES['excel']['tmp_name'];

		$objPHPExcel = PHPExcel_IOFactory::load($excel_file);
		$objPHPExcel->setActiveSheetIndex(0);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

		/*sheetData
		A = 계약구분
		B = 기관명
		C = 기관구분
		D = 우편번호
		E = 주소1
		F = 주소2
		G = 연락처
		H = 승인유무
		I = 계약시작날짜
		J = 계약종료날짜
		K = 메모
		L = 이메일
		M = 지역
		N = lat
		O = lng
		*/

		//엑셀 제목 삭제
		array_shift($sheetData);

		//print_r($sheetData);
		//exit;

		//에러 정리 array
		$errorArr = array();

		if( count($sheetData) > 0 ){
			$errorArr['noData'] = true;
			$success = 0;
			$failed = 0;
			for( $i = 0; $i < count($sheetData); $i++ ){
				$reg_date = $sheetData[$i]['B'];

				if(empty($reg_date)){
					break;
				}

				$client_idx = 20;
				$reg_date = date("Y-m-d",strtotime($reg_date));
				$mod_date = $reg_date;
				$product_name = $sheetData[$i]['C'];
				$op_no = $sheetData[$i]['D'];
				$film_no = $sheetData[$i]['E'];
				$ea_price = $sheetData[$i]['F'];

				if(empty($ea_price)){
					$ea_price = "0";
				}

				$content = $sheetData[$i]['G'];
				$amount = $sheetData[$i]['H'];

				if(empty($amount)){
					$amount = 1;
				}

				$dong_amount = $sheetData[$i]['I'];
				if(empty($dong_amount)){
					$dong_amount = 1;
				}

				$dong_price = $sheetData[$i]['J'];


				$film_price = $sheetData[$i]['K'];


				$material = $sheetData[$i]['L'];
				$total_price = $sheetData[$i]['N'];

				$total_price = str_replace(",","",$sheetData[$i]['N']);

				$etc = $sheetData[$i]['M'];
				$status = "Q";


				$data = array(
					"client_idx"	=>	$client_idx,
					"reg_date"	=>	$reg_date,
					"mod_date"	=>	$mod_date,
					"product_name"	=>	$product_name,
					"op_no"	=>	$op_no,
					"film_no"	=>	$film_no,
					"ea_price"	=>	$ea_price,
					"content"	=>	$content,
					"amount"	=>	$amount,
					"dong_amount"	=>	$dong_amount,
					"dong_price"	=>	$dong_price,
					"film_price"	=>	$film_price,
					"material"	=>	$material,
					"total_price"	=>	$total_price,
					"etc"	=>	$etc,
					"status"	=>	$status
				);

				$result = $this->adm_model->insertWorks($data);
				$success++;
			}

		}else{
			$errorArr['noData'] = true;
		}

		$sub_title = "작업내역 엑셀 업로드 결과";
		$depth1 = "";
		$depth2 = "";

		$this->CONFIG_DATA['depth1'] = $depth1;
		$this->CONFIG_DATA['depth2'] = $depth2;

  	$content_data = array(
  		"sub_title"		=>	$sub_title,
			"depth1"			=>	$depth1,
			"success"			=>	$success,
			"failed"			=>	$failed,
			"errorArr"		=>	$errorArr
    );

  	//header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
  	//contents
    $this->parser->parse("admin/work-excel-proc",$content_data);
	}



	//결산리스트
	public function settleList()
	{
		$depth1 = "admin";
		$depth2 = "settle";
		$title = "결산리스트";
		$sub_title = "결산리스트";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$num = $this->input->get('num');
		$srcN = $this->input->get('srcN');
		$year = $this->input->get('year');
		$month = $this->input->get('month');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		if(empty($year)){
			$year = date("Y");
		}

		if(empty($month)){
			$month = date("m");
		}

		$year_mon = $year."-".$month;

		$where = " AND DATE_FORMAT(work.confirm_date,'%Y-%m') = '$year_mon'";

		if(!empty($srcN)){
			$where .= " AND client.client_name LIKE '%{$srcN}%'";
		}

		$page_size = 20;
		$page_list_size = 10;
		$whereData = array(
			"where"			=>	$where,
		);
		$list_total = $this->adm_model->getSettleTotalCount($where);

		if( $list_total <= 0 )
		{
			$list_total = 0;
		}

		$total_page = ceil( $list_total / $page_size );

		$params = "";

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	$where,
			"limit"			=>	"LIMIT ".$num.",".$page_size
		);

		$settleList = $this->adm_model->getSettleList($whereData,$year,$month,$year_mon);

		//넘버링
		$current_page = ceil ( ($num + 1) / $page_size );

		$start_page = floor ( ($current_page - 1) / $page_list_size ) * $page_list_size + 1;
		$end_page = $start_page + $page_list_size - 1;

		if ($total_page < $end_page)
		{
				$end_page = $total_page;
		}

		$prev_list = ($num-$page_size > 0 ) ? $num-$page_size:0;
		$next_list = ($num+$page_size < ($total_page-1)*$page_size) ? $num+$page_size:($total_page-1)*$page_size;
		//넘버링 끝
		$settleList = $this->add_counting($settleList,$list_total,$num);

		$paging = $this->make_paging2("/admin/home/settleList",$start_page,$end_page,$page_size,$num,$srcN,$total_page,$params);

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$sumTotal = 0;
		//customSetting
		for($i = 0; $i < count($settleList); $i++)
		{
			$sumTotal += $settleList[$i]['total_price'];
			$settleList[$i]['total_price'] = number_format($settleList[$i]['total_price']);

		}
		$sumTotal = number_format($sumTotal);

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"settleList"	=>	$settleList,
			"paging"		=>	$paging,
			"srcN"			=>	$srcN,
			"year"			=>	$year,
			"month"				=>	$month,
			"status"		=>	$status,
			"num"				=>	$num,
			"total_client"	=>	number_format($list_total),
			"sumTotal"	=>	$sumTotal
		);




		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/settle-list",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function settlePop()
	{
		$year = $this->input->get("year");
		$month = $this->input->get("month");
		$client_idx = $this->input->get("client_idx");

		$companyData = $this->adm_model->getCompany();
		$settleData = $this->adm_model->getSettleData($year,$month,$client_idx);

		$etc_price = 0;
		$etc_product_name = "";

		/*
		if($client_idx == 4){
			$etc_price = 25000000;
			$etc_product_name = "박스,쇼핑백 제작";
		}
		*/

		$sumTotal = 0;
		for($i=0; $i< count($settleData); $i++){
			$sumTotal += $settleData[$i]['total_price'];
			if($settleData[$i]['work_amount'] == 0){
				$settleData[$i]['work_amount'] = $settleData[$i]['amount'] + 100;
			}

			$settleData[$i]['confirm_date'] = date("m/d",strtotime($settleData[$i]['confirm_date']));



			$settleData[$i]['amount'] = number_format($settleData[$i]['amount']);
			$settleData[$i]['work_amount'] = number_format($settleData[$i]['work_amount']);
			$settleData[$i]['ea_price'] = number_format($settleData[$i]['ea_price']);

			if($settleData[$i]['dong_price'] != 0 || $settleData[$i]['film_price'] != 0){
				$settleData[$i]['total_price'] = $settleData[$i]['total_price'] - ($settleData[$i]['dong_price'] + $settleData[$i]['film_price']);
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}else{
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}

			/*
			if($settleData[$i]['ea_price'] <= 0 && $settleData[$i]['total_price'] > 0){
				if(empty($settleData[$i]['etc'])){
					$settleData[$i]['etc'] = "기본 ".$settleData[$i]['total_price'];
				}

			}
			*/
		}

		$sumTotal += $etc_price;

		$perTotal = $sumTotal / 10;

		$sumLastTotal = $sumTotal + $perTotal;
		$perTotal = number_format($perTotal);
		$sumTotal = number_format($sumTotal);
		$sumLastTotal = number_format($sumLastTotal);

		switch($client_idx){
			case 6:
			case 8:
			case 9:
			case 10:
				$companyData['bank'] = "신한은행(김금애-한성피앤비):110-505-796462";
			break;
		}

		$contentData = array(
			"companyData"	=>	$companyData,
			"settleData"	=>	$settleData,
			"sumTotal"		=>	$sumTotal,
			"perTotal"		=>	$perTotal,
			"sumLastTotal"	=>	$sumLastTotal,
			"etc_price"	=>	$etc_price,
			"etc_product_name"	=>	$etc_product_name,
			"client_idx"	=>	$client_idx,
			"year"	=>	$year,
			"month"	=>	$month
		);

		//header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);

		$this->parser->parse("admin/settle-pop",$contentData);
	}

	//결산 선택리스트
	public function settleList2()
	{
		$depth1 = "admin";
		$depth2 = "settle2";
		$title = "선택결산리스트";
		$sub_title = "선택결산리스트";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$first_date = $this->input->get('first_date');
		$last_date = $this->input->get('last_date');

		$num = $num ?? 0;

		$srcN = $srcN ?? "";

		$where = "";

		$page_size = 20;
		$page_list_size = 10;
		$whereData = array(
			"where"			=>	$where,
		);
		$list_total = $this->adm_model->getClientTotalCount($where);

		if( $list_total <= 0 )
		{
			$list_total = 0;
		}

		$total_page = ceil( $list_total / $page_size );

		$params = "";

		$whereData = array(
				"sort"			=>	"",
			"where"			=>	$where,
			"limit"			=>	"LIMIT ".$num.",".$page_size
		);

		$clientList = $this->adm_model->getClientList($whereData);

		//넘버링
		$current_page = ceil ( ($num + 1) / $page_size );

		$start_page = floor ( ($current_page - 1) / $page_list_size ) * $page_list_size + 1;
		$end_page = $start_page + $page_list_size - 1;

		if ($total_page < $end_page)
		{
				$end_page = $total_page;
		}

		$prev_list = ($num-$page_size > 0 ) ? $num-$page_size:0;
		$next_list = ($num+$page_size < ($total_page-1)*$page_size) ? $num+$page_size:($total_page-1)*$page_size;
		//넘버링 끝
		$clientList = $this->add_counting($clientList,$list_total,$num);

		$paging = $this->make_paging2("/admin/home/settleList2",$start_page,$end_page,$page_size,$num,$srcN,$total_page,$params);

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"clientList"	=>	$clientList,
			"paging"		=>	$paging,
			"srcN"			=>	$srcN,
			"first_year"			=>	$first_year,
			"first_month"			=>	$first_year,
			"first_day"			=>	$first_year,
			"last_year"			=>	$last_year,
			"last_month"			=>	$last_month,
			"last_day"			=>	$last_day,
			"num"				=>	$num,
			"total_client"	=>	number_format($list_total)
		);




		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/settle-list2",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function settlePop2()
	{
		$first_date = $this->input->get("first_date");
		$last_date = $this->input->get("last_date");
		$client_idx = $this->input->get("client_idx");

		$companyData = $this->adm_model->getCompany();
		$settleData = $this->adm_model->getSettleData2($first_date,$last_date,$client_idx);

		$etc_price = 0;
		$etc_product_name = "";

		/*
		if($client_idx == 4){
			$etc_price = 25000000;
			$etc_product_name = "박스,쇼핑백 제작";
		}
		*/

		$sumTotal = 0;
		for($i=0; $i< count($settleData); $i++){
			$sumTotal += $settleData[$i]['total_price'];
			if($settleData[$i]['work_amount'] == 0){
				$settleData[$i]['work_amount'] = $settleData[$i]['amount'] + 100;
			}

			$settleData[$i]['confirm_date'] = date("m/d",strtotime($settleData[$i]['confirm_date']));



			$settleData[$i]['amount'] = number_format($settleData[$i]['amount']);
			$settleData[$i]['work_amount'] = number_format($settleData[$i]['work_amount']);
			//$settleData[$i]['ea_price'] = number_format(floor($settleData[$i]['ea_price']));

			if($settleData[$i]['dong_price'] != 0 || $settleData[$i]['film_price'] != 0){
				$settleData[$i]['total_price'] = $settleData[$i]['total_price'] - ($settleData[$i]['dong_price'] + $settleData[$i]['film_price']);
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}else{
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}


			if($settleData[$i]['ea_price'] <= 0 && $settleData[$i]['total_price'] > 0){
				if(empty($settleData[$i]['etc'])){
					$settleData[$i]['etc'] = "기본 ".$settleData[$i]['total_price'];
				}

			}
		}

		$sumTotal += $etc_price;

		$perTotal = $sumTotal / 10;

		$sumLastTotal = $sumTotal + $perTotal;
		$perTotal = number_format($perTotal);
		$sumTotal = number_format($sumTotal);
		$sumLastTotal = number_format($sumLastTotal);

		switch($client_idx){
			case 6:
			case 8:
			case 9:
			case 10:
				$companyData['bank'] = "신한은행(김금애-한성피앤비):110-505-796462";
			break;
		}

		$contentData = array(
			"companyData"	=>	$companyData,
			"settleData"	=>	$settleData,
			"sumTotal"		=>	$sumTotal,
			"perTotal"		=>	$perTotal,
			"sumLastTotal"	=>	$sumLastTotal,
			"etc_price"	=>	$etc_price,
			"etc_product_name"	=>	$etc_product_name,
			"client_idx"	=>	$client_idx,
			"first_date"	=>	$first_date,
			"last_date"	=>	$last_date
		);

		//header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);

		$this->parser->parse("admin/settle-pop2",$contentData);
	}

	public function settlePopExcel()
	{
		$first_date = $this->input->get("first_date");
		$last_date = $this->input->get("last_date");
		$client_idx = $this->input->get("client_idx");

		$companyData = $this->adm_model->getCompany();
		$settleData = $this->adm_model->getSettleData2($first_date,$last_date,$client_idx);

		header( "Content-type: application/vnd.ms-excel; charset=utf-8");
		header( "Content-Description: PHP4 Generated Data" );
		header( "Content-Disposition: attachment; filename=".$settleData[0]['client_name']."_".date("Y년 m월",strtotime($last_date))."_결산.xls" );
		print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");




		$etc_price = 0;
		$etc_product_name = "";
		/*
		if($client_idx == 4){
			$etc_price = 25000000;
			$etc_product_name = "박스,쇼핑백 제작";
		}
		*/

		$sumTotal = 0;
		for($i=0; $i< count($settleData); $i++){
			$sumTotal += $settleData[$i]['total_price'];
			if($settleData[$i]['work_amount'] == 0){
				$settleData[$i]['work_amount'] = $settleData[$i]['amount'] + 100;
			}

			$settleData[$i]['confirm_date'] = date("m/d",strtotime($settleData[$i]['confirm_date']));



			$settleData[$i]['amount'] = number_format($settleData[$i]['amount']);
			$settleData[$i]['work_amount'] = number_format($settleData[$i]['work_amount']);
			//$settleData[$i]['ea_price'] = number_format(floor($settleData[$i]['ea_price']));

			if($settleData[$i]['dong_price'] != 0 || $settleData[$i]['film_price'] != 0){
				$settleData[$i]['total_price'] = $settleData[$i]['total_price'] - ($settleData[$i]['dong_price'] + $settleData[$i]['film_price']);
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}else{
				$settleData[$i]['total_price'] = number_format($settleData[$i]['total_price']);
			}


			if($settleData[$i]['ea_price'] <= 0 && $settleData[$i]['total_price'] > 0){
				if(empty($settleData[$i]['etc'])){
					$settleData[$i]['etc'] = "기본 ".$settleData[$i]['total_price'];
				}

			}
		}

		$sumTotal += $etc_price;

		$perTotal = $sumTotal / 10;

		$sumLastTotal = $sumTotal + $perTotal;
		$perTotal = number_format($perTotal);
		$sumTotal = number_format($sumTotal);
		$sumLastTotal = number_format($sumLastTotal);

		switch($client_idx){
			case 6:
			case 8:
			case 9:
			case 10:
				$companyData['bank'] = "신한은행(김금애-한성피앤비):110-505-796462";
			break;
		}

		$output = "

		<style>
		* {
		  box-sizing: border-box;
		  -moz-box-sizing: border-box;
		}

		body {
		  margin: 0;
		  padding: 0;
		}
		.page {
		  width: 21cm;
		  min-height: 29.7cm;
		  padding: 0.4cm 0.4cm 0.4cm 0.4cm;
		}

		.bd {
		  border:1px solid #000;
		}

		table th {
		  border:1px solid #000;
		}

		.text-center {
			text-align: center;
		}

		@page {
		  size: A4;
		  margin: 0;
		}

		@media print {
		  .page {
		    margin:0;
		    border: initial;
		    border-radius: initial;
		    width: initial;
		    min-height: initial;
		    box-shadow: initial;
		    background: initial;
		    page-break-after: always;
		  }
		}
		</style>
		<div class=\"page\">
		<table style=\"margin-top:10px;width:100%;\">
		  <tr>
		    <td colspan=\"8\" class=\"text-center\"><h1>".date("Y년 m월",strtotime($last_date))."거 래 명 세 서</h1></td>
		  </tr>
		</table>

		<table style=\"margin-top:5px;width:100%\">
			<tr>
				<td colspan=\"4\" class=\"text-center align-middle\" style=\"width:40%;height:50px;\"><h4><b>".$settleData[0]['client_name']."</b> 貴下</h4></td>
				<td style=\"width:60%;padding-right:5px;\" rowspan=\"4\" >
		      <table style=\"border:1px solid #000;width:100%;height:100%\">
		        <tr>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">사업자<br>등록번호</td>
		          <td class=\"text-center align-middle\" colspan=\"3\" style=\"height:50px;border:1px solid #000;\">".$companyData['business_no']."</td>
		        </tr>
		        <tr>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">상 호</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">".$companyData['company_name']."</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">대표자<br>성 명</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">".$companyData['owner_name']."</td>
		        </tr>
		        <tr>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">주소</td>
		          <td class=\"text-left align-middle\" colspan=\"3\" style=\"height:50px;border:1px solid #000;\">".$companyData['addr_1']." ".$companyData['addr_2']."</td>
		        </tr>
		        <tr>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">업 태</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">".$companyData['business_1']."</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">종 목</td>
		          <td class=\"text-center align-middle\" style=\"height:50px;border:1px solid #000;\">".$companyData['business_2']."</td>
		        </tr>
		      </table>
		    </td>
			</tr>
			<tr>
				<td colspan=\"4\" class=\"text-center align-middle\" style=\"width:40%;height:50px;\"></td>
			</tr>
			<tr>
				<td colspan=\"4\" class=\"text-center align-middle\" style=\"width:40%;height:50px;\"><h4>아래와 같이 청구 합니다.</h4></td>
			</tr>
			<tr>
				<td colspan=\"4\" class=\"text-center align-middle\" style=\"width:40%;height:50px;\"></td>
			</tr>
		  <tr>

		  </tr>

		</table>
		<!-- body -->
		  <table style=\"width:100%;margin-top:15px;\">
		    <colgroup>
		      <col width=\"5%\">
		      <col width=\"30%\">
		      <col width=\"10%\">
		      <col width=\"8%\">
		      <col width=\"8%\">
		      <col width=\"8%\">
		      <col width=\"10%\">
		      <col width=\"10%\">
		    </colgroup>
		    <thead style=\"display:table-row-group\">
		      <tr>
		        <th class=\"text-center align-middle\">일자</th>
		        <th class=\"text-center align-middle\">품 명</th>
		        <th class=\"text-center align-middle\">내 용</th>
		        <th class=\"text-center align-middle\">정 매</th>
		        <th class=\"text-center align-middle\">수 량</th>
		        <th class=\"text-center align-middle\">단 가</th>
		        <th class=\"text-center align-middle\">공급가액</th>
		        <th class=\"text-center align-middle\">비 고</th>
		      </tr>
		    </thead>
		    <tbody>
		      <!-- contents -->
					";
		      for($i=0; $i<count($settleData); $i++){
						$output .= "
		      <tr>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['confirm_date']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['product_name']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['content']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['amount']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">
		          ";
		          if($settleData[$i]['work_amount2'] > 1){
		           $output .= $settleData[$i]['work_amount']." x ".$settleData[$i]['work_amount2'];
		         }else{
		           $output .= $settleData[$i]['work_amount'];
		         }

						 $output .= "
		        </td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['ea_price']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['total_price']."</td>
		        <td style=\"border:1px solid #000;text-align:center;\">".$settleData[$i]['etc']."</td>
		      </tr>";
		      if($settleData[$i]['dong_price']>0){
						$output .= "
		        <tr>
		          <td style=\"border:1px solid #000;text-align:right;\" colspan=\"6\">동판비</td>
		          <td style=\"border:1px solid #000;text-align:center;\">".number_format($settleData[$i]['dong_price'])."</td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		        </tr>
						";
					}
		      if($settleData[$i]['film_price']>0){
						$output .= "
						<tr>
		          <td style=\"border:1px solid #000;text-align:right;\" colspan=\"6\">필름비</td>
		          <td style=\"border:1px solid #000;text-align:center;\">".number_format($settleData[$i]['film_price'])."</td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		        </tr>";
		      }
		      }
		      /*if($client_idx == 4){
						$output .= "
		        <tr>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		          <td style=\"border:1px solid #000;text-align:center;\">".$etc_product_name."</td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		          <td style=\"border:1px solid #000;text-align:center;\">

		          </td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		          <td style=\"border:1px solid #000;text-align:center;\">".number_format($etc_price)."</td>
		          <td style=\"border:1px solid #000;text-align:center;\"></td>
		        </tr>";
		      }
					*/

					$output .= "
					<tr>
						<td colspan=\"8\"></td>
					</tr>
		      <tr>
		        <td style=\"border:1px solid #000;text-align:center;\" colspan=\"6\" rowspan=\"3\">
		          <h4>".$companyData['bank']."</h4>
		        </td>
		        <td class=\"bd text-center align-middle\" style=\"border-left:3px solid #000;border-top:3px solid #000; border:1px solid #000;\">공급가액</td>
		        <td class=\"bd text-right align-middle\" style=\"border-right:3px solid #000;border-top:3px solid #000; border:1px solid #000;\">".$sumTotal."</td>
		      </tr>
		      <tr>
		        <td class=\"bd text-center align-middle\" style=\"border-left:3px solid #000; border:1px solid #000;\">부가세</td>
		        <td class=\"bd text-right align-middle\" style=\"border-right:3px solid #000; border:1px solid #000;\">".$perTotal."</td>
		      </tr>
		      <tr>
		        <td class=\"bd text-center align-middle\" style=\"border-left:3px solid #000;border-bottom:3px solid #000; border:1px solid #000;\">합계</td>
		        <td class=\"bd text-right align-middle\" style=\"border-right:3px solid #000;border-bottom:3px solid #000; border:1px solid #000;\">".$sumLastTotal."</td>
		      </tr>

		    </tbody>
		  </table>

		</div>";
		echo $output;

	}



	//logout
	public function logout(){
		session_destroy();
		echo '{ "result" : "success" }';
		exit;
	}

	public function terms()
	{
		$depth1 = "admin";
		$depth2 = "terms";
		$title = "이용약관";
		$sub_title = "이용약관";

		$this->CONFIG_DATA["depth1"] = $depth1;
		$this->CONFIG_DATA["depth2"] = $depth2;

		$termsData = $this->adm_model->getTermPrivacy();


		$content_data = array(
			"depth1"		=>	$depth1,
			"title"			=>	$title,
			"sub_title"	=>	$sub_title,
			"termsData"	=>	$termsData
		);

		//header and css loads
		$this->parser->parse("admin/include/header",$this->CONFIG_DATA);

		//menu
		$this->parser->parse("admin/include/left",$this->CONFIG_DATA);
		//contents
		$this->parser->parse("admin/adm-terms",$content_data);

		//Footer
		$this->parser->parse("admin/include/footer",$this->CONFIG_DATA);
		//footer js files
		$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
	}

	public function termsWriteProc()
	{
		$terms = $this->input->post("terms");


		$data = array(
			"terms"	=>	$terms
		);

		$result = $this->adm_model->writeTerms($data);

		echo '{"result":"success"}';
		exit;
	}



	/**
	*============================== end =====================================*
	*/

	//make paging2
	public function make_paging2($url,$start_page,$end_page,$page_size,$num,$srcN="",$total_page,$params="")
	{
	    $pageArr[]['no'] = '<li><a class="page-link" href="'.$url.'?num=0&srcN='.$srcN.$params.'"><</a></li>';
		if( $end_page <= 0 )
        {
            $pageArr[]['no'] = '<li class="page-item"><a class="page-link" href="#">1</a></li>';
        }

        for( $i = $start_page; $i <= $end_page; $i++ )
        {
          $page = ( $i - 1 ) * $page_size;
          if( $num != $page )
          {
	    			$pageArr[$i]['no'] = '<li class="page-item"><a class="page-link" href="'.$url.'?num='.$page.'&srcN='.$srcN.$params.'">'.$i.'</a></li>';
          }
          else
          {
            $pageArr[$i]['no'] = '<li ><a class="page-link" href="#" style="background:#efefef">'.$i.'</a></li>';
          }
        }

        if($total_page> $end_page)
            $pageArr[]['no'] = '<li><a class="page-link" href="'.$url.'?num='.((($end_page*10)-10)+10).'&srcN='.$srcN.$params.'">></a></li>';
        else
            $pageArr[]['no'] = '<li><a class="page-link" href="#">></a></li>';

        return $pageArr;
	}

	//make paging
	public function make_paging($bd_name,$start_page,$end_page,$page_size,$num,$srcN="")
  {

    if( $end_page <= 0 )
    {
        $pageArr[0]['no'] = '<li class="page-item"><a class="page-link" href="#">1</a></li>';
    }

    for( $i = $start_page; $i <= $end_page; $i++ )
    {
      $page = ( $i - 1 ) * $page_size;
      if( $num != $page )
      {
				$pageArr[$i]['no'] = '<li class="page-item"><a class="page-link" href="/admin/board/'.$bd_name.'?num='.$page.'&srcN='.$srcN.'">'.$i.'</a></li>';
      }
      else
      {
        $pageArr[$i]['no'] = '<li><a class="page-link" href="#">'.$i.'</a></li>';
      }
    }

    return $pageArr;
  }

	//board add counting
	public function add_counting($arr,$total,$num)
  {
    $i = $total-$num;
    $returnArr = $arr;

    for( $v = 1; $v <= count($returnArr); $v++ )
    {
      //$returnArr[$v-1]['bd_name'] = $bd_name;
      $returnArr[$v-1]['count'] = $i;
      $i--;
    }

    return $returnArr;

  }

	public function eaPricePop()
	{
		$product_name = $this->input->get("product_name");
		$eaPriceData = $this->adm_model->getEaPricePopFind($product_name);

		for($i=0; $i<count($eaPriceData); $i++){
			if($eaPriceData[$i]['product_name']==$product_name){
				$eaPriceData[$i]['product_name'] = '<span style="color:red">'.$product_name.'</span>';
			}
		}

		$data = array(
			"title"	=>	$product_name,
			"eaPriceData"	=>	$eaPriceData
		);

		//header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
  	//contents
    $this->parser->parse("admin/ea-price-pop",$data);
	}


}
