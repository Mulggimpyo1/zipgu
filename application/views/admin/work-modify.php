<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{title}</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <form id="workForm">
    <input type="hidden" id="work_idx" name="work_idx" value="{work_idx}" />
    <input type="hidden" id="csrf" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>"/>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
              <table class="table table-hover">
                <colgroup>
                  <col/>
                </colgroup>
                <tbody>
                  <tr>
                    <td class="text-left align-middle">
                      <select class="form-control col-5 float-left select2" style="clear:both;margin:5px 5px 5px 5px;" name="client_idx" id="client_idx">
                        <option value="">업체선택</option>
                        <?php for($i=0; $i<count($clientList); $i++){ ?>
                        <option value="<?php echo $clientList[$i]['client_idx']; ?>" <?php echo $workData['client_idx']==$clientList[$i]['client_idx'] ? "selected":"" ?>><?php echo $clientList[$i]['client_name']; ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="product_name"><small>제품명</small></label>
                      <input type="text" class="form-control col-md-12 float-left" id="product_name" name="product_name" value="<?php echo $workData['product_name']; ?>" placeholder="제품명"/>

                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="op_no"><small>수주번호</small></label>
                      <input type="text" class="form-control col-md-12 float-left" value="<?php echo $workData['op_no']; ?>" id="op_no" name="op_no" placeholder="수주번호"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="film_no"><small>필름번호</small></label>
                      <input type="text" class="form-control col-md-12 float-left" id="film_no" name="film_no" value="<?php echo $workData['film_no']; ?>" placeholder="필름번호"/>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="ea_price" style="clear:both;float:left;"><small>단가</small></label>
                      <input type="tel" class="form-control col-md-2" style="clear:both;float:left;" id="ea_price" name="ea_price" value="<?php echo $workData['ea_price']; ?>" placeholder="단가" onkeyup="sumTotal()" onfocus="zeroChk(this);"/>
                      <button type="button" class="btn btn-default col-md-2 float-left ml-3" onclick="goEaPrice()">단가불러오기</button>
                      <button type="button" class="btn btn-warning col-md-2 float-left ml-3" onclick="goEaPricePop()">단가찾기</button>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="content"><small>작업내용</small></label>
                      <input type="text" class="form-control col-md-12 float-left" id="content" name="content" value="<?php echo $workData['content']; ?>" placeholder="작업내용"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="amount"><small>정매</small></label>
                       <?php if($this->session->userdata("admin_level") == 2){ ?>
                         <input type="tel" readonly class="form-control col-md-12 float-left" id="amount" name="amount" value="<?php echo $workData['amount']; ?>" placeholder="정매" onkeyup="sumTotal()" onfocus="zeroChk(this);"/>
                       <?php }else{ ?>
                         <input type="tel" class="form-control col-md-12 float-left" id="amount" name="amount" value="<?php echo $workData['amount']; ?>" placeholder="정매" onkeyup="sumTotal()" onfocus="zeroChk(this);"/>
                       <?php } ?>

                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="work_amount"><small>작업수량</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="work_amount" name="work_amount" value="<?php echo $workData['work_amount']; ?>" placeholder="작업수량" onfocus="zeroChk(this);"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <label for="work_amount2"><small>작업 부수</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="work_amount2" name="work_amount2" value="<?php echo $workData['work_amount2']; ?>" placeholder="작업 부수" onfocus="zeroChk(this);"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle" style="display:none;">
                      <label for="dong_amount"><small>동판갯수</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="dong_amount" name="dong_amount" value="<?php echo $workData['dong_amount']; ?>" placeholder="동판갯수" onfocus="zeroChk(this);"/>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="dong_price"><small>동판비</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="dong_price" name="dong_price" placeholder="동판비" value="<?php echo $workData['dong_price']; ?>" onkeyup="sumTotal()" onfocus="zeroChk(this);"/>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="film_price"><small>필름비</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="film_price" name="film_price" placeholder="필름비" value="<?php echo $workData['film_price']; ?>" onkeyup="sumTotal()" onfocus="zeroChk(this);"/>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="total_price"><small>최종금액</small></label>
                      <input type="tel" class="form-control col-md-12 float-left" id="total_price" name="total_price" value="<?php echo $workData['total_price']; ?>" placeholder="최종 금액" onfocus="zeroChk(this);" onkeyup="etcChk()"/>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="etc"><small>비고</small></label>
                      <input type="text" class="form-control col-md-12 float-left" id="etc" name="etc" value="<?php echo $workData['etc']; ?>" placeholder="비고"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <select class="form-control col-md-3 float-left" id="status" name="status">
                        <option value="">상태</option>
                        <option value="R" <?php echo $workData['status']=="R" ? "selected": ""; ?>>작업대기</option>
                        <option value="P" <?php echo $workData['status']=="P" ? "selected": ""; ?>>작업중</option>
                        <option value="C" <?php echo $workData['status']=="C" ? "selected": ""; ?>>작업완료</option>
                        <option value="Q" <?php echo $workData['status']=="Q" ? "selected": ""; ?>>납품완료</option>
                      </select>
                    </td>
                  </tr>
                  <tr style="<?php echo $this->session->userdata("admin_level") == 2 ? "display:none;":"" ?>">
                    <td class="text-left align-middle">
                      <label for="etc"><small>완료일</small></label>
                      <input type="text" class="form-control col-md-12 float-left" id="confirm_date" name="confirm_date" value="<?php echo $workData['confirm_date']; ?>" placeholder="완료일"/>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

            <!-- /.card-header -->
              <table class="table">
                <tbody>
                  <tr>
                    <td class="text-left align-middle">
                      <button type="button" class="btn btn-primary float-left mr-3" onclick="modifyWork()">수정</button>
                      <button type="button" class="btn btn-warning float-left mr-3" onclick="deleteWork()">삭제</button>
                      <button type="button" class="btn btn-default float-left mr-3" onclick="goList()">목록</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  </form>

</div>
<!-- /.content-wrapper -->

<script>
var etcText = "<?php echo $workData['etc'] ?>";
  $(function(){
    $('.select2').select2();
  });

  function zeroChk($obj)
  {
    if($($obj).val() == "0"){
      $($obj).val("");
    }
  }

  function sumTotal()
  {
    var ea_price = Number($('#ea_price').val());
    var amount = Number($('#amount').val());
    var dong_price = Number($('#dong_price').val());
    var film_price = Number($('#film_price').val());

    var total = ea_price * amount + (dong_price + film_price);

    $('#total_price').val(total);


  }

  function etcChk(){
    var ea_price = Number($('#ea_price').val());
    var total_price = Number($('#total_price').val());
    if(etcText == ""){
      if(ea_price == 0 && total_price > 0){
        $('#etc').val("기본 "+total_price+"원");
      }
    }
  }

  function modifyWork()
  {
    var client_idx = $('#client_idx').val();
    var status = $('#status').val();
    var amount = $('#amount').val();
    var product_name = $('#product_name').val();



    if( client_idx == ''){
      alert("업체를 선택해주세요.");
      return;
    }

    if( product_name == ''){
      alert("제품명을 작성해주세요.");
      return;
    }

    if( amount == '' || amount == '0'){
      alert("정매를 작성해주세요.");
      return;
    }

    if( status == ''){
      alert("상태를 선택해주세요.");
      return;
    }

    var csrf_name = $('#csrf').attr("name");
    var csrf_val = $('#csrf').val();

    var form = $('#workForm')[0];
    var formData = new FormData($('#workForm')[0]);

    $.ajax({
      type: "POST",
      url : "/admin/home/workModifyProc",
      data: formData,
      dataType:"json",
      processData: false,
      contentType: false,
      success : function(data, status, xhr) {

        if( data.result == "success" ){
          alert("수정 되었습니다.");
          location.href = "/admin/home/workList?srcType={srcType}&srcN={srcN}&num={num}&f_year={f_year}&f_month={f_month}&param={param}";
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });

  }

  function goEaPrice()
  {
    var product_name = $('#product_name').val();

    if(product_name == ""){
      alert("제품명을 입력하세요.");
      return;
    }

    var csrf_name = $('#csrf').attr("name");
    var csrf_val = $('#csrf').val();

    var form = $('#workForm')[0];
    var formData = new FormData($('#workForm')[0]);

    $.ajax({
      type: "POST",
      url : "/admin/home/getEaPrice",
      data: formData,
      dataType:"json",
      processData: false,
      contentType: false,
      success : function(data, status, xhr) {
        var ea_price = data.result;
        if(ea_price == "0"){
          alert("등록된 단가가 업습니다.");
        }else{
          alert("최근 단가로 적용합니다.");
          $('#ea_price').val(ea_price);
          sumTotal();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });
  }

  function selectEaPrice($val)
  {
    alert("단가적용");
    $('#ea_price').val($val);
    sumTotal();
  }

  function goEaPricePop()
  {
    var product_name = $('#product_name').val();

    if(product_name == ""){
      alert("제품명을 입력후 눌러주세요.");
      return;
    }

    window.open("/admin/home/eaPricePop?product_name="+encodeURIComponent(product_name),"popup_window","left=50 , top=50, width=985, height=500, scrollbars=auto");
  }

  function deleteWork()
  {
    var csrf_name = $('#csrf').attr("name");
    var csrf_val = $('#csrf').val();

    var form = $('#workForm')[0];
    var formData = new FormData($('#workForm')[0]);

    $.ajax({
      type: "POST",
      url : "/admin/home/deleteWork",
      data: formData,
      dataType:"json",
      processData: false,
      contentType: false,
      success : function(data, status, xhr) {
        alert("삭제 되었습니다.");
        location.href = "/admin/home/workList?srcType={srcType}&srcN={srcN}&num={num}&f_year={f_year}&f_month={f_month}&param={param}";
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });
  }

  function goList(){
    location.href = "/admin/home/workList?srcType={srcType}&srcN={srcN}&num={num}&f_year={f_year}&f_month={f_month}&param={param}";
  }
</script>
