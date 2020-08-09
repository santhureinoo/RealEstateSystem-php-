<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
include("DB-Connection/personal_data.php");
include("DB-Connection/login.php");
include("DB-Connection/property.php");
include("DB-Connection/user.php");
$readonly =false;
if(isset($_POST["save"]) && isset($_FILES)) {
  updateUser($_FILES["photo"],$_POST["username"],$_POST["email"],$_POST["password"],$_POST["phone"],$_POST["nrc"],$_POST["address"],$_POST["city"],$_POST["job"],$_POST["income"],$_POST["relationship"],$_POST["religion"],$_POST["family_members"]);
}

$profile_data = getUserById($_SESSION["userid"]);
$encodedImage = base64_encode($profile_data["photo"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Property Rental System</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">User Menu </div>
      <div class="list-group list-group-flush">
      <a href="profile.php" class="list-group-item list-group-item-action bg-light">Profile</a>
        <a href="myproperties.php" class="list-group-item list-group-item-action bg-light">Properties</a>
        <a href="myposts.php" class="list-group-item list-group-item-action bg-light">Posts</a>
        <a href="myproposals.php" class="list-group-item list-group-item-action bg-light">Proposals</a>
        <a href="myinbox.php" class="list-group-item list-group-item-action bg-light">Inbox</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php  echo $_SESSION["current_user"]?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Contact Us</a>
                <a class="dropdown-item" href="#">Switch Accounts</a>
                <div class="dropdown-divider"></div>
                <a href="login.php?logout=y" class="dropdown-item">Log Out</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

  <?php include("profile_detail.php"); ?>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
      $(document).ready(function() {

    
var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.avatar').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}


$(".file-upload").on('change', function(){
    readURL(this);
});
});
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>

</body>

</html>
