<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<LINK rel="stylesheet" type="text/css" name="forms" href="formLayout.css">
<LINK rel="stylesheet" type="text/css" name="searchCSS" href="layout.css">
<LINK rel="stylesheet" type="text/css" name="searchCSS" href="searchLayout.css">
<?php 
	include 'necessities.php';
	$page = 'search';
?>

<title>Grub Pal - <?php if(isset($_GET['sstr'])){echo $_GET['sstr'];}else{echo "Search";}?></title>
<script type="text/javascript">

$(document).ready(function(){

	var restPage=0; 
	var dishPage=0; 

	$('#searchMapWrapper').load("searchMap2.php", {s_str: $("#searchTextEntry").val(), page: restPage});
	
	
	//load search result
	$("#sRests").load("searchResult.php", {type: "places", s_str: $("#searchTextEntry").val(), page: restPage});
	$("#sDishes").load("searchResult.php", {type: "dishes", s_str: $("#searchTextEntry").val(), page: dishPage});
	
	//search entry update
	$('#searchTextEntry').keyup(function(e){
		var code = (e.keycode? e.keycode : e.which);
		if(code == 13)
		{
			$("#sRests").load('searchResult.php', {type: "places", s_str: $("#searchTextEntry").val(), page: restPage});
			$("#sDishes").load('searchResult.php', {type: "dishes", s_str: $("#searchTextEntry").val(), page: dishPage});
			$('#searchMapWrapper').load("searchMap2.php", {s_str: $("#searchTextEntry").val(), page: restPage});
		}
	});
	
	//hide filters
	$("#filters").hide();
	$('#tabLink').click(function(){
		//check if restLink is already active
		if( $('#filters').is(':hidden') ) {
			$("#filters").show("blind", "slow");
		}
		else
		{
			$("#filters").hide("blind", "slow");
		}
			return false;
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
		
        	<div id="stage_blank" style="height: 345px;">				
                <div id="searchBox" style="height: 325px; width: 285px; float: left; background-image: url('./images/searchFilterBG.png');">
						<form onsubmit="return false;"  id="searchForm" style="margin-left: 0px;">
							<input id="searchTextEntry" type="text" value="<?php if(isset($_GET['sstr'])){echo $_GET['sstr'];}else{echo "";}?>"/>
							<div id="filtersWrapper" style="">
								<div id="filters" style="">
									<div class="filterSet" style="width: 200px; margin: 30px 0px 0px 20px">
										<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
											<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
											Filter support will be coming up shortly.</p>
										</div>

									</div>
									<!--
									<div class="filterSet" style="margin: 30px 0px 0px 30px">
										<input type="checkbox" name="delivery" value="delivery" /> Delivery <br/>
										<input type="checkbox" name="kidFriendly" value="kidFriendly" /> Kid Friendly <br/>
										<input type="checkbox" name="takeOut" value="takeOut" /> Take Out <br/>
									</div>
									<div class="filterSet" style="margin: 30px 0px 0px 30px">
										<input type="checkbox" name="dollar1" value="dollar1" /> $ <br/>
										<input type="checkbox" name="dollar2" value="dollar2" /> $$ <br/>
										<input type="checkbox" name="dollar3" value="dollar3" /> $$$ <br/>
									</div>
									-->
								</div>
								<div id="filterTab">
									<a id="tabLink" href="#" onclick="return false;"></a>
								</div>
							</div>
						</form>
				</div>
				<div id="searchMapWrapper" style="padding-top: 1px;  float: left; margin-left: 10px; height: 325px; width: 597px; background-image: url('./images/searchMap2BG.png');">
					
				</div>
				<div id="clear"></div>
				
            </div><!--STAGE ENDS HERE-->
            
            <div id="resultWrapper">
                <div id="restResult">
			
						<div id="commentsTitle" style="margin-top: 0px; width: 425px;">
							<ul id="info_nav">
								<li class="active">Places</li>
							</ul>
						</div>
						<div id="sRests">
							<img id="loading" style="margin: 100px 0px 0px 150px;" src="./images/loading.gif"/>
						</div>
						
                </div>
				<div id="dishResult">
			
						<div id="commentsTitle" style="margin-top: 0px; width: 425px;">
							<ul id="info_nav">
								<li class="active">Dishes</li>
							</ul>
						</div>
						<div id="sDishes">
							<img id="loading" style="margin: 100px 0px 0px 150px;" src="./images/loading.gif"/>
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
