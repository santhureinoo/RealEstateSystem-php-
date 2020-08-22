<?php
session_start();
if(!isset($_SESSION["current_user"]) && !isset($_SESSION["adminAccess"])) {
    header('Location: login.php');
}
    include("DB-Connection/personal_data.php");
    include("DB-Connection/property.php");

    $contractDisplay;
    if(isset($_POST["confirm"]) && isset($_POST["isconfirm"])){
        if( $_POST['isconfirm']) {
            confirmApprovedProposal($_POST["confirm"]);
            insertContract($_POST["confirm"],$_POST["amount"],$_POST["fromDate"],$_POST["toDate"],$_POST["member"],$_FILES["offlineEvidence"]);   
            header('Location: myproposals.php');
        }
        else {
            $confirm = $_POST['confirm'];
            $postid = getProposalById($confirm)["postid"];
            echo $postid. ' and ' . $confirm;
            finalConfirmed($confirm,$postid);
        }
        
    }
    if(isset($_SERVER['QUERY_STRING'])) {
        
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['id'])) {
		  	$id = $queries['id'];
            $proposal_detail = getProposalById($id);
            $contractDisplay = getDisplayContract($proposal_detail["id"]);
			// $user_detail = getUserById($post_detail["ownerid"]);
        }
        if(isset($queries["noConfirm"])){
            $noConfirm = true;
        }
        if(isset($queries['id']) && isset($queries['isOwner']) && $queries['isOwner'] == 'y') {
            $readOnlyOrNot = "readonly";
            $id = $queries['id'];
          $proposal_detail = getProposalById($id);
          $contract_detail = getContractById($id);
          $contractDisplay = getDisplayContract($proposal_detail["id"]);
          // $user_detail = getUserById($post_detail["ownerid"]);
      }
        else {
            $readOnlyOrNot = "";
            $contract_detail=null;
        }
    }
    else {
        $readOnlyOrNot = "";
        $contract_detail=null;
    }

   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Contract</title>

    <!-- Icons font CSS-->
    <link href="css/material-design-iconic-font.css" rel="stylesheet" media="all">
    <link href="css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/select2.min.css" rel="stylesheet" media="all">
    <link href="css/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w1200">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Landlord, Tenant Agreement Contract</h2>
                    <h4 class="title text-right"><?php echo isset($contract_detail)?$contract_detail["created_at"]:date('d-m-Y'); ?></h2>
                            <div class="row">
                                <div class="col-md-6">
                                        <h4> Landlord</h4>
                                        <div class="form-group row mt-3">
                                            <label for="lName" class="col-sm-4 col-form-label">Name</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="lName" value="<?php echo isset($contractDisplay)? $contractDisplay["ownerName"]:'Unknown';?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="lNRC" class="col-sm-4 col-form-label">NRC</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="lNRC" value="<?php echo isset($contractDisplay)? $contractDisplay["ownerNRC"]:'Unknown';?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="lAddress" class="col-sm-4 col-form-label">Address</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="lAddress" value="<?php echo isset($contractDisplay)? $contractDisplay["ownerAddress"]:'Unknown';?>">
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6">
                                        <h4> Tenant</h4>
                                        <div class="form-group row mt-3">
                                            <label for="tName" class="col-sm-4 col-form-label">Name</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="tName" value="<?php echo isset($contractDisplay)? $contractDisplay["tenantName"]:'Unknown';?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tNRC" class="col-sm-4 col-form-label">NRC</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="tNRC" value="<?php echo isset($contractDisplay)? $contractDisplay["tenantNRC"]:'Unknown';?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tAddress" class="col-sm-4 col-form-label">Address</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="tAddress" value="<?php echo isset($contractDisplay)? $contractDisplay["tenantAddress"]:'Unknown';?>">
                                            </div>
                                        </div>
                                </div>
                              
                            </div>
                           
                            <hr/>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">
                                            <h4> House/Apartment Information</h4>
                                            <div class="form-group row mt-3">
                                                <label for="city" class="col-sm-3 col-form-label">City</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="city" value="<?php echo isset($contractDisplay)? $contractDisplay["pCity"]:'Unknown';?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="township" class="col-sm-3 col-form-label">Township</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="township" value="<?php echo isset($contractDisplay)? $contractDisplay["pTownship"]:'Unknown';?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="street" class="col-sm-3 col-form-label">Region</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="region" value="<?php echo isset($contractDisplay)? $contractDisplay["pRegion"]:'Unknown';?>"">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="noname" class="col-sm-3 col-form-label">No/Name</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="noname" value="MM-25">
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <h4>Transaction Information</h4>
                                            <div class="form-group row mt-3">
                                                <label for="amount" class="col-sm-3 col-form-label">Amount (Kyats<?php echo isset($contractDisplay) && $contractDisplay["postType"] !== 'Sale'?"/Month":'';?>)</label>
                                                <div class="col-sm-9">
                                                <input type="text" <?php echo $readOnlyOrNot; ?> class="form-control" id="amount_txt" value="<?php echo isset($contract_detail)? $contract_detail["amount"]:$proposal_detail["initial_amount"];?>">
                                                  
                                            </div>
                                            </div>
                                            <div class="form-group row " <?php echo $readOnlyOrNot === 'readonly'?'hidden':''; ?>>
                                                <label for="duration" class="col-sm-3 col-form-label">Duration <br/>(Month,Year)</label>
                                                <div class="col-sm-9 row">
                                                    <div class="col-sm-6"> <input type="email"  <?php echo $readOnlyOrNot; ?> class="form-control" id="month_txt" placeholder="Months"></div>
                                                    <div class="col-sm-6"><input type="email"  <?php echo $readOnlyOrNot; ?> class="form-control" id="year_txt" placeholder="Years"></div>                                            
                                                </div> 
                                            </div>
                                            <div class="form-group row">
                                                <label for="duration" class="col-sm-3 col-form-label">From</label>
                                                <div class="col-sm-9">
                                                        <input  id="fromDate_txt" class="<?php if($readOnlyOrNot == ""){
                                                            echo " form-control";
                                                        } ?>"  <?php echo $readOnlyOrNot; ?> type="text" placeholder="From Date" value="<?php 
                                                            if(isset($contract_detail)){
                                                                    echo  date('d-m-Y',strtotime($contract_detail["from_date"]));;
                                                            }
                                                        ?>">
                                                        <!-- <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i> -->
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="toDate_txt" class="col-sm-3 col-form-label">To</label>
                                                <div class="col-sm-9">
                                                        <input id="toDate_txt" class="<?php if($readOnlyOrNot == ""){
                                                            echo " form-control";
                                                        } ?>" <?php echo $readOnlyOrNot; ?> type="text" placeholder="To Date" name="todate" value="<?php
                                                            if(isset($contract_detail)){
                                                                echo  date('d-m-Y',strtotime($contract_detail["to_date"]));;
                                                             }
                                                        ?>">
                                                        <!-- <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i> -->
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label for="member_txt" class="col-sm-3 col-form-label">Members</label>
                                                <div class="col-sm-9"> 
                                                    <input type="text"  <?php echo $readOnlyOrNot; ?> class="form-control" id="member_txt" placeholder="Number of members who stay" value="<?php  if(isset($contract_detail)){echo  $contract_detail['members'];} ?>">
                                                </div>
                                            </div>
                                    </div>
                            </div>
                            </form>
                            <hr/>
                             <br/>
                            <div class="row">
                                <div class="col-md-12">
                                <?php if($readOnlyOrNot === 'readonly'){
                                    $encodedImage = base64_encode($contract_detail['evidence']);
                                      echo '<div class="row">';
                                      echo '<div class="col-md-6"><h2>Evidence</h2></div>';
                                      echo ' <div class="col-md-6"><img src="data:image/jpeg;base64,'.$encodedImage .'" class="avatar img-fluid img-circle img-thumbnail" alt="avatar"></div>';
                                } ?>
                                <div class="tabs" <?php echo $readOnlyOrNot === 'readonly'?'style="display: none;"':'' ?>>
                                    <div class="tab-button-outer" >
                                        <ul id="tab-button">
                                        <li><a href="#tab01">Online Payment</a></li>
                                        <li><a href="#tab02">Offline Payment</a></li>
                                        </ul>
                                    </div>
                                    <div class="tab-select-outer">
                                        <select id="tab-select">
                                        <option value="#tab01">Online Payment</option>
                                        <option value="#tab02">Offline Payment</option>
                                        </select>
                                    </div>

                                    <div id="tab01" class="tab-contents">
                                        <h2 class='p-3'>Payment information:</h2>
                                        <?php
                                                                                    
                                                //Merchant's account information
                                                $merchant_id = "JT02";			//Get MerchantID when opening account with 2C2P
                                                $secret_key = "YDRbw14OtHw3";	//Get SecretKey from 2C2P PGW Dashboard
                                                
                                                //Transaction information
                                                $payment_description  = $proposal_detail["name"];
                                                $order_id  = time();
                                                $currency = "104";
                                                $amount  =sprintf('%012d', $proposal_detail["initial_amount"]);
                                                
                                                //Request information
                                                $version = "8.5";	
                                                $payment_url = "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment";
                                                $result_url_1 = "http://localhost/V3_UI_PHP_JT01_devPortal_v2.0/V3_UI_PHP_JT01_devPortal_v2.0/result.php";

                                                //Construct signature string
                                                $params = $version.$merchant_id.$payment_description.$order_id.$currency.$amount.$result_url_1;
                                                $hash_value = hash_hmac('sha256',$params, $secret_key,false);	//Compute hash value
    
                                                echo '
                                                <form id="myform" method="post" action="'.$payment_url.' enctype="multipart/form-data">
                                                    <input type="hidden" name="version" value="'.$version.'"/>
                                                    <input type="hidden" name="merchant_id" value="'.$merchant_id.'"/>
                                                    <input type="hidden" name="currency" value="'.$currency.'"/>
                                                    <input type="hidden" name="result_url_1" value="'.$result_url_1.'"/>
                                                    <input type="hidden" name="hash_value" value="'.$hash_value.'"/>
                                                    <div class="form-group row">
                                                            <label for="staticEmail" class="col-sm-2 col-form-label">Property Name: </label>
                                                            <div class="col-sm-10">
                                                                <input type="text" id="payment_description" class="form-control-plaintext" name="payment_description" value="'.$payment_description.'"  readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="staticEmail" class="col-sm-2 col-form-label">ID: </label>
                                                            <div class="col-sm-10">
                                                                <input type="text" id="order_id" class="form-control-plaintext" name="order_id" value="'.$order_id.'"  readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="staticEmail" class="col-sm-2 col-form-label">Total Amount: </label>
                                                            <div class="col-sm-10">
                                                                <input type="text" id="finalAmount" class="form-control-plaintext" name="amount" value="'. $amount.'"  readonly/>
                                                            </div>
                                                        </div>
                                                    <input class="btn btn-success" type="submit" name="submit" value="Confirm" />
                                                </form> ';	 
                                        ?>
                                    </div>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                    <div id="tab02" class="tab-contents">
                                        <h2 class="mb-2">Evidence File</h2>
                                        <input type='file' id='offlineEvidence' name='offlineEvidence'>
                                    </div>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="p-t-20">
                                <input type='hidden' name='isconfirm' value='<?php if(isset($contract_detail)){ echo false ;} else { echo true;} ?>'>
                                <?php 
                                          if(isset($_REQUEST["payment_status"])) {
                                            // if($_REQUEST["payment_status"] == 000) {
                                            
                                            // }
                                            echo "<script> </script>";
                                        }
                                ?>
                                <?php 
                                        $success= isset($contract_detail) || isset($_REQUEST["payment_status"])?"":"disabled";
                                        if(isset($contract_detail)){  
                                            $isContract= false ;
                                        }
                                        else {
                                             $isContract= true;
                                            } 
                                        $confirmOrSave=isset($contract_detail)?"Confirm": "Submit";
                                        $initialAmount = isset($contract_detail)? $contract_detail["amount"]:$proposal_detail["initial_amount"];
                                    //    echo '<form action="" method="POST">';
                                        $buttonHide = '';
                                        if (isset($noConfirm)){
                                            $buttonHide = 'hidden';
                                        }
                                        echo "<input type='hidden' name='amount' id='amount' value='".  $initialAmount."'><input type='hidden' name='toDate' id='toDate'><input type='hidden' name='fromDate' id='fromDate'><input type='hidden' name='member' id='member'>";
                                        echo " <input type='hidden' name='isconfirm' value='".$isContract."'>";
                                        echo ' <input type="hidden" name="confirmOrSave" value="'.$confirmOrSave.'"><button '.$buttonHide.' id="confirm" class="btn btn--radius btn--green" '.$success .' name="confirm" value="'.$id.'" type="submit">'.$confirmOrSave.'</button>';
                                        echo '</form>';
                                ?>
                               
                            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <!-- Vendor JS-->
    <script src="js/select2.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
    <script>
        $(function() {
            $("#fromDate_txt").datepicker();
            $("#toDate_txt").datepicker();
         
  var $tabButtonItem = $('#tab-button li'),
      $tabSelect = $('#tab-select'),
      $tabContents = $('.tab-contents'),
      activeClass = 'is-active';

  $tabButtonItem.first().addClass(activeClass);
  $tabContents.not(':first').hide();

  $tabButtonItem.find('a').on('click', function(e) {
    var target = $(this).attr('href');

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
  });
  const zeroPad = (num, places) => String(num).padStart(places, '0');

  $("#offlineEvidence").change(function() {
      $("#confirm").prop('disabled', false);
  })

  $('#amount_txt').change(function() {
        var amount = zeroPad(($('#amount_txt').val()),12);
        $('#finalAmount').val(amount);
        $("#amount").val($('#amount_txt').val());
  })

  $("#toDate_txt").change(function() {
    adjustDuration();
      var toDate = $("#toDate_txt").val();
      $("#toDate").val(toDate);
  })

  

   $("#fromDate_txt").change(function() {
    adjustDuration();
        var toDate = $("#toDate_txt").val();
       var fromDate = $("#fromDate_txt").val();
       $("#fromDate").val(fromDate);
   })
   $("#member_txt").change(function() {
      var member = $("#member_txt").val();
      $("#member").val(member);
  })

  function adjustDuration(){
    var toDate = $("#toDate_txt").val();
    var fromDate = $("#fromDate_txt").val();
    if(toDate !== '' && fromDate !== ''){
        toDate = moment(toDate);
        fromDate = moment(fromDate);
        var diffDuration =  moment.duration(toDate.diff(fromDate));
        $('#month_txt').val(diffDuration.months());
        $('#year_txt').val(diffDuration.years());
    }
  }
  <?php
    if($readOnlyOrNot === 'readonly') {
        echo "adjustDuration();";
    }
  ?>

  $('#month_txt').change(function(){
    var fromDate_txt = $("#fromDate_txt").val();
    if(fromDate_txt === ''){
        
        fromDate_txt = moment(new Date());
        $('#fromDate_txt').val(fromDate_txt.format('MM/DD/YYYY'));
        $('#fromDate').val(fromDate_txt.format('MM/DD/YYYY'));
    }
    else {
        fromDate_txt = moment(fromDate_txt);
    }
    $('#toDate_txt').val(fromDate_txt.add($(this).val(),'months').format('MM/DD/YYYY'));
    $('#toDate').val(fromDate_txt.add($(this).val(),'months').format('MM/DD/YYYY'));
  })

  $('#year_txt').change(function(){
    var fromDate_txt = $("#fromDate_txt").val();
    if(fromDate_txt === ''){
        
        fromDate_txt = moment(new Date());
        $('#fromDate_txt').val(fromDate_txt.format('MM/DD/YYYY'));
        $('#fromDate').val(fromDate_txt.format('MM/DD/YYYY'));
    }
    else {
        fromDate_txt = moment(fromDate_txt);
    }
    $('#toDate_txt').val(fromDate_txt.add($(this).val(),'years').format('MM/DD/YYYY'));
    $('#toDate').val(fromDate_txt.add($(this).val(),'years').format('MM/DD/YYYY'));
  })

  function checkDateValidity(){
    var toDate = $("#toDate_txt").val();
    var fromDate = $("#fromDate_txt").val();

  }

  $tabSelect.on('change', function() {
    var target = $(this).val(),
        targetSelectNum = $(this).prop('selectedIndex');

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
  });
});
        </script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->
