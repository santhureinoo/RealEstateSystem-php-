<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}

    include("DB-Connection/property.php");
    include("DB-Connection/personal_data.php");
    include("DB-Connection/login.php");

    if(isset($_POST["reapply"]) && isset($_POST["postid"])){
      reapplyRejectedProposal($_POST["reapply"],$_POST["postid"]);
    }
    else if(isset($_POST["contract"])){
      header('Location: Contract.php?id='.$_POST["contract"].'&isOwner=y&noConfirm=y');
    }
    else if(isset($_POST["chat"]) && isset($_POST["chatname"]) && isset($_POST["chatid"])) {
      header('Location: private_chat/chat_new.php?rec_id='.$_POST["chatid"].'&rec_name='.$_POST["chatname"]);
    }
    else if(isset($_POST["confirmDelete"])) {
      deleteProposalById($_POST["confirmDelete"]);
    }
    
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
                <th>Description</th>
                <th>Owner Name</th>
                <th>Status</th>
                <th>Date</th>
                <th>Setting</th>
            </tr>
        </thead>
        <tbody>
        <?php
                    $result = getProposals($_SESSION["userid"]);
                    if($result != null) {
                        foreach( $result as  $proposal){
                            $desc= $proposal["description"];
                            $owner = $proposal["owner"];
                            $ownerid = $proposal["ownerid"];
                            $profile_data = getUserById($proposal["ownerid"]);
                            $encodedImage = base64_encode($profile_data["photo"]);
                            $profile_status = getProfileStatus($proposal["ownerid"]);
                            $readonly =true;
                            ob_start();
                            require "profile_detail.php";
                        $content = ob_get_clean();                     
                        function profileData(){
                          global $id,$content;
                          global $content;
                         
                              return ' <div class="modal " id="profile_'.$id.'">
                              <div class="modal-dialog modal-lg mw-100 w-75">
                                <div class="modal-content">
                            
                                  
                                  <div class="modal-header">
                                    <h4 class="modal-title">Profile</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>
                            
                                  <!-- Modal body -->
                                  <div class="modal-body">
                                  '.
                                 $content
                                .'
                                  </div>
                            
                                  <!-- Modal footer -->
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  </div>
                            
                                </div>
                              </div>
                            </div>
                        </div>';
                        }
                            $status=$proposal["status"];
                            $date=$proposal["created_at"];
                            $id = $proposal["id"];
                            $postid = $proposal["postid"];
                            $buttons = "";
                            if($status == "Approved") {
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
                                  Are you sure you want to delete this proposal?
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
                              $buttons = $deleteModal."  <form action='' method='post'> <button type='button' data-toggle='modal' data-target='#deleteModal".$id."' class='btn btn-danger'>Delete</button><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><button type='submit' name='confirm' value='$id' class='btn btn-success ml-2'>Confirm</button><input type='hidden' name='chatid' value='$ownerid'><input type='hidden' name='chatname' value='$owner'><button name='chat' class='btn btn-primary ml-2'>Chat</button></form>";
                            }
                            else if($status == "Rejected") {
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
                                  Are you sure you want to delete this proposal?
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
                                $buttons = $deleteModal."  <form action='' method='post'><button data-toggle='modal' data-target='#deleteModal".$id."' class='btn btn-danger'>Delete</button><input type='hidden' name='postid' value='$postid'><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><button type='submit' name='reapply' value='$id' class='btn btn-primary ml-2'>Re-Apply</button><input type='hidden' name='chatid' value='$ownerid'><input type='hidden' name='chatname' value='$owner'><button name='chat' class='btn btn-primary ml-2'>Chat</button></form>";
                            }
                            else if($status == "Waiting") {
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
                                  Are you sure you want to delete this proposal?
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
                                $buttons = $deleteModal."  <form action='' method='post'><button type='button'  data-toggle='modal' data-target='#deleteModal".$id."' class='btn btn-danger'>Delete</button><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='postid' value='$postid'><input type='hidden' name='chatid' value='$ownerid'><input type='hidden' name='chatname' value='$owner'><button name='chat' class='btn btn-primary ml-2'>Chat</button></form>";
                            }
                            else if($status == "Expired") {
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
                                  Are you sure you want to delete this proposal?
                                </div>
                                <div class="modal-footer">
                                <form action="" method="post">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" type="button"  name="confirmDelete" value="'.$id.'" class="btn btn-primary">Confirm</button>
                                  </form>	
                                </div>
                                </div>
                              </div>
                              </div>
                            </div>';
                                $buttons = $deleteModal."  <form action='' method='post'><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><button data-toggle='modal' data-target='#deleteModal".$id."' class='btn btn-danger'>Delete</button></form>";
                            }
                            else if($status == "Completed") {
                              $buttons = "  <form action='' method='post'><button name='contract' value='$id' class='btn btn-primary ml-2'>View Contract</button><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='chatid' value='$ownerid'><input type='hidden' name='chatname' value='$owner'><button name='chat' class='btn btn-primary ml-2'>Chat</button></form>";
                            }
                             echo "
                                     <tr>
                                         <td>
                                                 $desc
                                         </td>
                                         <td>
                                                 $owner
                                         </td>
                                         <td>
                                                 $status
                                         </td>
                                         <td>
                                                 $date
                                          </td>
                                          <td>
                                                <form action='' method='post'>
                                                 ".$buttons."
                                                </form>
                                         </td>
                                         
                                     </tr>  
                             ";
                             echo profileData();
                        } 
                    }
                
            ?>
        </tbody>
        <tfoot>
        <tr>
                <th>Description</th>
                <th>Owner Name</th>
                <th>Status</th>
                <th>Date</th>
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
        var table = $('#example').DataTable();
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
