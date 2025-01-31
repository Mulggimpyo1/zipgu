<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/admin" class="brand-link text-center "  >
    <span class="brand-text font-weight-light">한성피앤비</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview menu-open">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>
              관리
              <i class="right fas fa-angle-down"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if($this->session->userdata("admin_level") == 1){ ?>
            <li class="nav-item">
              <a href="/admin/companyMody" class="nav-link <?php echo $depth2=="company" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>본사관리</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/clientList" class="nav-link <?php echo $depth2=="client" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>업체관리</p>
              </a>
            </li>
            <?php } ?>
            <li class="nav-item">
              <a href="/admin/workList" class="nav-link <?php echo $depth2=="work" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>작업내역</p>
              </a>
            </li>
            <?php if($this->session->userdata("admin_level") == 1){ ?>
            <li class="nav-item">
              <a href="/admin/settleList" class="nav-link <?php echo $depth2=="settle" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>결산</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/settleList2" class="nav-link <?php echo $depth2=="settle2" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>선택결산</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/home/clientDbList" class="nav-link <?php echo $depth2=="db" ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>제안</p>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
