<!DOCTYPE html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8 ">
  <title>Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->


<!--
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
  />
-->
<script src="https://kit.fontawesome.com/ab06f23d17.js" crossorigin="anonymous"></script>
  <link rel="shortcut icon" type="image/x-icon" href="/assets/common/images/hrdlms_ico.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.standalone.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/plugins/summernote/summernote-bs4.css">
  <!--@import url(https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap);-->
  <link rel="stylesheet" href="{base_url}assets/admin_resources/dist/css/select2.min.css">
  <!-- jQuery
  <script src="/assets/admin_resources/plugins/jquery/jquery.min.js"></script>
-->
<!-- jQuery -->
<script src="{base_url}assets/admin_resources/plugins/jquery/jquery.min.js"></script>


  <!-- Google Font: Source Sans Pro -->
  <!--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">-->
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:300,400,400i,700" rel="stylesheet">
  <style>
.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 37px;
    user-select: none;
    -webkit-user-select: none;
    margin-left:5px;
    margin-top:5px;
}

.keyword {padding:5px;border:1px solid #ddd;}
.keywords {padding-left:20px;padding-top:20px;float:left;}
.small_table{
  font-size:12px !important;
}



  </style>




  <style>body {color:#222; font-family:'Noto Sans KR', sans-serif; letter-spacing:-0.025em}</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <input type="hidden" id="csrf" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>"/>
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/admin/" class="nav-link">HOME</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:logout()" class="nav-link">LOGOUT</a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->
