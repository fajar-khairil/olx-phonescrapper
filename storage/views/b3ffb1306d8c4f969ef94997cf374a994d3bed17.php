<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Scrapper 1.0</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo e($container->base_uri); ?>/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e($container->base_uri); ?>/font-awesome/css/font-awesome.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e($container->base_uri); ?>/themes/default/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo e($container->base_uri); ?>/themes/default/css/skins/skin-blue.min.css">
    <meta name="x-base-uri" value="<?php echo e($container->base_uri); ?>"/>
    <?php echo $container->PageHelper->renderStyles();; ?>

  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
      
      <?php echo $__env->make('default::header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <?php echo $__env->make('default::sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <?php echo $__env->yieldContent('content'); ?>
      </div><!-- /.content-wrapper -->

      <?php echo $__env->make('default::footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo e($container->base_uri); ?>/js/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo e($container->base_uri); ?>/js/hade-core.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo e($container->base_uri); ?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo e($container->base_uri); ?>/js/jquery.slimscroll.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo e($container->base_uri); ?>/themes/default/js/app.min.js"></script>
    <script src="<?php echo e($container->base_uri); ?>/js/pace.js"></script>
    <?php echo $container->PageHelper->renderScripts();; ?>

  </body>
</html>
