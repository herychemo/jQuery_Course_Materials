var main = function(){
	$(".msgsContainer").height( $('body').height() - ( $("#newComment").height()*2 ) );
	updateMsgs();
	$("#newComment").on("submit", function(e){
		e.preventDefault();
		var $msg = $("#msg");
		var $file = $("#file");
		var $user = $("#user");

		if( $msg.prop('required') ){
			if( $msg.val().trim().length < 8 ){
				myAlert( "Mensaje muy corto", 'Alerta',function(){
					$msg.focus();
				}, function(){
					$msg.focus();
				});
				return false;
			}
		}else{
			var uri = $file.val();
			var uriBroken = uri.split('.');
			var extension = uriBroken[ uriBroken.length -1].toLowerCase(); 
			if ( ! ( extension == 'png' || extension == 'jpg' || extension == 'gif' ) ){
				myAlert( "Los formatos ."+extension+" No son compatibles", 'Alerta',function(){
					$file.focus();
				}, function(){
					$file.focus();
				});
				return false;
			}
		}
		var myData = new FormData( $(this)[0] );
		$.cookie("user", $user.val().trim());
		var ok_x = function(){
			if( $msg.prop('required') )
				$msg.val("").focus();
			else
				$file.val("").focus();
		}
		$.ajax({
			url: './ajax/newMsg.php',
			type: "POST", 
			data: myData,
			cache: false,
			contentType: false, 
			processData: false, 
			success: function(data){
				console.log("Mensaje Enviado...");
				var array = data.split("|");
				if( array[0] == 'err' )
					myAlert( array[1], "Respuesta", ok_x,ok_x );
				else
					console.log( data );
			}
		});
		ok_x();
	});

	var last = 0;
	$("#msg").keydown(function(e) {

		if( e.keyCode == 13 && last != 16){
			$("#newComment").submit();
			return false;
		}
		if( last != e.keyCode )
			last = e.keyCode;
	});
	$(document).keyup(function(e) {
		if (e.keyCode == 27) { // escape key maps to keycode `27`
			cancelmyAlert();
		}
	});

	if( $.cookie("user") != '' ){
		$("#user").val( $.cookie("user") );
		$("#msg").focus();
	}

	$("label[for='msg']").on("click", function(e){
		$(".msg-container").slideDown("fast");
		$(".file-container").slideUp("fast");
		var $file = $("#file");
		$file.removeAttr('required');
		$file.val("");
		$("#msg").prop('required', true);
	});
	$("label[for='file']").on("click", function(e){
		$(".msg-container").slideUp("fast");
		$(".file-container").slideDown("fast");
		var $msg = $("#msg");
		$msg.removeAttr('required');
		$msg.val("");
		$("#file").prop('required', true);
		if( !$("#msg").is(":hidden") )
			e.preventDefault();
	});


	$("#newComment input[type='reset']").click(function(){
		$("#user").focus();
		$.cookie("user", "");
	});


	$("#alertBtn").on("click", function(){
		$("#alerta").slideUp('fast');
		if( alertCallback_ok != 0 )
			alertCallback_ok();
		alertCallback_ok = 0;
		alertCallback_cancel = 0;
	});

}				// function main ends
$(main);


var currentContent = [];
var msg_base = '';
function poll(){
	console.log("polling...");
	if( msg_base == '' )
		update_msg_base();
	setTimeout(updateMsgs,2000);
}
function updateMsgs(){
	$.getJSON('./ajax/getMsgs.php', function(json, textStatus) {

		if( msg_base == '' ){
			poll();
			return;
		}
		var $msgsContainer = $(".msgsContainer");
		if( Object.keys( json ).length == 0 ){
			$msgsContainer.html("<h3>No hay mensajes</h3>");
			poll();
			return;
		}
		news_ = isthereSomeNew(json);
		if( news_.length != 0 ){		// If news_ has entries, it means there are new msgs	
			if( currentContent.length == 0 )
				$msgsContainer.html("");
			$.each( news_ , function(index, val) {
				 currentContent.push( val.id );
				 beautyAppend( $msgsContainer, render_msg( val ) );
			});
			var $noImg = $("img[src='']");
			$noImg.css('display', 'none');
			$noImg.attr('src', "./imgs/uploaded/x.png");
			$msgsContainer.animate({scrollTop: $msgsContainer.height()*3}, 300);
			$( ".msg" ).off( "click" );
			$( ".msg" ).click( msgsOnClick );
		}
		poll();
	});
}
function isthereSomeNew( json ){
	var news_ = [];
	var temp = 0;
	$.each(json, function(index, val) {
		temp = $.inArray( val.id , currentContent);
		if( temp == -1 )
			news_.push( val );
	});
	return news_;
}
function update_msg_base(){
	$.get('./views/msg.html', function(data) {
		msg_base = data;
	});
}
function render_msg( msg ){
	var temp = msg_base.replace( "|_USER_|", msg.user )
						.replace( "|_MSG_|", msg.msg );
	var w = ( msg.msg.length < 1 ) ? "./imgs/uploaded/"+msg.uri : '';
	temp = temp.replace( "|_IMG_|", w );
	return temp.replace( "|_ADDRESS_|", msg.ip )
				.replace( "|_TIME_|", msg.time_ );
}
function beautyAppend( container, html ){
	var item = $(html);
	item.css('display', 'none');
	container.append(item );
	item.show("slow");
}
var msgsOnClick = function(event) {
	var myOthers = $(this).children('.others');

	$(".msgsContainer .others").not(myOthers).slideUp("fast");
	myOthers.slideToggle("fast");
}

var alertCallback_ok = 0;
var alertCallback_cancel = 0;
function myAlert(msg, title, callback_ok, callback_cancel){
	if( msg == undefined )
		return;
	if( title == undefined )
		title = 'msg';
	if( callback_ok == undefined )
		callback_ok = 0;
	if( callback_cancel == undefined )
		callback_cancel = 0;
	alertCallback_ok = callback_ok ;
	alertCallback_cancel = callback_cancel ;	
	$("#alertTitle").text(title);
	$("#alertMsg").html(msg);
	$("#alerta").slideDown('fast');
	$("#alertBtn").focus();
}
function cancelmyAlert(){
	$("#alerta").slideUp('fast');
	if( alertCallback_cancel != 0 )
		alertCallback_cancel();
	alertCallback_ok = 0;
	alertCallback_cancel = 0;
}
