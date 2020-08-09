<?php
$sql_user = "SELECT * FROM user WHERE id = ".$user_id;
$run_query = $db->query($sql_user);
$data_user = $run_query->fetch_assoc();
$name = $data_user['username'];
$picture = $data_user['photo'];
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
	<title>Chat using websocket</title>
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
	<div class="page-header"><h3>Chat AS <?php echo $name; ?></h3><button class="btn btn-xs btn-primary" type="button" onclick="document.location='logout.php'">Logout</button></div>
	<div class="row text-center" style="display: inline-block">
		<div class="text-center" style="display: inline-block; width: 300px">
			<!-- DIRECT CHAT PRIMARY -->
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
				<h3 class="box-title">Chat with:</h3>
              <select class="form-control" id="chat_with">
				<?php
					$sql_user = "SELECT * FROM user";
          $run_query = $db->query($sql_user);
					while ( $data = $run_query->fetch_assoc() ) {
						if ( $data['id'] != $user_id ) {
						?>
						<option value="<?php echo $data['id']; ?>"><?php echo $data['username'] ?></option>
						<?php
						}
					}
					?>
			  </select>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages" id="message_box"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." id="ln" class="form-control">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary btn-flat" id="btn_send_message">Send</button>
                      </span>
              </div>
            </div>
            <!-- /.box-footer-->
          </div>
		</div>
	</div>
</div>
<div id="template_left_message" style="display: none">
	<div class="direct-chat-msg">
		<div class="direct-chat-info clearfix">
			<span class="direct-chat-name pull-left">{name}</span>
            <span class="direct-chat-timestamp pull-right">{time}</span>
        </div>
        <!-- /.direct-chat-info -->
        {image}<!-- /.direct-chat-img -->
        <div class="direct-chat-text text-left">{message}</div>
        <!-- /.direct-chat-text -->
    </div>
</div>
<div id="template_right_message" style="display: none">
	<div class="direct-chat-msg right">
		<div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">{name}</span>
            <span class="direct-chat-timestamp pull-left">{time}</span>
        </div>
        <!-- /.direct-chat-info -->
        {image}<!-- /.direct-chat-img -->
        <div class="direct-chat-text text-left">{message}</div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/docs.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script type="text/javascript">
var uid = <?php echo $user_id; ?>;
var websocket_server = 'ws://<?php echo $_SERVER['HTTP_HOST']; ?>:3030?uid='+uid;
var uname = '<?php echo $name; ?>';
</script>
<script src="js/websocket.js"></script>
</body>
</html>