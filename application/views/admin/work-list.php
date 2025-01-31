<style>
table td {
  font-size: 0.9rem!important;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="float-left">{title}</h1>

        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">{sub_title}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <span class="float-right">
              <div style="width:130px;float:right">
                <button type="button" class="btn btn-block btn-success" onclick="writeWork()">작업등록</button>

              </div>
            </span>
            <!-- /.card-header -->
            <form id="search_form">
              <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>"/>
              <div class="card-body table-responsive p-0">
                <div class="form-group"<?php if($this->CONFIG_DATA['device']=="mobile"){  ?>style="width:1100px;"<?php }?>>
                  <input type="hidden" id="work_status" name="work_status" value=""/>
                  <button type="button" class="btn btn-primary float-right" style="margin:5px 5px 5px 5px;" id="srcBtn" onclick="search(this)">검색</button>
                  <input type="text" class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="srcN" id="srcN" value="<?php echo $this->input->get('srcN'); ?>"/>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="srcType" id="srcType">
                    <option value="all" <?php echo ($srcType=="all") ? "selected": ""; ?>>전체</option>
                    <option value="client_name" <?php echo ($srcType=="client_name") ? "selected": ""; ?>>업체명</option>
                    <option value="product_name" <?php echo ($srcType=="product_name") ? "selected": ""; ?>>상품명</option>
                    <option value="film_no" <?php echo ($srcType=="film_no") ? "selected": ""; ?>>필름번호</option>
                  </select>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="status" id="status">
                    <option value="all" <?php echo ($status=="all") ? "selected": ""; ?>>전체상태</option>
                    <option value="R" <?php echo ($status=="R") ? "selected": ""; ?>>작업대기</option>
                    <option value="P" <?php echo ($status=="P") ? "selected": ""; ?>>작업중</option>
                    <option value="C" <?php echo ($status=="C") ? "selected": ""; ?>>작업완료</option>
                    <option value="Q" <?php echo ($status=="Q") ? "selected": ""; ?>>납품완료</option>
                  </select>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="f_month" id="f_month">
                    <option value="" <?php echo ($f_month=="") ? "selected": ""; ?>>전체달</option>
                    <?php
                      for($i=1; $i<=12; $i++){
                        $mon = ($i<10) ? "0".$i : $i;
                    ?>
                    <option value="<?php echo $mon; ?>" <?php echo ($f_month==$mon) ? "selected":"" ?>><?php echo $mon; ?></option>
                    <?php
                      }
                    ?>
                  </select>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="f_year" id="f_year">
                    <option value="" <?php echo ($f_year=="") ? "selected": ""; ?>>전체년도</option>
                    <?php
                      for($i=2016; $i<=date("Y"); $i++){
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo ($f_year==$i) ? "selected":"" ?>><?php echo $i ?></option>
                    <?php
                      }
                    ?>
                  </select>

                  <span style="float:left; margin:15px 5px 5px 15px; font-weight:bold">총 {total_works}건</span>
                </div>
                  <table class="table table-hover" <?php if($this->CONFIG_DATA['device']=="mobile"){  ?>style="width:1100px;"<?php }?>>
                    <thead>
                      <colgroup>
                        <col width="3%"/>
                        <col width="8%"/>
                        <col />
                        <col width="8%"/>
                        <col width="10%"/>
                        <col width="8%"/>
                        <col width="8%"/>
                        <col width="8%"/>
                        <col width="10%"/>
                        <col width="10%"/>
                        <col width="5%"/>
                      </colgroup>
                      <tr>
                        <th class="text-center"><input type="checkbox" id="allCheck"></th>
                        <th class="text-center">상태</th>
                        <th class="text-center">품목</th>
                        <th class="text-center">필름No</th>
                        <th class="text-center">업체명</th>
                        <th class="text-center">작업내용</th>
                        <th class="text-center">정매</th>
                        <th class="text-center">작업수량</th>
                        <th class="text-center">등록일</th>
                        <th class="text-center">수정일</th>
                        <th class="text-center">관리</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( count($workList) > 0 ){ ?>
                      {workList}
                      <tr>
                        <td class="text-center align-middle"><input type="checkbox" name="chk[]" value="{work_idx}"></td>

                        <td class="text-center align-middle"><a href="javascript:goModify('{work_idx}')">{status}</a></td>
                        <td class="text-center align-middle">{product_name}</td>
                        <td class="text-center align-middle">{film_no}</td>
                        <td class="text-center align-middle">{client_name}</td>
                        <td class="text-center align-middle">{content}</td>
                        <td class="text-center align-middle">{amount}</td>
                        <td class="text-center align-middle">{work_amount}</td>
                        <td class="text-center align-middle">{reg_date}</td>
                        <td class="text-center align-middle">{mod_date}</td>
                        <td class="text-center align-middle"><button type="button" class="btn btn-block btn-sm btn-warning" onclick="goModify('{work_idx}')">수정</button></td>
                      </tr>
                      {/workList}
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="10">작업내역이 없습니다.</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
              </div>
            </form>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <ul class="pagination pagination-sm m-0 float-left">
                {paging}
                {no}
                {/paging}
              </ul>
              <span class="float-right">
                <button type="button" class="btn btn-block btn-success" onclick="writeWork()">작업등록</button>
              </span>
              <!--
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="workExcelDown()">엑셀다운로드</button>
              </span>
            -->
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="choiceStateChange('R')">선택작업대기</button>
              </span>
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="choiceStateChange('P')">선택작업중</button>
              </span>
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="choiceStateChange('C')">선택작업완료</button>
              </span>
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="choiceStateChange('Q')">선택납품완료</button>
              </span>
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="workDelete()">선택삭제</button>
              </span>
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-warning" onclick="workExcelWrite()">엑셀등록</button>
              </span>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<form id="excel_down_form" method="POST" action="/admin/home/workDownLoad">
  <input type="hidden" name="srcN_excel" id="srcN_excel"/>
  <input type="hidden" name="srcType_excel" id="srcType_excel"/>
  <input type="hidden" name="status_excel" id="status_excel"/>
  <input type="hidden" name="f_year_excel" id="f_year_excel"/>
  <input type="hidden" name="f_month_excel" id="f_month_excel"/>
  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>"/>
</form>
<script>
$(function(){
    $('#allCheck').on("click",function(){
      allCheckClick();
    });
});

function allCheckClick()
{
  if($('#allCheck').is(":checked") == true ){
    $('input[name="chk[]"]').prop("checked",true);
  }else{
    $('input[name="chk[]"]').prop("checked",false);
  }
}

function choiceStateChange($str)
{
  var chkBool = false;
  $('input[name="chk[]"]').each(function(){
    if($(this).is(":checked")){
      chkBool = true;
      return;
    }
  });


  if(chkBool == false){
    alert("선택변경 작업을 선택해주세요.");
    return;
  }

  $('#work_status').val($str);

  var csrf_name = $('#csrf').attr("name");
  var csrf_val = $('#csrf').val();

  var data = $('#search_form').serialize();


  data[csrf_name] = csrf_val;


  if(confirm("상태를 변경하시겠습니까?")){
    $.ajax({
      type: "POST",
      url : "/admin/home/updateWorkStatus",
      data: data,
      dataType:"json",
      success : function(data, status, xhr) {
        if(data.result=="success"){
          alert("작업 상태가 변경되었습니다.");
        }else{
          alert("최종금액이 입력되지 않아서 상태변경을 할 수 없습니다.");
          return;
        }

        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });
  }


}

function workDelete()
{
  var chkBool = false;
  $('input[name="chk[]"]').each(function(){
    if($(this).is(":checked")){
      chkBool = true;
      return;
    }
  });


  if(chkBool == false){
    alert("삭제할 작업을 선택해주세요.");
    return;
  }

  var csrf_name = $('#csrf').attr("name");
  var csrf_val = $('#csrf').val();

  var data = $('#search_form').serialize();


  data[csrf_name] = csrf_val;


  if(confirm("정말 삭제 하시겠습니까?")){
    $.ajax({
      type: "POST",
      url : "/admin/home/deleteWorks",
      data: data,
      dataType:"json",
      success : function(data, status, xhr) {
        if(data.result=="success"){
          alert("삭제 되었습니다.");
        }

        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });
  }
}

  function writeWork()
  {
    location.href="/admin/home/workWrite?num={num}&srcN={srcN}&srcType={srcType}&status={status}&f_year={f_year}&f_month={f_month}";
  }

  function goModify($work_idx)
  {
    location.href="/admin/home/workModify/"+$work_idx+"?num={num}&srcN={srcN}&srcType={srcType}&status={status}&f_year={f_year}&f_month={f_month}";
  }

  function workExcelWrite()
  {
    window.open("/admin/home/workExcelPop","popup_window","left=50 , top=50, width=985, height=500, scrollbars=auto");
  }

  function workExcelDown()
  {
    $('#srcN_excel').val($('#srcN').val());
    $('#srcType_excel').val($('#srcType').val());
    $('#status_excel').val($('#status').val());
    $('#f_year_excel').val($('#f_year').val());
    $('#f_month_excel').val($('#f_month').val());

    $('#excel_down_form').submit();
  }
</script>
