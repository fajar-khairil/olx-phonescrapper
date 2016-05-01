<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo e($container->base_uri); ?>/themes/default/img/avatar04.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>Alexander Pierce</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
    
  <!-- <li>
        <a href="">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span> 
        </a>
      </li> -->
      
      <li class="treeview">
        <a href="javascript:void(0);">
          <i class="fa fa-gears"></i> <span>Scraping</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="<?php echo e($container->base_uri); ?>/scrap">OLX</a>
          </li>
        </ul>
      </li>
    
      <li>
        <a href="lists">
          <i class="fa fa-th-list"></i> <span>Results</span>
        </a>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>