<link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="css/chat.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>


<!------ Include the above in your HEAD tag ---------->
<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
include("../DB-Connection/conversation.php");
include("../DB-Connection/login.php");
if(isset($_SERVER['QUERY_STRING'])) {
    $queries = array();
    parse_str($_SERVER['QUERY_STRING'], $queries);
    if(isset($queries['rec_id']) && isset($queries['rec_name'])) {
        $rec_id = $queries["rec_id"];
		$rec_name = $queries["rec_name"];
		if(checkExistingConversation($_SESSION["userid"],$rec_id) == 0) {
			addConversationByID($_SESSION["userid"],$rec_id);
		}
    }else {
         
    }
}
else {
    
}
$db = new mysqli("localhost", "root", "", "estatedb");
$sql_user = "SELECT * FROM user WHERE id = ".$_SESSION["userid"];
$run_query = $db->query($sql_user);
$data_user = $run_query->fetch_assoc();
$name = $data_user['username'];
$picture = $data_user['photo'];
?>
<script>
    var uid = <?php echo $_SESSION["userid"]; ?>;
    var websocket_server = 'ws://<?php echo $_SERVER['HTTP_HOST']; ?>:3030?uid='+uid;
    var uname = '<?php echo $name; ?>';
</script>
<script src="js/websocket.js"></script>
<!DOCTYPE html>
<html>
	<head>
		<title>Chat</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
	</head>
	<!--Coded With Love By Mutiullah Samim-->
	<body>
		<div class="container-fluid h-100">
			<div class="row justify-content-center h-100">
				<div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
					<div class="card-header">
						<div class="input-group">
							<input type="text" placeholder="Search..." name="" class="form-control search">
							<div class="input-group-prepend">
								<span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
							</div>
						</div>
					</div>
					<div class="card-body contacts_body">
						<ui class="contacts">
								<?php
										$currentConversationid;
										$receiver;
										$receiverPhoto;
										$userPic;
										foreach(getConversationsByID($_SESSION["userid"]) as $conversation){
											$currentConversation="";
											if($conversation["u1_id"] == $rec_id || $conversation["u2_id"] == $rec_id) {
												$currentConversationid = $conversation["id"];
												$currentConversation = "active";
											}
											if($conversation["u1_id"] != $_SESSION["userid"]) {
												$receiverid = $conversation["u1_id"];
												$userPic=  base64_encode($conversation["u1_photo"]);
												$receiver = $conversation["u1_username"];
											}
											else {
												$receiverid = $conversation["u2_id"];
												$userPic=  base64_encode($conversation["u2_photo"]);
												$receiver = $conversation["u2_username"];
											}
										
											// $receiverPhoto  = base64_encode($userData["photo"]);
											// echo $receiverPhoto;
											$url= explode('?', $_SERVER["REQUEST_URI"], 2)[0];
											echo '<li class="'.$currentConversation.'">
													<a href="'.$url.'?rec_id='.$receiverid.'&rec_name='.$receiver.'">
														<div class="d-flex bd-highlight">
															<div class="img_cont">
																<img src="data:image/jpeg;base64,'.$userPic.'" class="rounded-circle user_img">
															</div>
															<div class="user_info">
																<span>'.$receiver.'</span>
															</div>
														</div>
													</a>
												</li>
												';
										} 
								?>
						<!-- <li class="active">
							<div class="d-flex bd-highlight">
								<div class="img_cont">
									<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
									<span class="online_icon"></span>
								</div>
								<div class="user_info">
									<span>Khalid</span>
									<p>Kalid is online</p>
								</div>
							</div>
						</li> -->
						<!-- <li>
							<div class="d-flex bd-highlight">
								<div class="img_cont">
									<img src="https://2.bp.blogspot.com/-8ytYF7cfPkQ/WkPe1-rtrcI/AAAAAAAAGqU/FGfTDVgkcIwmOTtjLka51vineFBExJuSACLcBGAs/s320/31.jpg" class="rounded-circle user_img">
									<span class="online_icon offline"></span>
								</div>
								<div class="user_info">
									<span>Taherah Big</span>
									<p>Taherah left 7 mins ago</p>
								</div>
							</div>
						</li> -->
						</ui>
					</div>
					<div class="card-footer"></div>
				</div></div>
				<div class="col-md-8 col-xl-6 chat">
					<div class="card">
						<div class="card-header msg_head">
							<div class="d-flex bd-highlight">
								<div class="img_cont">
									<img src="data:image/jpeg;base64,<?php echo $userPic;?>" class="rounded-circle user_img">
									<span class="online_icon"></span>
								</div>
								<div class="user_info">
									<span>Chat with <?php echo $receiver;
									$chatData=getChatsByConversationID($currentConversationid);
									$count = 0;
									if($chatData != null) {
										$count = 0;//count($chatData);
									}
									?></span>
									<p><?php echo $count; ?> Messages</p>
									<input type='hidden' id='currentConversationId' value='<?php echo $currentConversationid; ?>'>
								</div>
								<!-- <div class="video_cam">
									<span><i class="fas fa-video"></i></span>
									<span><i class="fas fa-phone"></i></span>
								</div> -->
							</div>
							<!-- <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span> -->
							<!-- <div class="action_menu">
								<ul>
									<li><i class="fas fa-user-circle"></i> View profile</li>
									<li><i class="fas fa-users"></i> Add to close friends</li>
									<li><i class="fas fa-plus"></i> Add to group</li>
									<li><i class="fas fa-ban"></i> Block</li>
								</ul>
							</div> -->
						</div>
						<div class="card-body msg_card_body" id="message_box">
							<?php 
									foreach( $chatData as $chat){
										
										if($chat["senderid"]==$_SESSION["userid"]) {
											$leftOrRight = "Justify-content-end";
											$msgContainer = "msg_cotainer_send";
											$msgDate = "msg_time_send";
										}
										else {
											$leftOrRight = "Justify-content-start";
											$msgContainer = "msg_cotainer";
											$msgDate = "msg_time";
										}
										echo '<div class="d-flex '.$leftOrRight.' mb-4">
										<div class="'.$msgContainer.'">
												'.$chat["message"].'
											<span class="'.$msgDate.'">'.$chat["created_at"].'</span>
										</div>
									</div>';
									}
							?>	
							<div id="chat-area">
							</div>
						</div>
						<div class="card-footer">
							<div class="input-group">
								<!-- <div class="input-group-append">
									<span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
								</div> -->
								<textarea name="" class="form-control type_msg"  id="ln" placeholder="Type your message..."></textarea>
								<div class="input-group-append">
									<input type="hidden" id="chat_from_id" value="<?php echo $_SESSION['userid']; ?>">
                                    <input type="hidden" id="chat_with_id" value="<?php  echo $rec_id; ?>">
                                    <input type="hidden" id="chat_with_name" value="<?php echo $rec_name; ?>">
									<button class="input-group-text send_btn"  id="btn_send_message"><i class="fas fa-location-arrow"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script>
    	$(document).ready(function(){
        $('#action_menu_btn').click(function(){
            $('.action_menu').toggle();
        });
            });
</script>