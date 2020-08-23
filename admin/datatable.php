<?php 
 session_start();
 if(!isset($_SESSION["current_admin"])) {
   header('Location: ../index.php');
 }
  $data = "";
  $columns = "";
  $buttons="";
  include("../DB-Connection/property.php");
  include("../DB-Connection/login.php");
  include("../DB-Connection/personal_data.php");

  if(isset($_POST["back"])) {
    header('Location: index.php');
  }
  if(isset($_POST["deleteProperty"])) {
    deleteProposalById($_POST['deleteProperty']);
  }
  else if(isset($_POST["viewProperty"])) {
    header('Location: ../property_register.php?view=1&propertyid='.$_POST["viewProperty"]);
  }
  else if(isset($_POST["viewPost"])) {
    header('Location: ../post_register.php?view=true&id='.$_POST["viewPost"]);
  }
  else if(isset($_POST["viewContract"])) {
    $_SESSION["adminAccess"] = true;
    header('Location: ../Contract.php?id='.$_POST["viewContract"].'&isOwner=y&noConfirm=y');
  }
  else if(isset($_POST["confirmProperty"])) {
    setPropertyActiveById($_POST["confirmProperty"]);
  }
  else if(isset($_POST["approveUser"])) {
    approveUser($_POST["approveUser"],1);
  }
  else if (isset($_POST["approvePost"])) {
    approvePost($_POST["approvePost"]);
  }
  else if(isset($_POST["deleteUser"])){
    deleteUserById($_POST["deleteUser"]);
  }
  else if(isset($_POST["rejectProperty"])) {
    rejectPropertyById($_POST["rejectProperty"]);
  }
  else if(isset($_POST["rejectContract"])) {
    rejectContract($_POST["rejectContract"]);
  }
  else if(isset($_POST["deletePost"])){
    deletePostById($_POST["deletePost"]);
  }
  if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['id'])) {
		  	$id = $queries['id'];
			$post_detail = getPostById($id);
			$user_detail = getUserById($post_detail["ownerid"]);
    }
    if(isset($queries["name"])) {
      $name= $queries["name"];
      if($name === 'properties') {
        $title = "Property List";
        $data = getBriefProperties();
        $columns = ["Property Identity", "Description", "Owner","Date","Status", "Setting"];      
      }
      else if ($name === 'contracts' ) {
        $title = "Contract List";
        $data = getBriefContracts();
        $columns = ["Owner Name", "Tenant Name", "Start Date","End Date", "Contract Date","Status","Setting"]; 
      }
      else if ($name === 'posts') {
        $title = "Post List";
        $data = getBriefPosts();
        $columns = ["Description", "Amount",  "Owner", "Date","Status","Setting"];
      }
      else if($name === 'users') {
        $title = "User List";
        $data = getBriefUsers();
        $columns = ["UserName","Email","Created Date","Approved By","Approved Date","Status","Setting"];
      }
    }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Property Rental System</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="../index.php" class="navbar-brand">
        <img src="../img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Property Rental System</span>
      </a>
      
      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            
          </li>   
        </ul>
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
          Welcome, <?php echo $_SESSION["current_admin"];?>
            <!-- <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span> -->
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <!-- <span class="dropdown-header">15 Notifications</span> -->
            <div class="dropdown-divider"></div>
            <a href="../index.php" class="dropdown-item">
            <i class="fas fa-users mr-2"></i>
                Home
            </a>
            <div class="dropdown-divider"></div>
            <a href="login.php?logout=y" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 
             Logout
            </a>
            <!-- <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div> -->
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
        <script>
        window.location.origin
        </script>
          <div class="col-sm-6">
            <form action="" method="post">
            <button type="submit" name="back" class='btn btn-primary'>Back</button><h1 class="m-0 text-dark"><?php echo $title; ?></h1>
            </form>
          
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $name; ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Manage <?php echo $title; ?> Here</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="dataTable" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <?php
                        
                          if(isset($data) && isset($columns)) {
                            foreach($columns as $column) {
                                echo "<th>".$column."</th>";
                            }
                          } 
                      ?>
                    </tr>
                    </thead>
                    <tbody>
                      <!-- <td>Trident</td>
                      <td>Internet
                        Explorer 4.0
                      </td>
                      <td>Win 95+</td>
                      <td> 4</td> -->
                      <?php
                          if((isset($data) && isset($columns))) {
                            foreach($data as $row) 
                            {
                                  echo "<tr>";
                                  for($i = 0 ; $i < count($columns); $i++)  {
                                      echo "<td>".array_shift($row)."</td>";
                                    }
                                  echo "</tr>";
                              }
                             
                          }
                      ?>
                    </tbody>
                    <tfoot>
                    <tr>
                      <?php
                          if(isset($columns)) {
                            foreach($columns as $column) {
                                echo "<th>".$column."</th>";
                            }
                          } 
                      ?>
                    </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
      $("#dataTable").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
                        </script>
</body>
</html>
