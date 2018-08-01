<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Map Port</title>
  <link rel="shortcut icon" href="<?=base_url()?>asset/img/28_104847.ico" />

  <!-- Bootstrap core CSS-->
  <link href="<?=base_url()?>asset/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="<?=base_url()?>asset/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="<?=base_url()?>asset/css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
  <div class="container">

    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <?php echo form_open('login/index');?>
          <div class="form-group">
            <label for="InputUser">User name</label>
            <input class="form-control" id="InputUser" name="InputUser" value="<?php echo set_value('InputUser'); ?>" type="text" placeholder="Enter user name">
            <?php echo form_error('InputUser'); ?> 
          </div>

          <div class="form-group">
            <label for="InputPassword">Password</label>
            <input class="form-control" id="InputPassword" name="InputPassword" value="<?php echo set_value('InputPassword'); ?>" type="password" placeholder="Enter password" maxlength="8">
            <?php echo form_error('InputPassword'); ?>
          </div>

          <button type="submit" name="btlogin" class="btn btn-primary btn-block" value="Login"><i class="fa fa-sign-in"></i> Login</button>
        <?php echo form_close();?>
      </div>
    </div>

  </div><!-- container -->



  <!-- Bootstrap core JavaScript-->
  <script src="<?=base_url()?>asset/vendor/jquery/jquery.min.js"></script>
  <script src="<?=base_url()?>asset/vendor/popper/popper.min.js"></script>
  <script src="<?=base_url()?>asset/vendor/bootstrap/js/bootstrap.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="<?=base_url()?>asset/vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
