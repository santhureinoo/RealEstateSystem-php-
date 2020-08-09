<?php
if ( $_POST ) {
	$user_id = $_POST['user_id'];
	$_SESSION['userid'] = $user_id;
	header("location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="sharecodex.com">
	<title>Login Page</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/AdminLTE.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
	<link href="css/theme.css" rel="stylesheet">
	<script src="js/ie-emulation-modes-warning.js"></script>
</head>
<body>
<div class="container theme-showcase text-center" role="main">
	<div class="page-header"><h3>Login AS:</h3></div>
	<div class="row text-center" style="display: inline-block">
		<div class="text-center" style="display: inline-block; width: 300px">
			<form class="form-horizontal" method="post">
				<div class="form-group">
					<select class="form-control" name="user_id">
					<?php
					$sql_user = "SELECT * FROM user";
					$run_query = $db->query($sql_user);
					while ( $data = $run_query->fetch_assoc() ) {
						?>
						<option value="<?php echo $data['id']; ?>"><?php echo $data['username'] ?></option>
						<?php
					}
					?>
					</select>
				</div>
				<div class="form-group text-right">
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>