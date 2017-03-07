
<script type="text/javascript">
  google.load("identitytoolkit", "1", {packages: ["ac"]});
</script>
<script type="text/javascript">
   
$(function() { 
   $( "#actions" ).dialog({ position: 'center', 
								width: 400,
								title: 'Actions',
								autoOpen: false
							});	
   
   
							
  window.google.identitytoolkit.setConfig({
    developerKey: "AIzaSyARpZZaoEOl_NX8JR35F4Ah5BEjfwcEvyk",
    companyName: "haplette",
    callbackUrl: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp'; ?>/git/callback.php",
    realm: "",
    userStatusUrl: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>usrStatus.php",
    loginUrl: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>home.php",
    signupUrl: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>git/callback.php",
    homeUrl: "<?php echo $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>",
    logoutUrl: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>destroySession.php",
    idps: ["Gmail", "Yahoo", "AOL"],
    tryFederatedFirst: true,
    useCachedUserStatus: false
  });
  
  <?php 
		session_start();
	//	if(isset($_SESSION['id'])){
  ?>
  $("#navbar").accountChooser();
  <?php 
		if(isset($_SESSION['id'])){
  ?>
   window.google.identitytoolkit.setConfig({ dropdownmenu: [ 
    { 
      "label": "Feedback", 
      "url": "/hp/feedback.php"
    },
	{ 
      "label": "Actions", 
      "handler": onActionsClicked
    },
    { 
      "label": "Switch account",
      "handler": "onSwitchAccountClicked"
    },
    { 
      "label": "Log out",
      "url": "/logout",
      "handler": "onSignOutClicked"
    }
  ]});
  
  function onActionsClicked()
   {
		$('#actions').dialog('open'); 
		return true;
   }
  
  window.google.identitytoolkit.showSavedAccount("<?php echo $_SESSION['email']; ?>");
	<?php
	}
	?>
});
</script>

<script type="text/javascript">
$(document).ready(function(){

	/*MENU STUFF*/
	
	//slider for home
	$("a#home").mouseover(function(){
 		if($("div#homeSlider").is(":hidden")){
			$("div#homeSlider").show("slide", { direction: "up" }, "fast");
		}
 	});
	$("a#home").mouseout(function(){
 		if($("div#homeSlider").is(":visible")){
			$("div#homeSlider").hide("slide", { direction: "up" }, "fast");
		}
 	});
	
	//slider for fav
	$("a#fav").mouseover(function(){
 		if($("div#favSlider").is(":hidden")){
			$("div#favSlider").show("slide", { direction: "up" }, "fast");
		}
 	});
	$("a#fav").mouseout(function(){
 		if($("div#favSlider").is(":visible")){
			$("div#favSlider").hide("slide", { direction: "up" }, "fast");
		}
 	});
	
	//slider for explore
	$("a#exp").mouseover(function(){
 		if($("div#expSlider").is(":hidden")){
			$("div#expSlider").show("slide", { direction: "up" }, "fast");
		}
 	});
	$("a#exp").mouseout(function(){
 		if($("div#expSlider").is(":visible")){
			$("div#expSlider").hide("slide", { direction: "up" }, "fast");
		}
 	});
	
	//slider for search
	$("a#search").mouseover(function(){
 		if($("div#searchSlider").is(":hidden")){
			$("div#searchSlider").show("slide", { direction: "up" }, "fast");
		}
 	});
	$("a#search").mouseout(function(){
 		if($("div#searchSlider").is(":visible")){
			$("div#searchSlider").hide("slide", { direction: "up" }, "fast");
		}
 	});

	
	
	
	$("#addRestForm").load("./addPlaceForm.php", function(){
		$( "#launchAddRest" ).click(function() {
			$( "#addRest-form" ).dialog( "open" );
		});
	});
	
	$( ".upload-files" ).button()
	$( "#launchAddRest" ).button()
	

	
	//**********modal form to add restaurants ends here********************************
		///////////////////////////////////////////////////////////////////////////////////
	$( "#picWrapper" ).dialog({ position: 'top', 
								width: 650,
								title: 'Tell us about your pictures!',
								autoOpen: false
							});
												
});
</script>

<div id="logoHolder">
	<a style="text-decoration: none;" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>home.php">
		<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>/images/logo_grubpal.png"/>
	</a>
</div>
<!--
<div id="fb-root"></div>
  <script>
  /*
	window.fbAsyncInit = function() {
	  FB.init({
		appId      : '164932876924803',
		status     : true, 
		cookie     : true,
		xfbml      : true,
		oauth      : true,
	  });
	};
	(function(d){
	   var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	   js = d.createElement('script'); js.id = id; js.async = true;
	   js.src = "//connect.facebook.net/en_US/all.js";
	   d.getElementsByTagName('head')[0].appendChild(js);
	 }(document));
	 */
  </script>
  
-->

<div class="" id="topMenu" style="margin-right:30px;">

	<div style="top: 0; right: 0;" id="navbar"></div>
<!--
<div id="fbLogin2" style="float: right; margin-top: 5px; display: none;">
<span class="dish" style="margin-right: 10px;  ">or</span>
<fb:login-button scope="publish_stream,publish_actions">Login</fb:login-button>
</div>
<div id="fbUser" style="display: none; position: absolute; top: 0; right: 200px; margin-top: 0px;">
	<img style="float: left; height: 40px; width: 40px;"id="fImg"/>
	<div style="float:left; margin: 5px; 0px; 0px; 5px;" >
		<div class="rest" id="fName">
		
		</div>
		<div style="margin-left: 20px;">
			<a href="javascript: fbLogout();">Logout</a>
		</div>
	</div>
	<div id="clear"></div>
</div>

<div id='fbLogin' style="position: relative; float: left; margin: 5px 0px 0px 50px;" class="fb-login-button">Login with Facebook</div>	
	<a id="lgn" class="ui-widget-content ui-corner-br" href="javascript: void(0);">
		
	</a>
	<a id="fed" class="ui-widget-content" style="" href="javascript: void(0);">Feedback</a>
	<a id="abt" class="ui-widget-content ui-corner-bl" href="javascript: void(0);">About</a>

-->	
</div>

<div id="clear">
</div>

<div id="menuHolder">
	<div id="menu">
    	<ul>
        	<li><a id="home" href="./home.php"></a><div class="slider" id="homeSlider"></div></li>
            <li><a id="fav" href="./favorite.php"></a><div class="slider" id="favSlider"></div></li>
			<li><a id="exp" href="./explore.php"></a><div class="slider" id="expSlider"></div></li>
            <li><a id="search" href="./search.php"></a><div class="slider" id="searchSlider"></div></li>
        </ul>
	</div>
</div>
<!-- MODAL STUFF HERE -->
<div id="picWrapper">
		
</div>

<div id="actions">
<ul style="margin-left: 10px;">
<li>
	<div style="text-align: left;">Can't find your favorite place here? Click Below to Add a new place. </div>
	<button id='launchAddRest'> Add Place</button>
</li>
<li>
<div style="text-align: left;">Click Below to upload pictures. You can connect them to Restaurants and Dishes Once they Uploaded. </div>
	<div id="linkInputs" style="margin-left: 10px;">
		<form id="file_upload" class="upload-files" action="./admin/uploadImages.php" method="POST" enctype="multipart/form-data">
			
			<input type="file" name="file" multiple />
			<button>Upload</button>
			<div >Upload Images</div>
		</form>	
		<table id="files"></table>
	</div>
</li>
</ul>
</div>



	
<div id="addRestForm">
</div>

