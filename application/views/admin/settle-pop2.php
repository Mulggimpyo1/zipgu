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
<div class="page">
<table style="margin-top:10px;width:100%;">
  <tr>
    <td class="text-center"><h1><?php echo date("Y년 m월",strtotime($last_date)); ?>거 래 명 세 서</h1></td>
  </tr>
</table>

<table style="margin-top:5px;width:100%">
  <tr>
    <td class="text-center align-middle" style="width:40%;height:50px;"><!--<h4><?php echo date("Y년 m월 d일"); ?>--></h4></td>
    <td style="width:60%;padding-right:5px;" rowspan="4" >
      <table style="border:1px solid #000;width:100%;height:100%">
        <tr>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">사업자<br>등록번호</td>
          <td class="text-center align-middle" colspan="3" style="height:50px;border:1px solid #000;"><?php echo $companyData['business_no']; ?></td>
        </tr>
        <tr>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">상 호</td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;"><?php echo $companyData['company_name']; ?></td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">대표자<br>성 명</td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;"><?php echo $companyData['owner_name']; ?></td>
        </tr>
        <tr>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">주소</td>
          <td class="text-left align-middle" colspan="3" style="height:50px;border:1px solid #000;"><?php echo $companyData['addr_1']." ".$companyData['addr_2']; ?></td>
        </tr>
        <tr>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">업 태</td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;"><?php echo $companyData['business_1']; ?></td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;">종 목</td>
          <td class="text-center align-middle" style="height:50px;border:1px solid #000;"><?php echo $companyData['business_2']; ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class="text-center align-middle" style="width:40%;height:50px;"><h4><b><?php echo $settleData[0]['client_name'] ?></b> 貴下</h4></td>
  </tr>
  <tr>
    <td class="text-center align-middle" style="width:40%;height:50px;"></td>
  </tr>
  <tr>
    <td class="text-center align-middle" style="width:40%;height:50px;"><h4>아래와 같이 청구 합니다.</h4></td>
  </tr>
</table>
<!-- body -->
  <table style="width:100%;margin-top:15px;">
    <colgroup>
      <col width="5%">
      <col width="30%">
      <col width="10%">
      <col width="8%">
      <col width="8%">
      <col width="8%">
      <col width="10%">
      <col width="10%">
    </colgroup>
    <thead style="display:table-row-group">
      <tr>
        <th class="text-center align-middle">일자</th>
        <th class="text-center align-middle">품 명</th>
        <th class="text-center align-middle">내 용</th>
        <th class="text-center align-middle">정 매</th>
        <th class="text-center align-middle">수 량</th>
        <th class="text-center align-middle">단 가</th>
        <th class="text-center align-middle">공급가액</th>
        <th class="text-center align-middle">비 고</th>
      </tr>
    </thead>
    <tbody>
      <!-- contents -->
      <?php for($i=0; $i<count($settleData); $i++){ ?>
      <tr>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['confirm_date']; ?></td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['product_name']; ?></td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['content']; ?></td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['amount']; ?></td>
        <td class="bd text-center algin-middle">
          <?php
          if($settleData[$i]['work_amount2'] > 1){
           echo $settleData[$i]['work_amount']." x ".$settleData[$i]['work_amount2'];
         }else{
           echo $settleData[$i]['work_amount'];
         }
           ?>
        </td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['ea_price']; ?></td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['total_price']; ?></td>
        <td class="bd text-center algin-middle"><?php echo $settleData[$i]['etc']; ?></td>
      </tr>
      <?php if($settleData[$i]['dong_price']>0){ ?>
        <tr>
          <td class="bd text-right align-middle" colspan="6">동판비</td>
          <td class="bd text-center align-middle"><?php echo number_format($settleData[$i]['dong_price']); ?></td>
          <td class="bd text-center align-middle"></td>
        </tr>
      <?php } ?>
      <?php if($settleData[$i]['film_price']>0){ ?>
        <tr>
          <td class="bd text-right align-middle" colspan="6">필름비</td>
          <td class="bd text-center align-middle"><?php echo number_format($settleData[$i]['film_price']); ?></td>
          <td class="bd text-center align-middle"></td>
        </tr>
      <?php } ?>
      <?php } ?>
      <?php //if($client_idx == 4){ ?>
        <!--tr>
          <td class="bd text-center algin-middle"></td>
          <td class="bd text-center algin-middle"><?php echo $etc_product_name; ?></td>
          <td class="bd text-center algin-middle"></td>
          <td class="bd text-center algin-middle"></td>
          <td class="bd text-center algin-middle">

          </td>
          <td class="bd text-center algin-middle"></td>
          <td class="bd text-center algin-middle"><?php echo number_format($etc_price); ?></td>
          <td class="bd text-center algin-middle"></td>
        </tr-->
      <?php //} ?>
      <!-- // contents -->

      <!-- bottom-->
      <tr>
        <td class="text-center align-middle" colspan="5" rowspan="3">
          <h4><?php echo $companyData['bank']; ?></h4>
        </td>
        <td class="bd text-center align-middle" style="border-left:3px solid #000;border-top:3px solid #000;">공급가액</td>
        <td class="bd text-right align-middle" colspan="2" style="border-right:3px solid #000;border-top:3px solid #000">{sumTotal}</td>
      </tr>
      <tr>
        <td class="bd text-center align-middle" style="border-left:3px solid #000;">부가세</td>
        <td class="bd text-right align-middle" colspan="2" style="border-right:3px solid #000;">{perTotal}</td>
      </tr>
      <tr>
        <td class="bd text-center align-middle" style="border-left:3px solid #000;border-bottom:3px solid #000;">합계</td>
        <td class="bd text-right align-middle" colspan="2" style="border-right:3px solid #000;border-bottom:3px solid #000;">{sumLastTotal}</td>
      </tr>
      <!-- bottom -->
    </tbody>
  </table>
  <!-- // body -->
</div>

<script>
$(function(){
  window.print();
});
</script>
