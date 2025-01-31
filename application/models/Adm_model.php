<?php
  class Adm_Model extends MY_Model {
    function __construct(){
      parent::__construct();
      $this->load->library('encryption');
      $this->encryption->initialize(
          array(
                  'cipher' => 'aes-256'       //  암호화 알고리즘
                  ,'key'   =>  $this->config->config["key"]           //  암호화 키
                  ,'mode'  =>  'ctr'          //  암호화 모드
                  )
      );
    }

    public function getTotalWorkCount()
    {
      $sql = "SELECT count(work_idx) total_num FROM tb_work";
      $result = $this->db->query($sql)->row_array();

      return $result['total_num'];
    }

    public function insertClientDb($data)
    {
      $this->db->insert("client_db",$data);
    }

    public function login($admin_id,$admin_password)
    {
      $password_key = $this->config->config['password_key'];
      $result = array();
      $query = "SELECT AES_DECRYPT(UNHEX(admin_password), '{$password_key}') admin_password,
                      admin_id,
                      admin_name,
                      admin_level,
                      conditions,
                      user_email,
                      affiliation,
                      auth_control
                      FROM tb_admin WHERE admin_id = '{$admin_id}'";
      $admin_id_result = $this->db->query($query);
      $row = $admin_id_result->row_array();



      if( $admin_id_result->num_rows() > 0 ){

        if($row['admin_password'] == $admin_password ){
          $result["result"] = "success";
          $result["adminData"] = $row;
          $result["admin_type"] = "A";
          $result["admin_level"] = $row['admin_level'];
        } else {
          $result["result"] = "failed";
          $result["message"] = "admin_password";
        }

      }




      return $result;
    }

    public function insertWorks($data)
    {
      $this->db->insert('tb_work',$data);
    }

    public function partnerLogin($partner_id,$partner_password)
    {

      $result = array();
      $query = "SELECT * FROM client WHERE c_id = '{$partner_id}'";
      $partner_id_result = $this->db->query($query);
      $row = $partner_id_result->row_array();



      if( $partner_id_result->num_rows() > 0 ){

        if($row['c_pw'] == $partner_password ){
          $result["result"] = "success";
          $result["client_name"] = $row['client_name'];
          $result["client_idx"] = $row['client_idx'];
        } else {
          $result["result"] = "failed";
          $result["message"] = "partner_password";
        }

      }else{
        $result["result"] = "failed";
        $result["message"] = "partner_id";
      }




      return $result;
    }

    public function getTotalPrice()
    {
      $sql = "SELECT SUM(total_price) total_price FROM tb_work WHERE status = 'Q'";
      $result = $this->db->query($sql)->row_array();
      $total_price = $result['total_price'];

      $now = date("Y-m");
      $sql = "SELECT SUM(total_price) total_price FROM tb_work WHERE status = 'Q' AND DATE_FORMAT(mod_date,'%Y-%m') = '{$now}'";
      $result = $this->db->query($sql)->row_array();

      $month_total = $result['total_price'];

      $result_array = array(
        "total" =>  $total_price,
        "month_total" =>  $month_total
      );

      return $result_array;
    }

    public function getPartnerList($partner_idx,$year,$month,$status)
    {
      $yearMonth = $year."-".$month;


      if($partner_idx == '8'){ //애플비면 북스 계림 통합
        $where = " AND (work.client_idx = '9' OR work.client_idx = '10') AND DATE_FORMAT(work.reg_date,'%Y-%m') = '{$yearMonth}'";
      }else{
        $where = "AND work.client_idx = '{$partner_idx}' AND DATE_FORMAT(work.reg_date,'%Y-%m') = '{$yearMonth}'";
      }

      if($status != "all"){
        $where .= " AND work.status = '{$status}'";
      }

      $sql = "SELECT *,cl.client_name as client_title FROM tb_work work
              INNER JOIN client cl
              ON cl.client_idx = work.client_idx
              WHERE 1=1 $where ORDER BY mod_date DESC";

      $result = $this->db->query($sql)->result_array();

      return $result;
    }

    public function updateLogin($data)
    {
      $school_seq = $data['school_seq'];
      $admin_id = $data['admin_id'];
      $ip_address = $data['login_ip'];
      $last_login_time = $data['last_login_time'];

      $sql = "UPDATE tb_user SET last_login_time = '{$last_login_time}', login_ip = '{$ip_address}' WHERE user_id = '{$admin_id}'";
      $this->db->query($sql);
    }

    public function getInfoData()
    {
      $sql = "SELECT * FROM tb_info WHERE info_type = 'A'";
      $result = $this->db->query($sql)->row_array();

      return $result;
    }


    public function writeInfo($data)
    {
      $info_seq = $data['info_seq'];
      $info_type = $data['info_type'];
      $info_manual = $data['info_manual'];
      $info_movie = $data['info_movie'];
      $info_blog = $data['info_blog'];
      $manual_target = $data['manual_target'];
      $movie_target = $data['movie_target'];
      $blog_target = $data['blog_target'];
      $reg_date = date("Y-m-d H:i:s");

      $academy_seq = $this->session->userdata("academy_seq");

      if(empty($info_seq)){
        $sql = "INSERT INTO tb_info (info_manual,manual_target,info_movie,movie_target,info_blog,blog_target,info_type,academy_seq,reg_date)
                VALUES ('{$info_manual}','{$manual_target}','{$info_movie}','{$movie_target}','{$info_blog}','{$blog_target}','{$info_type}','{$academy_seq}','{$reg_date}')";
        $result = $this->db->query($sql);

      }else{
        $sql = "UPDATE tb_info SET info_manual='{$info_manual}',
        manual_target='{$manual_target}',
        info_movie='{$info_movie}',
        movie_target='{$movie_target}',
        info_blog='{$info_blog}',
        blog_target='{$blog_target}',
        reg_date='{$reg_date}' WHERE info_seq = '{$info_seq}'";
        $result = $this->db->query($sql);
      }

      return $result;
    }

    public function writeTerms($data)
    {
      $terms = $data['terms'];

      $sql = "UPDATE tb_terms SET terms='{$terms}' WHERE seq = '1'";
        $result = $this->db->query($sql);


      return $result;
    }

    //member data
    public function getMemberData($user_id)
    {
      $query = "SELECT * FROM admin_user WHERE user_id = '{$user_id}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //users 조회(관리자)
    public function getUsers($seq)
    {
      $query = "SELECT * FROM admin_user WHERE seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //admin member list total
    public function getAdminTotalCount()
    {
      $query = "SELECT count(*) cnt FROM admin_user";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];

    }

    public function getSettleTotalCount()
    {
      $query = "SELECT count(*) cnt FROM (SELECT client.* FROM client as client
                INNER JOIN tb_work as work
                ON work.client_idx = client.client_idx GROUP BY client.client_idx) A
      ";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    public function getSettleList($data,$year,$mon,$year_mon)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT client.client_name,
                (SELECT count(*) FROM tb_work WHERE status = 'R' AND client_idx = client.client_idx AND DATE_FORMAT(confirm_date,'%Y-%m') = '{$year_mon}') r_total,
                (SELECT count(*) FROM tb_work WHERE status = 'c' AND client_idx = client.client_idx AND DATE_FORMAT(confirm_date,'%Y-%m') = '{$year_mon}') c_total,
                (SELECT count(*) FROM tb_work WHERE status = 'q' AND client_idx = client.client_idx AND DATE_FORMAT(confirm_date,'%Y-%m') = '{$year_mon}') q_total,
                SUM(work.total_price) total_price,
                client.client_idx client_idx
                  FROM client as client
                INNER JOIN tb_work as work
                ON work.client_idx = client.client_idx
               WHERE 1=1 {$where} AND work.status = 'Q' GROUP BY work.client_idx ORDER BY client.client_idx ASC {$limit}";
      $result = $this->db->query($query)->result_array();

      return $result;
    }

    public function getSettleData($year,$month,$client_idx)
    {
      $year_mon = $year."-".$month;

      if($client_idx == '8' || $client_idx == '9' || $client_idx == '10'){
        if($month == 1){
          $start_year = $year-1;
          $start_month = 12;
        }else{
          $start_year = $year;
          $start_month = $month-1;
        }


        $start_day = 26;

        $start_year_month_day = date("Y-m-d",strtotime($start_year."-".$start_month."-".$start_day));
        $last_year_month_day = $year."-".$month."-25";
        $where = "AND  DATE_FORMAT(work.confirm_date,'%Y-%m-%d') >= '".$start_year_month_day."' AND DATE_FORMAT(work.confirm_date,'%Y-%m-%d') <= '".$last_year_month_day."' AND work.status = 'Q' AND work.client_idx = '{$client_idx}'";
      }else{
        $where = "AND DATE_FORMAT(confirm_date,'%Y-%m') = '{$year_mon}'
              AND work.status = 'Q'
              AND work.client_idx = '{$client_idx}'";
      }


      $sql = "SELECT work.mod_date,
                      work.product_name,
                      work.content,
                      work.amount,
                      work.work_amount,
                      work.work_amount2,
                      work.ea_price,
                      work.total_price,
                      work.etc,
                      work.dong_price,
                      work.film_price,
                      work.confirm_date,
                      client.client_name
              FROM tb_work as work
              INNER JOIN client as client
              ON client.client_idx = work.client_idx
              WHERE 1=1 $where ORDER BY work.mod_date ASC";
      $result = $this->db->query($sql)->result_array();


      return $result;
    }

    public function getSettleData2($first_date,$last_date,$client_idx)
    {


      $where = "AND DATE_FORMAT(confirm_date,'%Y-%m-%d') >= '{$first_date}' AND DATE_FORMAT(confirm_date,'%Y-%m-%d') <= '{$last_date}'
              AND work.status = 'Q'
              AND work.client_idx = '{$client_idx}'";



      $sql = "SELECT work.mod_date,
                      work.product_name,
                      work.content,
                      work.amount,
                      work.work_amount,
                      work.work_amount2,
                      work.ea_price,
                      work.total_price,
                      work.etc,
                      work.dong_price,
                      work.film_price,
                      work.confirm_date,
                      client.client_name
              FROM tb_work as work
              INNER JOIN client as client
              ON client.client_idx = work.client_idx
              WHERE 1=1 $where ORDER BY work.mod_date ASC";
      $result = $this->db->query($sql)->result_array();

      return $result;
    }

    //main banner list total
    public function getMainBannerListTotalCount()
    {
      $query = "SELECT count(*) cnt FROM main_banners";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    //user_id duplicate chk
    public function userIdDuplicateChk($user_id)
    {
      $query = "SELECT * FROM admin_user WHERE user_id = '{$user_id}'";
      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //admin member list
    public function getAdminList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT * FROM admin_user WHERE 1=1 {$where}ORDER BY seq DESC {$limit}";
      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //main banner list
    public function getMainBannerList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT * FROM main_banners WHERE 1=1 {$where}ORDER BY sort_order ASC {$limit}";

      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //get main banner
    public function getMainBanner($seq)
    {
      $query = "SELECT * FROM main_banners WHERE seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    /////////////// brand ////////////////////
    //get brand banner
    public function getBrandMain($seq)
    {
      $query = "SELECT * FROM brand_main WHERE seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //brand banner list
    public function getBrandMainList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT * FROM brand_main WHERE 1=1 {$where}ORDER BY seq DESC {$limit}";

      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //brand banner list total
    public function getBrandMainListTotalCount()
    {
      $query = "SELECT count(*) cnt FROM brand_main";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    /////////////// product main ////////////////////
    //get brand banner
    public function getProductMain($seq)
    {
      $query = "SELECT * FROM product_main WHERE seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //brand banner list
    public function getProductMainList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT * FROM product_main WHERE 1=1 {$where}ORDER BY seq DESC {$limit}";

      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //brand banner list total
    public function getProductMainListTotalCount()
    {
      $query = "SELECT count(*) cnt FROM product_main";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    public function modifyCompany($data)
    {
        $this->db->where('company_idx',"1");
        $this->db->update("company",$data);
      $result = $this->db->affected_rows();
      return $result;
    }

    public function getCompany()
    {
      $sql = "SELECT * FROM company";
      $result = $this->db->query($sql)->row_array();

      return $result;
    }

    public function insertClient($data)
    {
      $business_no = $data['business_no'];
      $sql = "SELECT count(*) cnt FROM client WHERE business_no = '{$business_no}'";
      $result = $this->db->query($sql)->row_array();

      $cnt = $result['cnt'];

      if($cnt > 0){
        $returnData = array(
          "result"  =>  "failed"
        );
      }else{
        $this->db->insert("client",$data);
        $returnData = array(
          "result"  =>  "success"
        );
      }

      return $returnData;
    }

    public function getClientData($client_idx){
      $sql = "SELECT * FROM client WHERE client_idx = '{$client_idx}'";
      $result = $this->db->query($sql)->row_array();

      return $result;
    }

    public function deleteClient($client_idx)
    {
      $sql = "DELETE FROM client WHERE client_idx = '{$client_idx}'";
      $this->db->query($sql);
    }

    public function modifyClient($data,$client_idx)
    {
      $this->db->where('client_idx',$client_idx);
      $this->db->update("client",$data);
      $result = $this->db->affected_rows();
    }

    public function insertQna($data)
    {
      $this->db->insert("qna_board",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //admin insert
    public function addAdmin($data)
    {
      $user_id = $data['user_id'];
      $user_name = $data['user_name'];
      $user_email = $data['user_email'];
      $affiliation = $data['affiliation'];
      $user_level = $data['user_level'];
      $auth_control = $data['auth_control'];
      $reg_date = date("Y-m-d H:i:s");

      //곧 수정예정
      $user_password = '1234';
      $user_password = $this->encryption->encrypt($user_password);
      $query = "INSERT INTO admin_user (user_id,user_password,user_name,user_level,reg_date,conditions,user_email,affiliation,auth_control)
                VALUES ('{$user_id}',('{$user_password}'),'{$user_name}','{$user_level}','{$reg_date}',1,'{$user_email}','{$affiliation}','{$auth_control}')";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      return $result;
    }

    public function getWorkTotalCount($where='')
    {
      $query = "SELECT count(*) cnt FROM tb_work as work
                INNER JOIN client as client
                ON client.client_idx = work.client_idx
                WHERE 1=1 {$where}";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    public function getWorkList($data)
    {
        $where = $data['where'];
        $limit = $data["limit"] == "" ? null : $data["limit"];

        $query = "SELECT work.*,client.client_name FROM tb_work as work
                  INNER JOIN client as client
                  ON client.client_idx = work.client_idx
                  WHERE 1=1 {$where}ORDER BY work.reg_date DESC {$limit}";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getWorkData($work_idx)
    {
      $sql = "SELECT * FROM tb_work WHERE work_idx = '{$work_idx}'";
      $result = $this->db->query($sql)->row_array();

      return $result;
    }

    public function getEaPrice($product_name)
    {
      $sql = "SELECT ea_price FROM tb_work WHERE replace(product_name,' ','') = '{$product_name}' AND status = 'Q' ORDER BY reg_date DESC LIMIT 1";
      $result = $this->db->query($sql)->row_array();

      $ea_price = 0;
      if(!is_array($result)){
        $ea_price = 0;
      }else{
        $ea_price = $result['ea_price'];
      }

      return $ea_price;
    }

    public function getEaPricePopFind($product_name)
    {
      $ex_text = array(
        "+",
        "-",
        "<",
        ">",
        "(",
        ")",
        "~",
        "*"
      );
      $product_name = str_replace($ex_text,"",$product_name);

      $sql = "SELECT product_name,match(product_name) AGAINST('{$product_name}') as score,ea_price FROM tb_work WHERE match(product_name) AGAINST('{$product_name}' WITH QUERY EXPANSION) > 0 AND ea_price > 0 ORDER BY score DESC LIMIT 0,10";
      $result = $this->db->query($sql)->result_array();

      return $result;
    }

    public function insertWork($data)
    {
      $this->db->insert("tb_work",$data);

      $result = $this->db->affected_rows();

      return $result;
    }

    public function changeStateWork($work_idx,$work_status)
    {
      if($work_status == "C"){
        $sql = "SELECT total_price FROM tb_work WHERE work_idx = '{$work_idx}'";
        $total_price = $this->db->query($sql)->row_array();
        if($total_price['total_price'] <= 0){
          return "failed";
        }
      }

      $mod_date = date("Y-m-d H:i:s");

      if($work_status == "C"){
        $confirm_date = date("Y-m-d H:i:s");
        $sql = "UPDATE tb_work SET status = '{$work_status}', mod_date = '{$mod_date}', confirm_date = '{$confirm_date}' WHERE work_idx = '{$work_idx}'";
      }else{
        $sql = "UPDATE tb_work SET status = '{$work_status}', mod_date = '{$mod_date}' WHERE work_idx = '{$work_idx}'";
      }


      $this->db->query($sql);

      if($work_status == "Q"){
        $sql = "SELECT * FROM tb_work WHERE work_idx = '{$work_idx}'";
        $workData = $this->db->query($sql)->row_array();

        if(empty($workData['confirm_date'])){
          $confirm_date = $workData['mod_date'];
          $sql = "UPDATE tb_work SET confirm_date = '{$confirm_date}' WHERE work_idx = '{$work_idx}'";
          $this->db->query($sql);
        }
      }

      return "success";

    }

    public function deleteWork($work_idx)
    {
      $sql = "DELETE FROM tb_work WHERE work_idx = '{$work_idx}'";
      $this->db->query($sql);
    }

    //admin update
    public function updateAdmin($data)
    {
      $seq = $data['seq'];
      $user_email = $data['user_email'];
      $affiliation = $data['affiliation'];
      $user_level = $data['user_level'];
      $auth_control = $data['auth_control'];

      $query = "UPDATE admin_user SET user_email = '{$user_email}', affiliation = '{$affiliation}', user_level = '{$user_level}', auth_control = '{$auth_control}' WHERE seq = '{$seq}'";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      //session 저장 - 메뉴 접근 권한
      $this->session->set_userdata("auth_control",explode("|",$auth_control));

      return $result;
    }

    //adminDelete
    public function deleteAdmin($seq)
    {
      $query = "DELETE FROM admin_user WHERE seq = '{$seq}'";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      return $result;
    }



    //board list
    public function getBoardList()
    {
      $query = "SELECT * FROM board_config ORDER BY seq DESC";
      $result = $this->db->query($query)->result_array();

      return $result;
    }


    //password chk
    public function getPaasswordCheck($user_id,$user_password)
    {
      $user_password = $this->encryption->encrypt($user_password);
      $query = "SELECT * FROM admin_user WHERE user_id = '{$user_id}'";
      $result = $this->db->query($query)->result_array();


      if($this->encryption->decrypt($result['user_password']) == $user_password ){
        return $result;
      } else {
         return "";
      }



    }

    //first login update
    public function updateFirstModify($arr)
    {
      $user_id = $arr['user_id'];
      $user_password = $arr['user_password'];
      $user_email = $arr['user_email'];
      $affiliation = $arr['affiliation'];
      $first_login = $arr['first_login'];

      $user_password = $this->encryption->encrypt($user_password);
      $query = "UPDATE admin_user SET user_password = ('{$user_password}'), user_email = '{$user_email}', affiliation = '{$affiliation}', first_login = '{$first_login}' WHERE user_id = '{$user_id}'";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      return $result;
    }

    //info update
    public function updateInfoModify($arr)
    {
      $user_id = $arr['user_id'];
      $user_password = $arr['user_password'];
      $user_email = $arr['user_email'];
      $affiliation = $arr['affiliation'];

      $user_password = $this->encryption->encrypt($user_password);
      $query = "UPDATE admin_user SET user_password = ('{$user_password}'), user_email = '{$user_email}', affiliation = '{$affiliation}' WHERE user_id = '{$user_id}'";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      return $result;
    }

    //mainBanner insert
    public function insertMainBanner($data)
    {
      $this->db->insert("main_banners",$data);
      $result = $this->db->affected_rows();

      return $result;

    }

    //update mainBanner
    public function updateMainBanner($data,$seq)
    {
      $this->db->where('seq',$seq);
      $this->db->update("main_banners",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    public function updateWork($data,$work_idx)
    {
      $sql = "SELECT * FROM tb_work WHERE work_idx = '{$work_idx}'";
      $workData = $this->db->query($sql)->row_array();

      $work_amount = $workData['work_amount'];

      if($work_amount == 0 && $data['work_amount'] > 0){
        $data['status'] = "C";
      }

      if($workData['status'] == $data['status']){
        $data['mod_date'] = $workData['mod_date'];
      }

      if($data['status'] == 'C'){
        $data['confirm_date'] = date("Y-m-d H:i:s");
      }

      if(empty($workData['confirm_date'])){
        if($data['status'] == "Q" || $data['status'] == "C"){
          $data["confirm_date"] = date("Y-m-d H:i:s");
        }
      }


      $this->db->where('work_idx',$work_idx);
      $this->db->update("tb_work",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //delete mainBanner
    public function deleteMainBanner($seq)
    {
      $query = "DELETE FROM main_banners WHERE seq = '{$seq}'";
      $this->db->query($query);
      $result = $this->db->affected_rows();

      return $result;
    }

    //insert brandMain
    public function insertBrandMain($data)
    {
      $this->db->insert("brand_main",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //update brandMain
    public function updateBrandMain($data,$seq)
    {
      $this->db->where('seq',$seq);
      $this->db->update("brand_main",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //delete brandMain
    public function deleteBrandMain($seq)
    {
      $query = "DELETE FROM brand_main WHERE seq = '{$seq}'";
      $this->db->query($query);
      $result = $this->db->affected_rows();

      return $result;
    }

    //insert brandMain
    public function insertProductMain($data)
    {
      $this->db->insert("product_main",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //update brandMain
    public function updateProductMain($data,$seq)
    {
      $this->db->where('seq',$seq);
      $this->db->update("product_main",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //delete brandMain
    public function deleteProductMain($seq)
    {
      $query = "DELETE FROM product_main WHERE seq = '{$seq}'";
      $this->db->query($query);
      $result = $this->db->affected_rows();

      return $result;
    }

    //qna board list total
    public function getQnaTotalCount($where='')
    {
      $query = "SELECT count(*) cnt FROM qna_board as qna
                LEFT JOIN brands as brand
                ON brand.seq = qna.brand_seq
                LEFT JOIN admin_user as adm
                ON adm.seq = qna.answer_seq WHERE 1=1 {$where}";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];

    }

    public function getClientTotalCount($where='')
    {
      $query = "SELECT count(*) cnt FROM client WHERE 1=1 {$where}";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    public function getClientDbTotalCount($where='')
    {
      $query = "SELECT count(*) cnt FROM client_db WHERE 1=1 {$where}";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    //qna list
    public function getQnaList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT qna.*,brand.brand_name,adm.user_name answer_name FROM qna_board as qna
                LEFT JOIN brands as brand
                ON brand.seq = qna.brand_seq
                LEFT JOIN admin_user as adm
                ON adm.seq = qna.answer_seq
                WHERE 1=1 {$where}ORDER BY seq DESC {$limit}";

      $result = $this->db->query($query)->result_array();

      return $result;
    }

    public function getClientList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $sql = "SELECT * FROM client WHERE 1=1 {$where} ORDER BY client_idx ASC {$limit}";
      $result = $this->db->query($sql)->result_array();

      return $result;
    }

    public function getClientDbList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $sql = "SELECT * FROM client_db WHERE 1=1 {$where} ORDER BY idx DESC {$limit}";
      $result = $this->db->query($sql)->result_array();

      return $result;
    }

    //qna
    public function getQna($seq)
    {
      $query = "SELECT qna.*,brand.brand_name,adm.user_name answer_name FROM qna_board as qna
                LEFT JOIN brands as brand
                ON brand.seq = qna.brand_seq
                LEFT JOIN admin_user as adm
                ON adm.seq = qna.answer_seq
                WHERE qna.seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //update qna
    public function updateQna($data,$seq)
    {
      $this->db->where('seq',$seq);
      $this->db->update("qna_board",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //get boards
    public function getBoards($seq)
    {
      $query = "SELECT * FROM boards WHERE seq = '{$seq}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //get boards total
    public function getBoardsTotalCount($type)
    {
      $query = "SELECT count(*) cnt FROM boards WHERE b_type = '{$type}'";
      $rows = $this->db->query($query)->row_array();

      return $rows['cnt'];
    }

    //get boards
    public function getBoardsList($data)
    {
      $where = $data['where'];
      $limit = $data["limit"] == "" ? null : $data["limit"];

      $query = "SELECT board.*,adm.user_name FROM boards board
                LEFT JOIN admin_user adm
                ON adm.seq = board.wr_seq
                WHERE 1=1 {$where}ORDER BY seq DESC {$limit}";

      $result = $this->db->query($query)->result_array();

      return $result;
    }

    //insert boards
    public function insertBoards($data)
    {
      $this->db->insert("boards",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //modify boards
    public function updateBoards($data,$seq)
    {
      $this->db->where('seq',$seq);
      $this->db->update("boards",$data);
      $result = $this->db->affected_rows();

      return $result;
    }

    //modify boards
    public function deleteBoards($seq)
    {
        $query = "DELETE FROM boards WHERE seq = '{$seq}'";
        $this->db->query($query);
        $return["result"] = "success";

        return $return;
    }



    //get session admin user
    public function getSessionUser($user_id)
    {
      $query = "SELECT * FROM admin_user WHERE user_id = '{$user_id}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //get board config
    public function getBoardConfig($board_name)
    {
      $query = "SELECT * FROM board_config WHERE board_name = '{$board_name}'";
      $result = $this->db->query($query)->row_array();

      return $result;
    }

    //board add
    public function boardAdd($dataArr)
    {
      $board_name = $dataArr["board_name"];
      $board_title = $dataArr["board_title"];
      $board_skin = $dataArr["board_skin"];
      $board_read_level = $dataArr["board_read_level"];
      $board_write_level = $dataArr["board_write_level"];
      $board_delete_level = $dataArr["board_delete_level"];
      $board_state = $dataArr["board_state"];
      $add_date = date("Y-m-d H:i:s");

      $query = "INSERT INTO board_config (board_name,board_title,board_skin,board_read_level,board_write_level,board_delete_level,board_state,add_date)
                VALUES ('{$board_name}','{$board_title}','{$board_skin}','{$board_read_level}','{$board_write_level}','{$board_delete_level}','{$board_state}','{$add_date}')";
      $this->db->query($query);

      $result = $this->db->affected_rows();

      if( $result > 0 ){
        $result = "TRUE";
      } else {
        $result = "FALSE";
      }

      return $result;
    }

    //board modify
    public function boardConfigModify($dataArr)
    {
      $board_title = $dataArr["board_title"];
      $board_skin = $dataArr["board_skin"];
      $board_read_level = $dataArr["board_read_level"];
      $board_write_level = $dataArr["board_write_level"];
      $board_delete_level = $dataArr["board_delete_level"];
      $board_state = $dataArr["board_state"];
      $seq = $dataArr["seq"];

      $query = "UPDATE board_config SET
                board_title = '{$board_title}',
                board_skin = '{$board_skin}',
                board_read_level = '{$board_read_level}',
                board_write_level = '{$board_write_level}',
                board_delete_level = '{$board_delete_level}',
                board_state = '{$board_state}' WHERE seq = '{$seq}'";
      $this->db->query($query);
      $result = $this->db->affected_rows();

      if( $result > 0 ){
        $result = "TRUE";
      } else {
        $result = "FALSE";
      }

      return $result;
    }

    //board delete
    public function boardConfigDelete($seq)
    {
      $return = array();
      //작성된 글이 있는지 확인
      $query = "SELECT seq FROM board WHERE board_seq = '{$seq}' LIMIT 1";
      $writeNums = $this->db->query($query)->num_rows();

      if( $writeNums > 0 ){
        $return["result"] = "failed";
        $return["message"] = "작성된 글이 있습니다.\n글 삭제 후 게시판 삭제가 가능합니다.";
      } else {
        $query = "DELETE FROM board_config WHERE seq = '{$seq}'";
        $this->db->query($query);
        $return["result"] = "success";
      }

      return $return;
    }

    //site site config
    public function getSiteConfig()
    {
      $sql = "SELECT * FROM config";
      $query = $this->db->query($sql)->row_array();

      return $query;
    }

    //counting
    public function getCounting()
    {
      $sql = "SELECT count(course_code) cnt FROM tb_course";
      $query = $this->db->query($sql)->row_array();

      $courseCount = $query['cnt'];

      $sql = "SELECT count(user_id) cnt FROM tb_user WHERE user_level = 7";
      $query = $this->db->query($sql)->row_array();

      $partnerCount = $query['cnt'];

      $sql = "SELECT count(user_id) cnt FROM tb_user WHERE user_level = 10";
      $query = $this->db->query($sql)->row_array();

      $studentCount = $query['cnt'];

      $sql = "SELECT count(class_code) cnt FROM tb_course_class";
      $query = $this->db->query($sql)->row_array();

      $classCount = $query['cnt'];

      $data = array(
        "courseCount" =>  $courseCount,
        "partnerCount"  =>  $partnerCount,
        "studentCount"  =>  $studentCount,
        "classCount"    =>  $classCount
      );

      return $data;
    }

    public function getTermPrivacy()
    {
      $sql = "SELECT * FROM tb_terms";
      $query = $this->db->query($sql)->row_array();

      return $query;
    }

    public function getPrivacy()
    {
      $sql = "SELECT * FROM tb_privacy";
      $query = $this->db->query($sql)->row_array();

      return $query;
    }

  }
?>
