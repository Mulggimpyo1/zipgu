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

    $this->load->model("adm_model");

		$this->load->library('excel');

		$this->load->helper('load_controller');
		$uri = explode("/",uri_string());
		// login Check
    if( !$this->session->userdata("partner_id") ){
      if( $uri[count($uri)-1] != "login" && $uri[count($uri)-1] != "login_proc" ){
        //$this->msg("로그인 해주시기 바랍니다.");
        $this->goURL(base_url("partner/home/login"));
        exit;
      }
		}


	}

	public function index()
	{

		//login page redirect
		if( !$this->session->userdata("partner_id") ){
			$this->login();
		}else{
		    $this->goURL("partner/list");

		}


	}

  public function list()
  {
    $year = $this->input->get("year");
    $month = $this->input->get("month");
    $status = $this->input->get("status");

    if(empty($status)){
      $status = "all";
    }

    if(empty($year)){
      $year = date("Y");
    }

    if(empty($month)){
      $month = date("m");
    }

    $partner_idx = $this->session->userdata("partner_idx");

    $partnerList = $this->adm_model->getPartnerList($partner_idx,$year,$month,$status);

    $client_name = $this->session->userdata("client_name");

    $content_data = array(
      "year"  =>  $year,
      "month" =>  $month,
      "status"  =>  $status,
      "partnerList" =>  $partnerList,
      "client_name" =>  $client_name
    );
    //header and css loads
    $this->parser->parse("admin/include/pop-header",$this->CONFIG_DATA);

  	//footer js files
  	$this->parser->parse("admin/include/footer_js",$this->CONFIG_DATA);
  	//contents
    $this->parser->parse("partner/list",$content_data);
  }

  public function login()
  {
    $content_data = array(
			"base_url"	=>	$this->BASE_URL
		);
    $this->parser->parse("partner/login",$content_data);
  }


  //login ajax
	public function login_proc()
	{
		$partner_id = $this->input->post("partner_id");
		$partner_password = $this->input->post("partner_password");

		if( empty($partner_id) ){
			echo '{ "result" : "failed" , "message" : "아이디를 입력 해 주세요." }';
			exit;
		}

		if( empty($partner_password) ){
			echo '{ "result" : "failed" , "message" : "비밀번호를 입력 해 주세요." }';
			exit;
		}

		//db id check
		$result = $this->adm_model->partnerLogin($partner_id,$partner_password);

		if( $result["result"] == "success" ){

			//session에 저장
			$this->session->set_userdata("partner_id" , $partner_id);
			$this->session->set_userdata("client_name",$result['client_name']);
      $this->session->set_userdata("partner_idx",$result["client_idx"]);


			$value = $this->security->get_csrf_hash();

			echo '{ "result" : "success", "csrf" : "'.$value.'"}';
			exit;
		} else {
			$message = "";

			switch($result["message"]){
				case "partner_id" :
					$message = "아이디를 확인 해 주세요.";
				break;

				case "partner_password":
					$message = "비밀번호를 확인 해 주세요.";
				break;
			}

			echo '{ "result" : "failed" , "message" : "'.$message.'" }';
			exit;
		}
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


}
