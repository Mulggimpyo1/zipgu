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
            <!-- /.card-header -->
            <form id="search_form">
              <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>"/>
              <div class="card-body table-responsive p-0">
                  <input type="text" class="form-control col-2 float-left date" style="margin:5px 5px 5px 5px;" name="first_date" id="first_date" value="<?php echo $this->input->get('first_date'); ?>" placeholder="결산시작일"/>
                  <input type="text" class="form-control col-2 float-left date" style="margin:5px 5px 5px 5px;" name="last_date" id="last_date" value="<?php echo $this->input->get('last_date'); ?>" placeholder="결산종료일"/>
                </div>
                  <table class="table table-hover">
                    <thead>
                      <colgroup>
                        <col />
                        <col width="10%"/>
                      </colgroup>
                      <tr>
                        <th class="text-center">업체명</th>
                        <th class="text-center">관리</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( count($clientList) > 0 ){ ?>
                      {clientList}
                      <tr>
                        <td class="text-center align-middle">{client_name}</td>
                        <td class="text-center align-middle">
                          <button type="button" class="btn btn-block btn-default" onclick="goExcel('{client_idx}')">엑셀다운</button>
                          <button type="button" class="btn btn-block btn-warning" onclick="goSettle('{client_idx}')">결산</button>
                        </td>
                      </tr>
                      {/clientList}
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="2">업체가 없습니다.</td>
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
<script>
$(function(){
  $.datepicker.regional['ko'] = {
      closeText: '닫기',
      prevText: '이전달',
      nextText: '다음달',
      currentText: 'X',
      monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
      '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
      monthNamesShort: ['1월','2월','3월','4월','5월','6월',
      '7월','8월','9월','10월','11월','12월'],
      dayNames: ['일','월','화','수','목','금','토'],
      dayNamesShort: ['일','월','화','수','목','금','토'],
      dayNamesMin: ['일','월','화','수','목','금','토'],
      weekHeader: 'Wk',
      dateFormat: 'yy-mm-dd',
      firstDay: 0,
      isRTL: false,
      showMonthAfterYear: true,
      yearSuffix: ''};
     $.datepicker.setDefaults($.datepicker.regional['ko']);

  $('.date').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    yearRange: 'c-99:c+99',
    minDate: '',
    maxDate: ''
  });
});

  function goSettle($client_idx)
  {
    if($('#first_date').val() == "" ){
      alert("결산시작일을 선택해주세요");
      return;
    }

    if($('#last_date').val() == "" ){
      alert("결산종료일을 선택해주세요");
      return;
    }

    var $first_date = $('#first_date').val();
    var $last_date = $('#last_date').val();

    window.open("/admin/home/settlePop2?first_date="+$first_date+"&last_date="+$last_date+"&client_idx="+$client_idx,"popup_window","left=50 , top=50, width=985, height=1000, scrollbars=auto");
  }

  function goExcel($client_idx)
  {
    if($('#first_date').val() == "" ){
      alert("결산시작일을 선택해주세요");
      return;
    }

    if($('#last_date').val() == "" ){
      alert("결산종료일을 선택해주세요");
      return;
    }

    var $first_date = $('#first_date').val();
    var $last_date = $('#last_date').val();

    location.href = "/admin/home/settlePopExcel?first_date="+$first_date+"&last_date="+$last_date+"&client_idx="+$client_idx;
  }
</script>
