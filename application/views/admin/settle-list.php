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
                <div class="form-group"<?php if($this->CONFIG_DATA['device']=="mobile"){  ?>style="width:1100px;"<?php }?>>
                  <input type="hidden" id="work_status" name="work_status" value=""/>
                  <button type="button" class="btn btn-primary float-right" style="margin:5px 5px 5px 5px;" id="srcBtn" onclick="search(this)">검색</button>
                  <input type="text" class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="srcN" id="srcN" value="<?php echo $this->input->get('srcN'); ?>"/>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="month" id="month">
                    <option value="" <?php echo ($month=="") ? "selected": ""; ?>>전체달</option>
                    <?php
                      for($i=1; $i<=12; $i++){
                        $mon = ($i<10) ? "0".$i : $i;
                    ?>
                    <option value="<?php echo $mon; ?>" <?php echo ($month==$mon) ? "selected":"" ?>><?php echo $mon; ?></option>
                    <?php
                      }
                    ?>
                  </select>
                  <select class="form-control col-1 float-right" style="margin:5px 5px 5px 5px;" name="year" id="year">
                    <option value="" <?php echo ($year=="") ? "selected": ""; ?>>전체년도</option>
                    <?php
                      for($i=2016; $i<=date("Y"); $i++){
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo ($year==$i) ? "selected":"" ?>><?php echo $i ?></option>
                    <?php
                      }
                    ?>
                  </select>

                  <span style="float:left; margin:15px 5px 5px 15px; font-weight:bold">총 {total_client}건</span>
                </div>
                  <table class="table table-hover" <?php if($this->CONFIG_DATA['device']=="mobile"){  ?>style="width:1100px;"<?php }?>>
                    <thead>
                      <colgroup>
                        <col />
                        <col width="8%"/>
                        <col width="8%"/>
                        <col width="8%"/>
                        <col width="15%"/>
                        <col width="10%"/>
                      </colgroup>
                      <tr>
                        <th class="text-center">업체명</th>
                        <th class="text-center">작업대기</th>
                        <th class="text-center">작업완료</th>
                        <th class="text-center">납품완료</th>
                        <th class="text-center">결산금액</th>
                        <th class="text-center">관리</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( count($settleList) > 0 ){ ?>
                      {settleList}
                      <tr>
                        <td class="text-center align-middle">{client_name}</td>
                        <td class="text-center align-middle">{r_total}</td>
                        <td class="text-center align-middle">{c_total}</td>
                        <td class="text-center align-middle">{q_total}</td>
                        <td class="text-center align-middle">{total_price}</td>
                        <td class="text-center align-middle">
                          <button type="button" class="btn btn-block btn-warning" onclick="goSettle('<?php echo $year ?>','<?php echo $month ?>','{client_idx}')">결산</button>
                        </td>
                      </tr>
                      {/settleList}
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="6">결산내역이 없습니다.</td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td class="text-right align-middle" colspan="5">총 : </td>
                        <td class="text-left align-middle"><h5><span style="color:red">{sumTotal}</span></h5></td>
                      </tr>
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

});

  function goSettle($year,$month,$client_idx)
  {
    window.open("/admin/home/settlePop?year="+$year+"&month="+$month+"&client_idx="+$client_idx,"popup_window","left=50 , top=50, width=985, height=1000, scrollbars=auto");
  }
</script>
