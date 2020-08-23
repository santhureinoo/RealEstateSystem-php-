
<?php
	if(!isset($_SESSION)) {
		session_start();
	}
	include("DB-Connection/property.php");
    include("DB-Connection/login.php");
    if(isset($_POST['signin'])){
       $result =  login($_POST['signin-email'],$_POST['signin-password']);
}
    if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['logout']) && $queries['logout'] == 'y') {
            logout();
        }
	}
    if(isset($_POST["signup"])) {
        echo $_POST["username"];
        addUser($_FILES["profile"],$_POST["username"],$_POST["email"],$_POST["password"],
        $_POST["address"],$_POST["city"],$_POST["phone"],$_POST["nrc"],$_FILES["nrc_back"]
        ,$_FILES["nrc_front"],$_POST["relationship"],$_POST["religion"],$_POST["job"],$_POST["income"],$_POST["members"]);
        header('Location: index.php');
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
    <title>Login/Register</title>
</head>
<body>
	<div class="container">
		<div class="row">
		<div id="logreg-forms" class="col-sm-4 offset-sm-4">
        <form class="form-signin" action="" method="post" <?php if(isset($queries["register"]) && $queries["register"] == 'y'){
            echo 'style="display:none;"';
        } ?>>
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Sign in</h1>
            <?php if(isset($result)){
                      echo "<h4 style='color:red;'>$result</h4>";
            }?>
            <input type="email" id="inputEmail" name='signin-email' class="form-control mt-2 mb-2" placeholder="Email address" required="" autofocus="">
            <input type="password" id="inputPassword" name='signin-password' class="form-control mt-2 mb-2" placeholder="Password" required="">
            
            <button class="btn btn-success btn-block" name='signin' type="submit"><i class="fas fa-sign-in-alt"></i> Sign in</button>
            <a href="#" id="forgot_pswd">Forgot password?</a>
            <hr>
            <!-- <p>Don't have an account!</p>  -->
            <button class="btn btn-primary btn-block" type="button" id="btn-signup"><i class="fas fa-user-plus"></i> Sign up New Account</button>
            </form>

            <form action="" method="post" class="form-reset" style="display:none;" >
                <input type="email" id="resetEmail" class="form-control mt-2 mb-2" placeholder="Email address" required="" autofocus="">
                <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                <a href="#" id="cancel_reset"><i class="fas fa-angle-left"></i> Back</a>
            </form>
            
            <form enctype='multipart/form-data'  action="" method="post" class="form-signup" <?php if(!isset($queries["register"])){
            echo 'style="display:none;"';
            } ?>>
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Sign Up</h1>
                <h4 class='p-2'> Required Information </h4>
                <div class="row">
                        <div class="col-md-4 my-auto text-center">
                                Profile Pic :
                        </div>  
                        <div class="col-md-8">
                        <input type="file" id="profile" name="profile" class="form-control mt-2 mb-2" placeholder="profile" required autofocus="">
                        </div>
                </div>
                <input type="text" id="user-name" name="username" class="form-control mt-2 mb-2" placeholder="Full name" required="" autofocus="">
                <input type="email" id="user-email" name="email" class="form-control mt-2 mb-2" placeholder="Email address" required autofocus="">
                <input type="password" id="user-pass" name="password" class="form-control mt-2 mb-2" placeholder="Password" required autofocus="">
                <input type="password" id="user-repeatpass" class="form-control mt-2 mb-2" placeholder="Repeat Password" required autofocus="">
                <input type="text" id="address" name="address" class="form-control mt-2 mb-2" placeholder="Current Address" required="" autofocus="">
                <select id="city" name="city" name="city" class="form-control mt-2 mb-2"></select>
                <input type="text" id="phone" name="phone" class="form-control mt-2 mb-2" placeholder="Phone Number" required autofocus="">
                <input type="text" id="nrc" name="nrc" class="form-control mt-2 mb-2" placeholder="NRC Number" required autofocus="">
                <div class="row">
                        <div class="col-md-4 my-auto text-center">
                                NRC(Back) :
                        </div>  
                        <div class="col-md-8">
                        <input type="file" id="nrc_back_photo"  name="nrc_back" class="form-control mt-2 mb-2" placeholder="NRC Number" required autofocus="">
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-4 my-auto text-center">
                                NRC(Front) :
                        </div>  
                        <div class="col-md-8">
                        <input type="file" id="nrc_front_photo" name="nrc_front" class="form-control mt-2 mb-2" placeholder="NRC Number" required autofocus="">
                        </div>
                </div>
                <h4 class='pt-2 pl-2 pr-2'>Optional Information</h4>
                <h6 class=''>(It may be useful when someone rents your property or you rent someone's property)</h6>
                <select class="form-control mt-2 mb-2" id="relationship" name="relationship">
                            <option>Select Your Relationship</option>
                            <option value="1">Married</option>
                            <option value="2">Single</option>
                            <option value="3">Divorced</option>
                </select>
              <input type="text" id="religion" name="religion" class="form-control mt-2 mb-2" placeholder="Religion" autofocus="">
                <input type="text" id="job" name="job" class="form-control mt-2 mb-2" placeholder="Job" autofocus="">
                <input type="text" id="income" name="income" class="form-control mt-2 mb-2" placeholder="Income" autofocus="">
                <input type="text" id="familyMembers" name="members" class="form-control mt-2 mb-2" placeholder="Family Members" autofocus="">
                <button name="signup" class="btn btn-primary btn-block" type="submit"><i class="fas fa-user-plus"></i> Sign Up</button>
                <a href="#" id="cancel_signup"><i class="fas fa-angle-left"></i> Back</a>
            </form>
            <br>
            
    </div>
		</div>
	
	</div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
<script>
        $.getJSON( "mm.json", function( data ) {
      var items = [];
      $('#city').append("<option>Select a City </option>")
      $.each( data, function( key, val ) {

        $('#city').append("<option value='"+val.city+"'>"+val.city+"</option>");
      });
      });
function toggleResetPswd(e){
    e.preventDefault();
    $('#logreg-forms .form-signin').toggle() // display:block or none
    $('#logreg-forms .form-reset').toggle() // display:block or none
}

function toggleSignUp(e){
    e.preventDefault();
    $('#logreg-forms .form-signin').toggle(); // display:block or none
    $('#logreg-forms .form-signup').toggle(); // display:block or none
}

$(()=>{
    // Login Register Form
    $('#logreg-forms #forgot_pswd').click(toggleResetPswd);
    $('#logreg-forms #cancel_reset').click(toggleResetPswd);
    $('#logreg-forms #btn-signup').click(toggleSignUp);
    $('#logreg-forms #cancel_signup').click(toggleSignUp);
})
</script>
</body>
</html>