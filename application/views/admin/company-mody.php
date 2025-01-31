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
  <form id="companyForm">
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
                      <input type="text" class="form-control col-md-12 float-left" id="company_name" name="company_name" value="{company_name}" placeholder="회사명"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="business_no" name="business_no" value="{business_no}" placeholder="사업자번호"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="owner_name" name="owner_name" value="{owner_name}" placeholder="대표자"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="business_1" name="business_1" value="{business_1}" placeholder="업태"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="business_2" name="business_2" value="{business_2}" placeholder="종목"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="phone" name="phone" value="{phone}" placeholder="전화번호"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="fax" name="fax" value="{fax}" placeholder="팩스"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="bank" name="bank" value="{bank}" placeholder="계좌내용"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-sm-3 float-left" id="zipcode" name="zipcode" value="{zipcode}" onclick="findAddress()" placeholder="우편번호" />
                      <button type="button" class="btn btn-block btn-default float-left col-sm-1" style="margin-left:5px;" onclick="findAddress()">주소검색</button>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="addr_1" name="addr_1" value="{addr_1}" placeholder="주소"/>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-left align-middle">
                      <input type="text" class="form-control col-md-12 float-left" id="addr_2" name="addr_2" value="{addr_2}" placeholder="상세주소"/>
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
                      <button type="button" class="btn btn-primary float-left mr-3" onclick="modiCompany()">저장</button>
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
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<script>
  $(function(){

  });

  function modiCompany()
  {
    var company_name = $('#company_name').val();
    var business_no = $('#business_no').val();
    var owner_name = $('#owner_name').val();
    var business_1 = $('#business_1').val();
    var business_2 = $('#business_2').val();
    var phone = $('#phone').val();
    var fax = $('#fax').val();
    var bank = $('#bank').val();
    var zipcode = $('#zipcode').val();
    var addr_1 = $('#addr_1').val();
    var addr_2 = $('#addr_2').val();

    var csrf_name = $('#csrf').attr("name");
    var csrf_val = $('#csrf').val();

    var form = $('#companyForm')[0];
    var formData = new FormData($('#companyForm')[0]);

    $.ajax({
      type: "POST",
      url : "/admin/home/companyModyProc",
      data: formData,
      dataType:"json",
      processData: false,
      contentType: false,
      success : function(data, status, xhr) {

        if( data.result == "success" ){
          alert("수정 되었습니다.");
          location.reload();
        } else {
          alert("오류발생!!");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
      }
    });

  }

  function findAddress()
  {
        new daum.Postcode({
            oncomplete: function(data) {

                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }

                } else {
                    document.getElementById("addr_1").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('zipcode').value = data.zonecode;
                document.getElementById("addr_1").value = addr+extraAddr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("addr_2").focus();
            }
        }).open();
    }
</script>
