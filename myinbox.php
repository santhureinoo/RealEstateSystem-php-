<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
    include("DB-Connection/personal_data.php");
    include("DB-Connection/login.php");
    include("DB-Connection/property.php");

    if(isset($_POST["confirm"]) && isset($_POST["postid"])) {
      approveProposal($_POST["confirm"],$_POST['postid']);
    }
    else if(isset($_POST["contract"])){
      header('Location: Contract.php?id='.$_POST["contract"].'&isOwner=y&noConfirm=y');
    }
    else if(isset($_POST["reject_Approved"]) && isset($_POST["postid"])) {
      rejectApprovedProposal($_POST["reject_Approved"],$_POST['postid']);
    }
    else if(isset($_POST["reject"])) {
      rejectProposal($_POST["reject"]);
    }
    else if(isset($_POST["confirmFinal"])) {
      header('Location: Contract.php?id='.$_POST["confirmFinal"].'&isOwner=y');
      // finalConfirmed($_POST["confirmFinal"],$_POST['postid']);
    }
    else if(isset($_POST["chat"]) && isset($_POST["chatname"]) && isset($_POST["chatid"])) {
      header('Location: private_chat/chat_new.php?rec_id='.$_POST["chatid"].'&rec_name='.$_POST["chatname"]);
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
                <th>Tenant Name</th>
                <th>Status</th>
                <th>Date</th>
                <th>Setting</th>
            </tr>
        </thead>
        <tbody>
        <?php
                    $result =getInbox($_SESSION["userid"]);
               
                    if($result != null) {
                        foreach( $result as  $proposal){
                            $desc= $proposal["description"];
                            $tenant = $proposal["tenant"];
                            $tenantid = $proposal["tenantid"];
                            $status=$proposal["status"];
                            $date=$proposal["created_at"];
                            $id = $proposal["id"];
                            $postid = $proposal["postid"];   
                            $profile_data = getUserById($proposal["tenantid"]);
                            $encodedImage = base64_encode($profile_data["photo"]);
                            $profile_status = getProfileStatus($proposal["tenantid"]);
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
                           $buttons = '' ;
                           
                            if($status == "Waiting") {
                              
                              $buttons = $buttons . "<input type='hidden' value='$postid' name='postid'> <button name='reject' value='$id' class='btn btn-danger'>Reject</button> <button name='confirm' value='$id' class='btn btn-success ml-2'>Confirm</button><button type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='chatid' value='$tenantid'><input type='hidden' name='chatname' value='$tenant'><button name='chat' class='btn btn-primary ml-2'>Chat</button>";
                            }
                            else if($status == "Approved") {
                              $buttons = $buttons .  "<input type='hidden' value='$postid' name='postid'><button name='reject_Approved' value='$id' class='btn btn-danger'>Reject</button><button  type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='chatid' value='$tenantid'><input type='hidden' name='chatname' value='$tenant'><button name='chat' class='btn btn-primary ml-2'>Chat</button>";
                            }
                            else if($status =="Confirmed") {
                              $buttons =  $buttons . "<input type='hidden' value='$postid' name='postid'><button name='reject' value='$id' class='btn btn-danger'>Reject</button> <button name = 'confirmFinal' value='$id' class='btn btn-success'>Confirm Proposal </button><button  type='button' class='btn btn-primary ml-2' data-toggle='modal' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='chatid' value='$tenantid'><input type='hidden' name='chatname' value='$tenant'><button name='chat' class='btn btn-primary ml-2'>Chat</button>";
                            }
                            else if($status =="Completed") {
                              $buttons =  $buttons . "<button name = 'contract' value='$id' class='btn btn-primary'>View Contract </button><button class='btn btn-primary ml-2' data-toggle='modal'  type='button' data-target='#profile_".$id."'>View Profile</button><input type='hidden' name='chatid' value='$tenantid'><input type='hidden' name='chatname' value='$tenant'><button name='chat' class='btn btn-primary ml-2'>Chat</button>";
                            }
                             echo "
                                     <tr>
                                         <td>
                                                 $desc
                                         </td>
                                         <td>
                                                 $tenant
                                         </td>
                                         <td>
                                                 $status
                                         </td>
                                         <td>
                                                 $date
                                          </td>
                                          <td>
                                              <form action='' method='post'>
                                              $buttons
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
                <th>Tenant Name</th>
                <th>Status</th>
                <th>Date</th>
                <th>Setting</th>
            </tr>
        </tfoot>
    </table>
    
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
