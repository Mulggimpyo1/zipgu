<link href="/css/dash.css" rel="stylesheet">
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<style>
.widget-numbers {
  font-size : 1.5rem!important;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{title}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">{sub_title}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="mb-3 card col-sm-12 col-md-12 col-xl-12 mr-3">
        <div class="card-header-tab card-header">
          <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
            <i class="header-icon lnr-charts icon-gradient bg-happy-green"> </i>
            매출
          </div>

        </div>
        <div class="no-gutters row">
        <div class="col-sm-6 col-md-4 col-xl-4">
          <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
            <div class="icon-wrapper rounded-circle">
              <div class="icon-wrapper-bg opacity-10 bg-warning"></div>
              <i class="fas fa-users"></i>
            </div>
            <div class="widget-chart-content">
              <div class="widget-subheading">전체매출</div>
              <div class="widget-numbers"><?php echo number_format($datas['total'])."원"; ?></div>
            </div>
          </div>
          <div class="divider m-0 d-md-none d-sm-block"></div>
        </div>
        <div class="col-sm-6 col-md-4 col-xl-4">
          <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
            <div class="icon-wrapper rounded-circle">
              <div class="icon-wrapper-bg opacity-9 bg-danger"></div>
              <i class="fas fa-user text-white"></i>
            </div>
            <div class="widget-chart-content">
              <div class="widget-subheading"><?php echo date("m")."월 매출"; ?></div>
              <div class="widget-numbers"><span><?php echo number_format($datas['month_total'])."원"; ?></span></div>
              <div class="widget-description opacity-8 text-focus">
                <!--저번달 대비:
                <span class="text-danger pl-1">
                <i class="fa fa-angle-up"></i>
                 down
                <span class="text-primary pl-1">
                <i class="fa fa-angle-down"></i>

                <span class="pl-1">14.1%</span>
                -->
                </span>
              </div>
            </div>
          </div>
          <div class="divider m-0 d-md-none d-sm-block"></div>
        </div>
      </div>
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
