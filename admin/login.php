<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include $_SERVER['DOCUMENT_ROOT'].'/hp/necessities.php'?>
<LINK rel="stylesheet" type="text/css" name="admin" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/admin/'; ?>layout.css">
<title>Add Restaurant</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('#login_btn').click(function(){
			var username = $('#user_entry').val();
			var password = $('#pass_entry').val();
			$.ajax({
					url: "loginUser.php",
					global: false,
					type: "POST",
					data: ({user: username, pass: password}),
					dataType: "html",
					success: function(html){
						if(html=='Success')    
							$(location).attr('href','adminArea.php');
						else	
							$('#resultDiv').html(html);	
					}
				}
			);	
		});
		
		$("#accordion").accordion();
	});
</script>

</head>


<body>
    <div id="outerWrapper">
      	<div id="header">
			<?php include '../header.php'?>
        </div>
        <div id="belowStage" style="padding-top: 1px;">
        <div id="accordion" style="margin-top: 30px; width: 600px; float: left;"> 
			<h3><a href="#">Log In</a></h3>
			<div>
			Please log in to access admin Area. 
			<!-- 
				<div class="errorExplanation" id="signUpError">
				</div>
				<table>
					<tr>
						<td>Username: </td><td><input id="user_entry" type="text" class="text"/></td>
					 </tr>
					 <tr>   
						<td>Password: </td><td><input id="pass_entry" type="password" class="text"/></td>
					 </tr>
					 <tr>   
						<td></td><td><input id="login_btn" style="float: right;" type="button" value="Log In" /></td>
					</tr>
				</table>
			-->
			</div>
        </div><!--accordian ends-->
        </div>
        <div id="footer">
        </div>
    </div>    	
</body>
</html>
