var websocket = false;
var js_flood = 0;
var status_websocket = 0;
$(document).ready(function() {
    console.log('win tolt win tal ha');
    start(websocket_server);
});
function scrolldiv() {
    $("#message_box").prop({
        scrollTop: $("#message_box").prop("scrollHeight")
    })
}
function waitForSocketConnection(socket, callback){
    setTimeout(
        function () {
            if (socket.readyState === 1) {
                if(callback != null){
                    callback();
                }
                return;

            } else {

                waitForSocketConnection(socket, callback);
            }

        }, 5); // wait 5 milisecond for the connection...
}
function start(websocketServerLocation){
    websocket = new WebSocket(websocketServerLocation);
    websocket.onopen = function(ev) {
		//console.log(ev);
        status_websocket = 1;
    };
    $('#ln').keypress(function( event ) {
        if ( event.which == 13 || event.keyCode == 13 ) {
            var mymessage = $(this).val(); //get message text
            var myname = uname; //get user name

            if ( mymessage.length > 0 ) {
                if ( js_flood == 0 ) {
                   
                    var to_id = $('#chat_with_id').val();
                    var uid = $('#chat_from_id').val();
                    console.log('uid is ' + uid);
					var to_name = $('#chat_with_name').text();
					var msg = {
						message: mymessage,
                        name: myname,
						uid: uid,
						to_id: to_id,
						to_name: to_name
                    };
                    websocket.send(JSON.stringify(msg));
                    flood_js();
				} else {
					$('#ln').val('');
                }
            }
            var obj = document.getElementById('ln');
            obj.value = '';
            obj.focus();
            event.preventDefault();
        }
    });
    $('#btn_send_message').click(function() {
        var mymessage = $("#ln").val(); //get message text
        var myname = uname; //get user name
        
        if ( mymessage.length > 0 ) {
			if ( js_flood == 0 ) {
               
                var uid = $('#chat_from_id').val();
                var to_id = $('#chat_with_id').val();
                console.log('uid is ' + uid);
					var to_name = $('#chat_with_name').text();
					var msg = {
                        convid : $("#currentConversationId").val(),
						message: mymessage,
                        name: myname,
						uid: uid,
						to_id: to_id,
						to_name: to_name
                    };
              
                websocket.send(JSON.stringify(msg));
                flood_js();
			} else {
				$('#ln').val('');
            }
        }
        var obj = document.getElementById('ln');
        obj.value = '';
        obj.focus();
        event.preventDefault();
    });
    websocket.onmessage = function(ev) {
		console.log(ev.data);
        var msg = JSON.parse(ev.data); //PHP sends Json data
        var umsg = msg.message; //message text
        var name = msg.name; //user name
        var timemsg = msg.timemsg;
        var user_image = msg.user_image;
        var to_id = msg.to_id;
            console.log(to_id + " AND " + $("#chat_with_id").val());
            if ( name != uname) {
               
                // if(to_id == $('#chat_with_id').val()) {
                //     console.log("SUCCESS HERE");
                    $('#message_box').append('<div class="d-flex justify-content-start mb-4"><div class="msg_cotainer">'+umsg+'<span class="msg_time">'+timemsg+'</span></div></div>');
                // }
            } else {
                $('#message_box').append('<div class="d-flex justify-content-end mb-4"><div class="msg_cotainer_send">'+umsg+'<span class="msg_time_send">'+timemsg+'</span></div></div>');
            }
	
		scrolldiv();
    };

    websocket.onclose = function(ev){
        if ( status_websocket === 1 ) {
            status_websocket = 0;
        }
        setTimeout(function(){start(websocketServerLocation)}, 1000);
    };
    websocket.onerror = function(ev) {
        console.log('Error '+JSON.stringify(ev));
    };
}

function flood_js() {
    var interval_flood = setInterval(function() {
        if ( js_flood == 0 ) {
            js_flood = 1;
        } else {
            js_flood = 0;
            clearInterval(interval_flood);
        }
    }, 300);
}