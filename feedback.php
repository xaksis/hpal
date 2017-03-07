<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	include 'necessities.php';
	$page = 'feedback';
?>

<title>Happy Palette - Feedback</title>
<script type="text/javascript">

$(document).ready(function(){
	
	$('#sendBtn').click(function(){
		var body = $('#body_entry').val(); 
		var subject = $('#subject_entry').val();
		var email = $('#email_entry').val(); 
		if(body != '' && subject != '')
		{
			$.ajax({
				url: "sendFeedback.php",
				global: false,
				type: "POST",
				data: ({email: email, comment: body, subject: subject}),
				dataType: "html",
				success: function(html){
					if(html=="Feedback Saved!")
					{
						$("#formElems").hide("blind", "slow", function(){
							$("#thankyou").show("blind", "slow");
						});
						$("#feedbacks").load("feedbacks.php", {page: 0, n: 10});
					}
				}
			});
		}
	});
	
	$("#feedbacks").load("feedbacks.php", {page: 0, n: 10});
});      

</script>


</head>

<body>
<div id="searchBody">
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content" style="text-align: center;">
			<div class="ui-widget" id="thankyou" style="text-align: left; display: none;">
				<div class="ui-state-highlight ui-corner-all" style="margin-left: auto; margin-right: auto; width: 350px; height: 20px; margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					Thank you! Your feedback is submitted for review.</p>
				</div>
			</div>
			<div id="clear"></div>
			<div id='formElems' style="text-align: left; width: 500px; margin-left: auto; margin-right: auto;">
				<div id='SaveResponse'></div>
				<span class="dish" style="width: 70px; display: block; float: left; margin-top: 5px;">Your Email</span>
				<input id="email_entry" style="width: 400px; height: 20px; margin-bottom: 10px;" class="text ui-widget-content ui-corner-all" />			
				<div id="clear"></div>
				
				<span class="dish" style="width: 70px; display: block; float: left;">Subject</span>
				<input id="subject_entry" style="width: 400px; height: 20px; margin-bottom: 10px;" class="text ui-widget-content ui-corner-all" />
				<div id="clear"></div>
				
				<span class="dish" style="width: 70px; display: block; float: left;">Comment</span>
				<textarea id="body_entry" style="float: left; width: 350px; height: 100px;" class="text ui-widget-content ui-corner-all"></textarea>
				<a id='sendBtn' href="javascript:void(0);" style='float: left; padding: 5px; display: block; height: 15px; width:30px; margin:73px 0px 0px 5px;' class="ui-widget-content ui-corner-all rest">
					Send
				</a>
				<div id="clear"></div>
			</div>
			<div id="dishtitle" style="text-align: left; margin: 10px 0px 0px 150px;">
				<span>Feedback</span>
            </div>
			<div id="feedbacks" style='margin-left: auto; margin-right: auto; text-align: left; width: 600px; height: 800px;' class="ui-widget-content ui-corner-all">
				
			</div>
			
        </div><!-- CONTENT ENDS HERE-->
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>
</div >
</body>
</html>
