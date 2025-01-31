<!-- Content Wrapper. Contains page content -->
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div>
          <h1 class="float-left">제품명 : {title}</h1>

        </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <thead>
                      <colgroup>
                        <col />
                        <col width="20%"/>
                      </colgroup>
                      <tr>
                        <th class="text-center">제품명</th>
                        <th class="text-center">단가</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( count($eaPriceData) > 0 ){ ?>
                      {eaPriceData}
                      <tr>
                        <td class="text-center align-middle">{product_name}</td>
                        <td class="text-center align-middle">{ea_price}</td>
                        <td class="text-center align-middle"><button type="button" class="btn btn-block btn-sm btn-warning" onclick="goSelect('{ea_price}')">적용</button></td>
                      </tr>
                      {/eaPriceData}
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="10">등록된 제품이 없습니다.</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
              </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <!--
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="workExcelDown()">엑셀다운로드</button>
              </span>
            -->
              <span class="float-right" style="margin-right:5px;">
                <button type="button" class="btn btn-block btn-default" onclick="window.close();">닫기</button>
              </span>
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
function goSelect($ea_price)
{
  opener.parent.selectEaPrice($ea_price);
  window.close();
}
</script>
