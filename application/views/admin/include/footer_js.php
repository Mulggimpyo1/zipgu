<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7); /* 70% 투명한 백색 배경 */
  z-index: 9999; /* 다른 요소들보다 위에 배치 */
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  border: 8px solid rgba(0, 0, 0, 0.3); /* 반투명한 검은색 테두리 */
  border-top: 8px solid #333; /* 검은색 실제 로딩바 */
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite; /* 회전 애니메이션 */
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div class="overlay" style="display:none">
  <div class="spinner"></div>
</div>
<script>
function loadView($str)
{
  if($str == "Y"){
    $('.overlay').show();
  }else{
    $('.overlay').hide();
  }
}
</script>
<?php if($depth2 == 'challenge'){ ?>
<script>
function logout()
{
  if( confirm('정말 로그아웃 하시겠습니까?') ){
    var $data = {
      "<?=$this->security->get_csrf_token_name();?>" : "<?=$this->security->get_csrf_hash();?>"
    };

    $.ajax({
      type: "POST",
      url : "/admin/logout",
      data: $data,
      dataType:"json",
      success : function(data, status, xhr) {
        if( data.result != "success" ){
          alert('오류가 발생했습니다.');
        } else {
          location.href = "/admin";
          return;
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("error : " + errorThrown);
        console.log(jqXHR.responseText);
      }
    });

  }
}
</script>
<?php }else{ ?>




<!-- jQuery UI 1.11.4 -->
<script src="{base_url}assets/admin_resources/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 4 -->
<script src="{base_url}assets/admin_resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="{base_url}assets/admin_resources/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="{base_url}assets/admin_resources/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="{base_url}assets/admin_resources/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="{base_url}assets/admin_resources/plugins/jqvmap/maps/jquery.vmap.world.js"></script>
<!-- jQuery Knob Chart -->
<script src="{base_url}assets/admin_resources/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{base_url}assets/admin_resources/plugins/moment/moment.min.js"></script>
<script src="{base_url}assets/admin_resources/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<!--
<script src="{base_url}assets/admin_resources/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
<!-- Summernote -->
<script src="{base_url}assets/admin_resources/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="{base_url}assets/admin_resources/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App-->
<script src="{base_url}assets/admin_resources/dist/js/adminlte.js"></script>

<?php if($depth1 == 'dashboard'){ ?>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{base_url}assets/admin_resources/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{base_url}assets/admin_resources/dist/js/demo.js"></script>
<?php } ?>



<script src="{base_url}assets/admin_resources/dist/js/masonry.pkgd.min.js"></script>
<script src="{base_url}assets/admin_resources/dist/js/select2.full.min.js"></script>





<script>
$(function(){
  //$('.select2').select2();
  //$('.select2').css("float","right");
});

function search($obj)
{
  $('#search_form').submit();
}

function academiSelect()
{
  var academy_seq = $('#academy_select').val();
  var csrf_name = $('#csrf').attr("name");
  var csrf_val = $('#csrf').val();

  var send_data = {
    "academy_seq" : academy_seq,
  }

  send_data[csrf_name] = csrf_val;


  $.ajax({
    type: "POST",
    url : "/admin/home/selectAcademy",
    data: send_data,
    dataType:"json",
    success : function(data, status, xhr) {
      location.reload();
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(jqXHR.responseText);
    }
  });
}

function logout()
{
  if( confirm('정말 로그아웃 하시겠습니까?') ){
    var $data = {
      "<?=$this->security->get_csrf_token_name();?>" : "<?=$this->security->get_csrf_hash();?>"
    };

    $.ajax({
      type: "POST",
      url : "/admin/logout",
      data: $data,
      dataType:"json",
      success : function(data, status, xhr) {
        if( data.result != "success" ){
          alert('오류가 발생했습니다.');
        } else {
          location.href = "/admin";
          return;
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("error : " + errorThrown);
        console.log(jqXHR.responseText);
      }
    });

  }
}
</script>
<?php } ?>
</body>
</html>
