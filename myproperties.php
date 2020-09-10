<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
include("DB-Connection/personal_data.php");
include("DB-Connection/user.php");
include("DB-Connection/property.php");
include("DB-Connection/login.php");
$readonly;
$profile_data;
$encodedImage;
$viewProfile = false;
    if(isset($_POST["confirmDelete"])) {
      $errorMessage = deletePorpertyById($_POST["confirmDelete"]);
      if(isset($errorMessage)) {
          echo "<script> alert('$errorMessage');</script>";
      }
    }
    else if(isset($_POST["edit"])) {
      header('Location: property_register.php?view=2&propertyid='.$_POST["edit"]);
    }
    else if(isset($_POST["viewProfile"])){
      $viewProfile = true;
    } 
     
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Property Rental And Selling System</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

  <!--Jquery DataTable -->
  <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel='stylesheet'>
  <link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css" rel="stylesheet">
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
              
                <a href="login.php?logout=y" class="dropdown-item">Log Out</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    
      <div class="container">
      <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>City</th>
                <th>Address</th>
                <th>Type</th>
                <th>Date</th>
                <th>Status</th>
                <th>Setting</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    if($viewProfile){
                      $readonly =true;
                    }
                    $result = getProperties($_SESSION["userid"]);
                    if($result != null) {
                      foreach( $result as  $property){
                        $name= $property["name"];
                        $city = $property["city"];
                        $address=$property["address"];
                        $type=$property["type"];
                        $status = $property["status"];
                        $date=$property["created_at"];
                        $id = $property["id"];
                        if($status =="Active" && checkPropertyDeletable($property["id"])) {
                          $deleteModal = ' 
                          <div class="modal fade" id="deleteModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="deleteModal'.$id.'" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Property</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Are you sure you want to delete this property?
                              </div>
                              <div class="modal-footer">
                              <form action="" method="post">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" name="confirmDelete" value="'.$id.'" class="btn btn-primary">Confirm</button>
                                </form>	
                              </div>
                              </div>
                            </div>
                            </div>
                          </div>';
                          $buttons = $deleteModal."<form action='' method='post'><button type='submit' value='$id' name='edit' class='btn btn-primary'>Edit</button><button type='button' data-toggle='modal' data-target='#deleteModal".$id."' name='delete' class='btn btn-danger ml-2'>Delete</button>";
                        }
                        else if($status == "Active") {
                          $buttons = "<form action='' method='post'><button type='submit' name='edit' value='$id' class='btn btn-primary'>Edit</button>";
                        }
                        else if($status=="Occupied") {
                          $profile_data = getProfileDataByProperty($id);
                          $mod = '';
                          if(isset($profile_data)){
                            $encodedImage = base64_encode($profile_data["photo"]);
                            $profile_status = getProfileStatus($profile_data["id"]);
                            $readonly =true;
                            ob_start();
                              require "profile_detail.php";
                          $content = ob_get_clean();  
                            $mod =  '
                            <div class="modal fade" id="modal_'.$id.'" tabindex="-1"  role="dialog" aria-labelledby="modal_'.$id.'" aria-hidden="true">
                            <div class="modal-dialog modal-lg mw-100 w-75" role="document" style="max-width: 80%;">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  '.
                                  $content
                                . '</div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                            </div>
                            ';
                          }
                       
                          $buttons = "<form action='' method='post'><button type='button' name='viewProfile' data-toggle='modal' data-target='#modal_$id' class='btn btn-primary'>View Profile</button>".$mod;     
                        }
                        else { 
                          $deleteModal = ' 
                          <div class="modal fade" id="deleteModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="deleteModal'.$id.'" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Property</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Are you sure you want to delete this property?
                              </div>
                              <div class="modal-footer">
                              <form action="" method="post">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" name="confirmDelete" value="'.$id.'" class="btn btn-primary">Confirm</button>
                                </form>	
                              </div>
                              </div>
                            </div>
                            </div>
                          </div>';
                          $buttons = $deleteModal."<form action='' method='POST'><button type='button' data-toggle='modal' data-target='#deleteModal".$id."' name='delete' class='btn btn-danger ml-2'>Delete</button></form>";
                        }
                        
                         echo "
                                 <tr>
                                     <td>
                                             $name
                                     </td>
                                     <td>
                                             $city
                                     </td>
                                     <td>
                                             $address
                                     </td>
                                     <td>
                                             $type
                                     </td>
                                     <td>
                                             $date
                                      </td>
                                      <td>
                                             $status
                                      </td>
                                      <td>
                                          <form action='' method='POST'>
                                          ".$buttons."
                                          </form>
                                      </td>
                                 </tr>  
                         ";
                    } 
                    }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>City</th>
                <th>Address</th>
                <th>Type</th>
                <th>Date</th>
                <th>Status</th>
                <th>Setting</th>
            </tr>
        </tfoot>
    </table>

    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
      $(document).ready( function () {
        $("#profileModal").modal("show");
        var table = $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Add New Property',
                    action: function ( e, dt, node, config ) {
                        window.location =window.location.protocol + '//' + window.location.host+'/RealEstateSystem-php--master/RealEstateSystem-php--master/property_register.php?id='+<?php echo $_SESSION["userid"];?>;
                    }
                }
            ],
           
        } );
        table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );   
    } );
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>

</body>

</html>
