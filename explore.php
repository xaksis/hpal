<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<LINK rel="stylesheet" type="text/css" name="forms" href="formLayout.css">
<LINK rel="stylesheet" type="text/css" name="searchCSS" href="layout.css">
<LINK rel="stylesheet" type="text/css" name="searchCSS" href="searchLayout.css">
<?php 
	include 'necessities.php';
	$page = 'explore';
?>

<title>Grub Pal - <?php if(isset($_GET['sstr'])){echo $_GET['sstr'];}else{echo "Browse";}?></title>
<script type="text/javascript">

$(document).ready(function(){

	var restPage=0; 
	var dishPage=0; 
	$('#eNavAccordion').accordion({
		active: false,
		autoHeight: false,
		collapsible: true
	});
	
	$("#rrHolder").load('restForCategory.php', {cat: '', start: 0}, function(){
		$(this).child('#rrHolder').fadeIn("slow"); 
	});
	$("#rrHolder2").load('restForCategory.php', {cat: '', start: 11}, function(){
		$(this).child('#rrHolder').fadeIn("slow"); 
	});
	
	$(".sidebar_link").each(function(){
		$(this).bind("click", function(){
			//if already active, do nothing.
			if($(this).hasClass('active'))
				return;
				
			$(".sidebar_link").each(function(){
				$(this).removeClass('active');
			});
			$(this).addClass('active');
			var catName = $(this).text();
			$("#rrHolder").load('restForCategory.php', {cat: catName, start: 0}, function(){
				$(this).child('#rrHolder').fadeIn("slow"); 
			});
			$("#rrHolder2").load('restForCategory.php', {cat: catName, start: 11}, function(){
				$(this).child('#rrHolder').fadeIn("slow"); 
			});
		});
	});
	
});      

</script>


</head>

<body>
<div id="searchBody">
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content">
			<div id="eNav">
				<div id="eNavAccordion">
					<?php
						require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
						require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
						require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
						
						$rm = new RestModel();
						$mcats = array(); 
						$response = $rm->getSubCategories($mcats); 
						foreach($mcats as $cat)
						{
							echo "<h3><a href='#'>".$cat['subcat']."</a></h3>";
							echo "<div>";
							$cats = array(); 
							$nResp = $rm->getCuisinesForSubcat($cats, $cat['subcat']);
							foreach($cats as $cuisine)
							{
								echo "<a href='javascript:void();' id='".str_replace(' ', '', $cuisine['name'])."' class='sidebar_link'><span class='label'>",$cuisine['name'],"</span></a>";
							}
							echo "</div>";
						}
					?>
				</div>
			</div>
			<div id='resultArea'>
					<div id="restResult" style="float: left; width: 337px; min-height: 400px; border: none;">
						<div id='rrHolder'>
							<img id="restLoading" style="margin: 120px 0px 0px 120px;" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/hp/images/loading.gif" />
						</div>
					</div>
					<div id="restResult" style="float: left; width: 337px; min-height: 400px; border: none;">
						<div id='rrHolder2'>
							<img id="restLoading" style="margin: 120px 0px 0px 120px;" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/hp/images/loading.gif" />
						</div>
					</div>
			</div>
        </div><!-- CONTENT ENDS HERE-->
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>
</div >
</body>
</html>
