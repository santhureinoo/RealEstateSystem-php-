<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
    include("DB-Connection/personal_data.php");
    include("DB-Connection/login.php");
    if(isset($_POST['confirmDelete'])) {
        $errorMessage = deletePostById($_POST["confirmDelete"]);
        if(isset($errorMessage)) {
          echo "<script> alert('$errorMessage');</script>";
      }
    }
    else if(isset($_POST["edit"])) {
      header('Location: post_register.php?id='.$_POST["edit"]);
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
                <th>Property Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Setting</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    $result = getPosts($_SESSION["userid"]);
                    if($result != null) {
                      foreach( $result as  $post){
                        $id =$post["id"];
                         $name= $post["name"];
                         $description = $post["description"];
                         $initial_amount=$post["initial_amount"];
                         $status=$post["status"];
                         $created_at=$post["created_at"];
                         $buttons = "<form action='' method='post'><button name='edit' type='submit' value='$id' class='btn btn-primary'>Edit</button><button type='button' data-toggle='modal' data-target='#deleteModal".$id."' name='delete' class='btn btn-danger ml-2'>Delete</button></form>";                    
                         if($status == "Expired" ) {
                              $buttons = "";
                         }
                         else {
                          if(isPostDeletable($id) == 0) {
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
                                Are you sure you want to delete this post?
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
                            $buttons = $deleteModal.$buttons;
                          }
                          else {
                            $buttons = "<form action='' method='Post'><button name='edit' type='submit' value='$id' class='btn btn-primary'>Edit</button></form>";                    
                          }
                         }
                         echo "                             
                                  <tr>
                                      <td>
                                              $name
                                      </td>
                                      <td>
                                              $description
                                      </td>
                                      <td>
                                              $initial_amount
                                      </td>
                                      <td>
                                              $status
                                       </td>
                                       <td>
                                       $created_at
                                      </td>
                                      <td>
                                            <form action=' ' method='post'>
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
                <th>Property Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created Date</th>
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
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
      $(document).ready( function () {
    
        var table = $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Add New Post',
                    action: function ( e, dt, node, config ) {
                        window.location =window.location.protocol + '//' + window.location.host+'/RealEstateSystem-php--master/RealEstateSystem-php--master/post_register.php';
                    }
                }
            ]
        } );
        table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );   

        <?php
            if(isset($delete_result)) {
              echo "alert('$delete_result');";
            } 
            ?>
    } );
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
   
  </script>

</body>

</html>
