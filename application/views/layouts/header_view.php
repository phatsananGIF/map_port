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

  <!-- Bootstrap core JavaScript-->
  <script src="<?=base_url()?>asset/vendor/jquery/jquery.min.js"></script>
  <script src="<?=base_url()?>asset/vendor/popper/popper.min.js"></script>
  <script src="<?=base_url()?>asset/vendor/bootstrap/js/bootstrap.min.js"></script>


  <!-- datatables -->
  <link href="<?=base_url()?>asset/vendor/datatablesAd/dataTables.bootstrap4.css" rel="stylesheet">
  <script src="<?=base_url()?>asset/vendor/datatablesAd/jquery.dataTables.js"></script>
  <script src="<?=base_url()?>asset/vendor/datatablesAd/dataTables.bootstrap4.js"></script>


  <!-- Include Required Prerequisites -->
  <script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/moment.min.js"></script>

  <!-- Include Date Range Picker -->
  <script src="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.js"></script>
  <link href="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.css" rel="stylesheet" type="text/css"/>

  <!-- sweetalert -->
  <script src="<?=base_url()?>asset/vendor/sweetalert/sweetalert.min.js"></script>
  
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">


  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="<?=site_url()?>home">Map Port</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Server">
          <a class="nav-link" href="<?=site_url()?>home">
            <i class="fa fa-fw fa-server"></i>
            <span class="nav-link-text">Server</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Gen-config">
          <a class="nav-link" href="#">
            <i class="fa fa-fw fa-pencil-square-o"></i>
            <span class="nav-link-text">Gen-config</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add new list">
          <a class="nav-link" href="<?=site_url()?>addlist">
            <i class="fa fa-fw fa-plus"></i>
            <span class="nav-link-text">Add new list</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="System Reload">
          <a class="nav-link" href="" onClick="window.location.reload()">
            <i class="fa fa-fw fa-refresh"></i>
            <span class="nav-link-text">System Reload</span>
          </a>
        </li>
        
      </ul>

      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">

        <?php  if( $this->session->userdata("web")=='my' ){ ?>

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-user-circle-o"></i>
            <span class="nav-link-text"><?php echo $this->session->userdata("userlogin"); ?></span>
          </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">

              <a class="dropdown-item" href="<?=site_url()?>changepass">
                <i class="fa fa-fw fa-key"></i>
                <span class="nav-link-text">Change password</span>
              </a>

              <?php  if( $this->session->userdata("type")==1 && $this->session->userdata("web")=='my' ){ ?>
                <a class="dropdown-item" href="<?=base_url()?>manageuser">
                  <i class="fa fa-fw fa-users"></i>
                  <span class="nav-link-text">Manage user</span>
                </a>
              <?php  }?>

            </div>
            
        </li>
        <?php  }else{?>

        <li class="nav-item">
          <a class="nav-link">
            <i class="fa fa-fw fa-user-circle-o"></i>
            <span class="nav-link-text"><?php echo $this->session->userdata("userlogin"); ?></span>
          </a>
        </li>

        <?php  }?>

        <li class="nav-item">
          <a class="nav-link" href="<?=site_url()?>logout">
            <i class="fa fa-fw fa-sign-out"></i>
            <span class="nav-link-text">Logout</span>
          </a>
        </li>




        
      </ul>

     
    </div>
  </nav>

  <div class="content-wrapper">
   
