Copyright &#169; 2011 Haplette.com 
<script type="text/javascript">
/*global $ */
$(function () {
    $('#file_upload').fileUploadUI({
        uploadTable: $('#files'),
        downloadTable: $('#files'),
        buildUploadRow: function (files, index) {
            return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
        },
		beforeSend: function (event, files, index, xhr, handler, callBack) {
            handler.formData = {
                rest_id: 0,
				user_id: 1
            };
            callBack();
		},
        buildDownloadRow: function (file) {
            return $('<tr><td>' + file.name + '<\/td><\/tr>');
        },
		onComplete: function (event, files, index, xhr, handler){
			handler.onCompleteAll(files); 
		},
		onCompleteAll: function(files){
			if (!files.uploadCounter) {
                files.uploadCounter = 1;  
            } else {
                files.uploadCounter = files.uploadCounter + 1;
            }
            if (files.uploadCounter === files.length) {
                /* your code after all uplaods have completed */
				$("#picWrapper").load("./admin/loadUploaded.php", {restID: 0});
				$('#picWrapper').dialog('open'); 
            }
		}
    });
});
</script>

<script type="text/javascript">
/*
<div id="fb-root"></div>
function fbLogout()
  {
	FB.logout(function(response){
		location.reload(true);
	});
	
  }
  
$(document).ready(function(){
  FB.init({
    appId  : '164932876924803',
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
    //channelURL : 'http://70.104.139.232:8081/hp/channel.php', // channel.html file
    oauth  : true // enable OAuth 2.0
  });
    
	FB.getLoginStatus(function(response){
		if(response.authResponse)
		{
			FB.api('/me', function(user) {
			   if(user != null) {
				  
				  $('#fbLogin').hide();		  
				  var image = document.getElementById('fImg');
				  image.src = 'http://graph.facebook.com/' + user.id + '/picture';
				  var name = document.getElementById('fName');
				  name.innerHTML = user.name
				  $('#fbUser').show();
				  $('#navbar').hide();
			   }
			   else
			   {
					$('#fbUser').hide();
					$('#fbLogin').show();
					$('#navbar').show();
			   }
			   
			 });
		}
		else
		{
			
		}
	});
  
	FB.Event.subscribe('auth.logout', function(response) {
		$.ajax({
					url: "destroySession.php",
					global: false,
					type: "POST"
					});
	}); 
  
  
	FB.Event.subscribe('auth.login', function(response) {
		//create new facebook user and/or create session
		// register the user
		if(response.authResponse)
		{
			FB.api('/me', function(user) {
			   if(user != null) {
				  //create a new user
				  $.ajax({
					url: "createFbUser.php",
					global: false,
					type: "POST",
					data: ({fName: user.first_name, lName: user.last_name, name: user.name, id: user.id}),
					dataType: "html",
					success: function(html){
						$('#fbLogin').hide();		  
						  var image = document.getElementById('fImg');
						  image.src = 'http://graph.facebook.com/' + user.id + '/picture';
						  var name = document.getElementById('fName');
						  name.innerHTML = user.name
						  $('#fbUser').show();
						  $('#navbar').hide();
						  $('#fbLogin').hide(); 
					}
				});
			   }
			   else
			   {
					$('#fbUser').hide();
					$('#fbLogin').show();
					$('#navbar').show();
			   }
			   
			 });
		}
		//location.reload(true);
	});
  
  
  FB.getLoginStatus(function(user){
	if(user.authResponse)
	{
		 FB.API('/me', function(user) {
           if(user != null) {
				alert(user.error);
			  $('#fbLogin').hide();		  
              var image = document.getElementById('fImg');
              image.src = 'http://graph.facebook.com/' + user.id + '/picture';
              var name = document.getElementById('fName');
              name.innerHTML = user.name
			  $('#fbUser').show();
			  $('#fbLogin').hide();
		   }
		   else
		   {
				$('#fbUser').hide();
				$('#fbLogin').show();
		   }
         });
	}
	else
	{
	}
  });
});
 */
</script>