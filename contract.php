<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
    include("DB-Connection/personal_data.php");
    include("DB-Connection/property.php");

    if(isset($_POST["confirm"]) && isset($_POST["isconfirm"])){
        if( $_POST['isconfirm']) {
            confirmApprovedProposal($_POST["confirm"]);
            insertContract($_POST["confirm"],$_POST["amount"],$_POST["fromDate"],$_POST["toDate"],$_POST["member"]);   
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
			// $user_detail = getUserById($post_detail["ownerid"]);
        }
        if(isset($queries['id']) && isset($queries['isOwner']) && $queries['isOwner'] == 'y') {
            $readOnlyOrNot = "readonly";
            $id = $queries['id'];
          $proposal_detail = getProposalById($id);
          $contract_detail = getContractById($id);
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
    <title>Au Register Forms by Colorlib</title>

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
                    <h4 class="title text-right"><?php echo date('d-m-Y'); ?></h2>
                            <div class="row">
                                <div class="col-md-6">
                                        <h4> Landlord</h4>
                                        <div class="form-group row mt-3">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">Name</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Kimmy">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">NRC</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="9/MHM(N)032569">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">Address</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="35*36 streets, Mandalay">
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6">
                                        <h4> Tenant</h4>
                                        <div class="form-group row mt-3">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">Name</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Kimmy">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">NRC</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="9/MHM(N)032569">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-4 col-form-label">Address</label>
                                            <div class="col-sm-8">
                                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="35*36 streets, Mandalay">
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
                                                <label for="staticEmail" class="col-sm-3 col-form-label">City</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Kimmy">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">Township</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="9/MHM(N)032569">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">Street</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="35*36 streets, Mandalay">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">No/Name</label>
                                                <div class="col-sm-9">
                                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="MM-25">
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <h4>Transaction Information</h4>
                                            <div class="form-group row mt-3">
                                                <label for="amount" class="col-sm-3 col-form-label">Amount (Kyats/Month)</label>
                                                <div class="col-sm-9">
                                                <input type="text" <?php echo $readOnlyOrNot; ?> class="form-control-plaintext" id="amount_txt" value="<?php echo isset($contract_detail)? $contract_detail["amount"]:$proposal_detail["initial_amount"];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">Duration <br/>(Month,Year)</label>
                                                <div class="col-sm-9 row">
                                                    <div class="col-sm-6"> <input type="email"  <?php echo $readOnlyOrNot; ?> class="form-control" id="month_txt" placeholder="Months"></div>
                                                    <div class="col-sm-6"><input type="email"  <?php echo $readOnlyOrNot; ?> class="form-control" id="year_txt" placeholder="Years"></div>                                            
                                                </div> 
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">From</label>
                                                <div class="col-sm-9">
                                                        <input  id="fromDate_txt" class="<?php if($readOnlyOrNot == ""){
                                                            echo " input--style-1 js-datepicker";
                                                        } ?>"  <?php echo $readOnlyOrNot; ?> type="text" placeholder="From Date" value="<?php 
                                                            if(isset($contract_detail)){
                                                                    echo  date('d-m-Y',strtotime($contract_detail["from_date"]));;
                                                            }
                                                        ?>">
                                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">To</label>
                                                <div class="col-sm-9">
                                                        <input id="toDate_txt" class="<?php if($readOnlyOrNot == ""){
                                                            echo " input--style-1 js-datepicker";
                                                        } ?>" <?php echo $readOnlyOrNot; ?> type="text" placeholder="To Date" name="todate" value="<?php
                                                            if(isset($contract_detail)){
                                                                echo  date('d-m-Y',strtotime($contract_detail["to_date"]));;
                                                             }
                                                        ?>">
                                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label for="staticEmail" class="col-sm-3 col-form-label">Members</label>
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
                                <div class="tabs">
                                    <div class="tab-button-outer">
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
                                                <form id="myform" method="post" action="'.$payment_url.'">
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
                                    <div id="tab02" class="tab-contents">
                                        <h2 class="mb-2">Evidence File</h2>
                                        <input type='file' id='offline_evidence' name='offline_evidence'>
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
                                        echo '<form action="" method="POST">';
                                        echo "<input type='hidden' name='amount' id='amount'><input type='hidden' name='toDate' id='toDate'><input type='hidden' name='fromDate' id='fromDate'><input type='hidden' name='member' id='member'>";
                                        echo " <input type='hidden' name='isconfirm' value='".$isContract."'>";
                                        echo ' <input type="hidden" name="confirmOrSave" value="'.$confirmOrSave.'"><button id="confirm" class="btn btn--radius btn--green" '.$success .' name="confirm" value="'.$id.'" type="submit">'.$confirmOrSave.'</button>';
                                        echo '</form>';
                                ?>
                               
                            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Vendor JS-->
    <script src="js/select2.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
    <script>
        $(function() {
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

  $("#offline_evidence").change(function() {
      $("#confirm").prop('disabled', false);
  })

  $('#amount_txt').on('change',function() {
        var amount = zeroPad(($('#amount_txt').val()),12);
        $('#finalAmount').val(amount);
        $("#amount").val(amount);
  })

  $("#toDate_txt").on('change',function() {
      var toDate = $("#toDate_txt").val();
      $("toDate").val(toDate);
  })
   $("#fromDate_txt").on("change",function() {
       var fromDate = $("#fromDate_txt").val();
       $("fromDate").val(fromDate);
   })
   $("#member_txt").on('change',function() {
      var member = $("#member_txt").val();
      $("#member").val(member);
  })

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
