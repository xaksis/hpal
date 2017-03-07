<?php
	session_start();
	//only logged in users have access here
if(!isset($_SESSION['privilege']) || $_SESSION['privilege'] != 1)
	header('location:../destroySession.php');
?>
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
		var geocoder;
		function populateEditForm(data)
		{
			$("#rest_name_e").val(data.name) ;
			$("#rest_addr_e").val(data.address);
			$("#rest_phone_e").val(data.phone);
			$("#rest_genre_e").val(data.genre);
			$("#rest_city_e").val(data.city);
			$("#rest_state_e").val(data.state);
			$("#rest_zip_e").val(data.zipcode);
			$("#rest_adminRating_e").val(data.adminRating);
		}
		
		//function to geocode. 
		function codeAddressAndAdd(rest) {
			var address = rest.address + ", " + rest.city + ", " + rest.state + " " + rest.zip;
			//alert(address);
			geocoder =  new google.maps.Geocoder();
			geocoder.geocode( { 'address': address}, function(results, status) {
				//alert(status);
				if (status == google.maps.GeocoderStatus.OK && results.length) {
					//alert(results[0].geometry.location.lat());
					var newRestId=0; 
					rest.lat = results[0].geometry.location.lat(); 
					rest.lng = results[0].geometry.location.lng(); 
					$.ajax({
						url: "addRestaurant.php",
						global: false,
						type: "POST",
						data: ({name: rest.name, 
								address: rest.address, 
								phone: rest.phone, 
								genre: rest.genre,
								city: rest.city,
								state: rest.state,
								zip: rest.zip,
								lat: rest.lat,
								lon: rest.lng,
								adminRating: rest.adminRating }),
						async: false,
						dataType: "html",
						success: function(html){
									newRestId=html; 
									//if restaurant was added successfully 
									if(newRestId !=0)
									{
										$('#resultDiv').html("Restaurant successfully added.");	
										//go through each category and add them to the restaurant
										$('#cat_tbl tr').each(function(){
											var $thisTr = $(this); 
											var cuisine_id=0;
											if(!$(this).find('.cuisine_sel'))
												alert("select not found");
											else 
											{
												cuisine_id = $(this).find('.cuisine_sel').val();
												if(cuisine_id && cuisine_id != '0')
												{
													$.ajax({
														url: "addCategory.php",
														global: false,
														type: "POST",
														async: false,
														data: ({rest_id: newRestId, cat_id: cuisine_id}),
														dataType: "html",
														success: function(html){
															$thisTr.html('<td>Category Added!</td>');  
														}
													});
												}
											}
										});
									}
								}
					});
					 
				} else {
					alert("geocode failed: " + status);
				}
			});
			return location; 
		}
		
		//function to enter a new restaurant
		$('#submitButton').click(function(){
			var restaurant = {	name: $("#rest_name").val(),
								address: $("#rest_addr").val(),
								phone: $("#rest_phone").val(),
								genre: $("#rest_genre").val(),
								city: $("#rest_city").val(),
								state: $("#rest_state option:selected").val(),
								zip: $("#rest_zip").val(),
								adminRating: $("#rest_adminRating").val(),
								lat: 0,
								lng: 0
							}; 
			
			if(restaurant.name == ""){
				alert("name cannot be empty");
				return; 
			} 
			//geocode to get lat, long
			codeAddressAndAdd(restaurant); 
		});
		
		//function to enter a new restaurant
		$('#submitButton_e').click(function(){
			var restId = $('#restList2 option:selected').val();
			var restName= $("#rest_name_e").val();
			var restAddr= $("#rest_addr_e").val();
			var restPhone= $("#rest_phone_e").val();
			var restGenre= $("#rest_genre_e").val();
			var restCity= $("#rest_city_e").val();
			var restState= $("#rest_state_e option:selected").val();
			var restZip= $("#rest_zip_e").val();
			var restAdminRating= $("#rest_adminRating_e").val();
			if(restName == ""){
				alert("name cannot be empty");
				return; 
			}
			var address = restAddr + ", " + restCity + ", " + restState + " " + restZip;
			//alert(address);
			geocoder =  new google.maps.Geocoder();
			geocoder.geocode( { 'address': address}, function(results, status) {
				//alert(status);
				if (status == google.maps.GeocoderStatus.OK && results.length) {
					var restLat = results[0].geometry.location.lat(); 
					var restLng = results[0].geometry.location.lng(); 
					$.ajax({
						url: "updateRestaurant.php",
						global: false,
						type: "POST",
						data: ({id: restId,
								name: restName, 
								address: restAddr, 
								phone: restPhone, 
								genre: restGenre,
								city: restCity,
								state: restState,
								zip: restZip,
								lat: restLat,
								lon: restLng,
								adminRating: restAdminRating }),
						dataType: "html",
						success: function(html){
							$('#resultDiv_e').html(html);	
						}
					});
				}
			}); 
			
		});
		
		var noOfFiles = 0; 
		
		//function to upload images
		$('#restList').click(function(){
			var restID = $('#restList option:selected').val(); 
			noOfFiles = 0; 
			$('#linkInputs').show(); 
			//$('#linkInputs').append("<br/> <input type='file' id='image1' />"); 
		});
	
/*	
		$('.imageFile').change(function(){
			var chngedId = $(this).attr("id");
			var maxInputId = "image"+noOfFiles;
			alert(chngedId +":"+maxInputId );
			if(chngedId == maxInputId)
			{
				noOfFiles++;
				var fileHtml = $('#linkInputs').html(); 
				$('#linkInputs').html(fileHtml+"<br/> <input class='imageFile' id='image"+noOfFiles+"' type='file' />")
				//$('#'+chngedId).after("<br/> <input class='imageFile' id='image"+noOfFiles+"' type='file' />"); 
			}
		}); 
*/

		$('#restList2').click(function(){
			var restID = $('#restList2 option:selected').val(); 
			
			$.ajax({
					url: "getRestaurantInfo.php",
					global: false,
					type: "POST",
					data: ({rest_id: restID}),
					dataType: "json",
					success: function(data){
						populateEditForm(data); 
					}
				}
			);
		});
		
		$('#d_restList').click(function(){
			var restID = $('#d_restList option:selected').val(); 
			$('#dishListContainer').load('dishSelectList.php', {restId: restID}, 
			function(){
				$('#dishList').click(function(){
					var dishID = $('#dishList option:selected').val(); 
					$('#gDishContainer').load("gDishForm.php", {dishId: dishID}); 
				});
				
			}); 
		});
		
		
		
		$('.getDataButton').click(function(){
			var siteID = $(this).attr("id");
			var restID = $('#restList2 option:selected').val();  
			$('#stolenData').html('<img src="./images/loading.gif" alt="fetching data"/>');
			$.ajax({
					url: "stealData.php",
					global: false,
					type: "POST",
					data: ({rest_id: restID, site_id: siteID}),
					dataType: "html",
					success: function(html){
						$('#stolenData').html(html);	
					}
				}
			);
			
		});
		
		$('#signUpButton').click(function(){
			var username = $('#dUsername').val();
			var password = $('#dPassword').val();
			var cPassword = $('#cPassword').val();
			if(username == '' || password == '' || cPassword == '')
			{
				$('#signUpError').html('All fields are required');
				return;
			}
			if(cPassword != password)
			{
				$('#signUpError').html('Passwords do not match');
				return;
			}	
			$.ajax({
					url: "addUser.php",
					global: false,
					type: "POST",
					data: ({user: username, pass: password, priv: 1}),
					dataType: "html",
					success: function(html){
						if(html=='Success')    
							$('#signUpError').html('<span style="color: green;">Addition Successful. Thank You</span>');
						else	
							$('#signUpError').html(html);
					}
				}
			);	
		});
		
		$('#cancel_btn').click(function(){
			$('#picWrapper').hide(); 
		});
		
		$("#accordion").accordion();
		
		//should be removed
		$("#picWrapper").load("loadUploaded.php", {userID: 1});
		
		var noOfCats = 3; 
		$("#cat_tbl").load("subcategories.php", {selectId: noOfCats});
 
	});
</script>

</head>


<body>
	<div id="picWrapper">
		
	</div>
    <div id="outerWrapper">
      	<div id="header">
			<?php include '../header.php'?>
        </div>
        <div id="belowStage" style="padding-top: 1px;">
        <div id="accordion" style="margin-top: 30px; width: 800px; float: left;">
            
            <h3><a href="#">Add a new Restaurant</a></h3>
            <div>
                <div id="resultDiv" style="height: 30px;"></div>
                <form style="float: left;">
                	<table style="float: left;">
                    	<tr>
                        	<td>Name:</td>	<td> <input id="rest_name" type="text" /></td>
                        </tr>
                        <tr>
                        	<td>Address: </td>	<td> <input id="rest_addr" type="text" /></td>
                        </tr>
						<tr>
                        	<td>City: </td>	<td> <input id="rest_city" type="text" /></td>
                        </tr>
						<tr>
                        	<td>State: </td>	<td><select id="rest_state" size="1">
													<option value="AK">AK</option>
													<option value="AL">AL</option>
													<option value="AR">AR</option>
													<option value="AZ">AZ</option>
													<option value="CA">CA</option>
													<option value="CO">CO</option>
													<option value="CT">CT</option>
													<option value="DC">DC</option>
													<option value="DE">DE</option>
													<option value="FL">FL</option>
													<option value="GA">GA</option>
													<option value="HI">HI</option>
													<option value="IA">IA</option>
													<option value="ID">ID</option>
													<option value="IL">IL</option>
													<option value="IN">IN</option>
													<option value="KS">KS</option>
													<option value="KY">KY</option>
													<option value="LA">LA</option>
													<option value="MA">MA</option>
													<option value="MD">MD</option>
													<option value="ME">ME</option>
													<option value="MI">MI</option>
													<option value="MN">MN</option>
													<option value="MO">MO</option>
													<option value="MS">MS</option>
													<option value="MT">MT</option>
													<option value="NC">NC</option>
													<option value="ND">ND</option>
													<option value="NE">NE</option>
													<option value="NH">NH</option>
													<option value="NJ">NJ</option>
													<option value="NM">NM</option>
													<option value="NV">NV</option>
													<option value="NY">NY</option>
													<option value="OH">OH</option>
													<option value="OK">OK</option>
													<option value="OR">OR</option>
													<option value="PA">PA</option>
													<option value="RI">RI</option>
													<option value="SC">SC</option>
													<option value="SD">SD</option>
													<option value="TN">TN</option>
													<option value="TX">TX</option>
													<option value="UT">UT</option>
													<option value="VA">VA</option>
													<option value="VT">VT</option>
													<option value="WA">WA</option>
													<option value="WI">WI</option>
													<option value="WV">WV</option>
													<option value="WY">WY</option>
																	</select></td>
                        </tr>
						<tr>
                        	<td>zipcode: </td>	<td> <input id="rest_zip" type="text" /></td>
                        </tr>
                    	<tr>
                        	<td>Phone: </td>	<td> <input id="rest_phone" type="text" /></td>
                        </tr>
						<tr>
                        	<td>Rating: </td>	<td> <input id="rest_adminRating" type="text" /></td>
                        </tr>
                    	<tr>
                        	<td><input type="button" id="submitButton" value="submit"  /></td>	<td> </td>
                        </tr>
                	</table>
					<table id="cat_tbl" style="float: left;">
						
					</table>
					<div id="clear">
					</div>
                </form>
            </div>
            
            <h3><a href="#">Add Images for Restaurants</a></h3>
            <div>
                <div id="resultDiv2"></div>
                <div>
                	<div id="listContainer" style="float:left;">
                    <?php
					
                        include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
                        //open database connection
                        $connection = mysql_connect($host, $user, $pass) or die('could not connect');
                        //select database
                        mysql_select_db($dbName) or die('could not select database');
                        //insert data
                        $query = "SELECT id, name FROM restaurants";
                        //execute query
                        $result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
                        
                        //check if results returned
                        if(mysql_num_rows($result)>0)
                        {
                            echo "<select id='restList' size='13'>";
                            while($row = mysql_fetch_assoc($result))
                            {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>"; 
                            }
                            echo "</select>";
                        }
                        else
                        {
                            echo "No restaurants found.";
                        }
					?>
                    </div>
					<div id="linkInputs" style="float: left; display: none; margin-left: 10px;">
						<form id="file_upload" action="uploadImages.php" method="POST" enctype="multipart/form-data">
							
							<input type="file" name="file" multiple>
							<button>Upload</button>
							<div>Upload files</div>
						</form>	
						<table id="files"></table>
					</div>
					<div id="clear"></div>
				</div>
            </div>
                
            <h3><a href="#">Edit Restaurant</a></h3>
            <div style="height: 160px;">
				<form>
					<div style="float:left;">
						<?php
							
							mysql_free_result($result);
							$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
							
							//check if results returned
							if(mysql_num_rows($result)>0)
							{
								echo "<select id='restList2' size='13'>";
								while($row = mysql_fetch_assoc($result))
								{
									echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>"; 
								}
								echo "</select>";
							}
							else
							{
								echo "No restaurants found.";
							}?>
							
					</div>
					<div class="explanation" style="float: left; margin-left: 30px;">
						<div id="resultDiv_e"></div>
						<table>
							<tr>
								<td>Name:</td>	<td> <input id="rest_name_e" type="text" /></td>
							</tr>
							<tr>
								<td>Address: </td>	<td> <input id="rest_addr_e" type="text" /></td>
							</tr>
							<tr>
								<td>City: </td>	<td> <input id="rest_city_e" type="text" /></td>
							</tr>
							<tr>
								<td>State: </td>	<td><select id="rest_state_e" size="1">
														<option value="AK">AK</option>
														<option value="AL">AL</option>
														<option value="AR">AR</option>
														<option value="AZ">AZ</option>
														<option value="CA">CA</option>
														<option value="CO">CO</option>
														<option value="CT">CT</option>
														<option value="DC">DC</option>
														<option value="DE">DE</option>
														<option value="FL">FL</option>
														<option value="GA">GA</option>
														<option value="HI">HI</option>
														<option value="IA">IA</option>
														<option value="ID">ID</option>
														<option value="IL">IL</option>
														<option value="IN">IN</option>
														<option value="KS">KS</option>
														<option value="KY">KY</option>
														<option value="LA">LA</option>
														<option value="MA">MA</option>
														<option value="MD">MD</option>
														<option value="ME">ME</option>
														<option value="MI">MI</option>
														<option value="MN">MN</option>
														<option value="MO">MO</option>
														<option value="MS">MS</option>
														<option value="MT">MT</option>
														<option value="NC">NC</option>
														<option value="ND">ND</option>
														<option value="NE">NE</option>
														<option value="NH">NH</option>
														<option value="NJ">NJ</option>
														<option value="NM">NM</option>
														<option value="NV">NV</option>
														<option value="NY">NY</option>
														<option value="OH">OH</option>
														<option value="OK">OK</option>
														<option value="OR">OR</option>
														<option value="PA">PA</option>
														<option value="RI">RI</option>
														<option value="SC">SC</option>
														<option value="SD">SD</option>
														<option value="TN">TN</option>
														<option value="TX">TX</option>
														<option value="UT">UT</option>
														<option value="VA">VA</option>
														<option value="VT">VT</option>
														<option value="WA">WA</option>
														<option value="WI">WI</option>
														<option value="WV">WV</option>
														<option value="WY">WY</option>
																		</select></td>
							</tr>
							<tr>
								<td>zipcode: </td>	<td> <input id="rest_zip_e" type="text" /></td>
							</tr>
							<tr>
								<td>Phone: </td>	<td> <input id="rest_phone_e" type="text" /></td>
							</tr>
							<tr>
								<td>Genre: </td>	<td> <input id="rest_genre_e" type="text" /></td>
							</tr>
							<tr>
								<td>Rating: </td>	<td> <input id="rest_adminRating_e" type="text" /></td>
							</tr>
							<tr>
								<td><input type="button" id="submitButton_e" value="submit"  /></td>	<td> </td>
							</tr>
						</table>
					</div>
				</form>
            </div>
                
			<h3><a href="#">Add new administrator</a></h3>
			<div>
				<div class="errorExplanation" id="signUpError">
				</div>
				<table>
					<tr>
						<td>Desired Username: </td><td><input id="dUsername" type="text" class="text"/></td>
					 </tr>
					 <tr>   
						<td>Desired Password: </td><td><input id="dPassword" type="password" class="text"/></td>
					 </tr>
					 <tr>   
						<td>Confirm Password: </td><td><input id="cPassword" type="password" class="text"/></td>
					 </tr>
					 <tr>   
						<td></td><td><input id="signUpButton" style="float: right;" type="button" value="Add Admin" /></td>
					</tr>
				</table>
			</div>
				
			<h3><a href="#">Add Dish information</a></h3>
			<div>
				<div id="listContainer" style="float:left;">
				<?php
				
					include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
					//open database connection
					$connection = mysql_connect($host, $user, $pass) or die('could not connect');
					//select database
					mysql_select_db($dbName) or die('could not select database');
					//insert data
					$query = "SELECT id, name FROM restaurants";
					//execute query
					$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
					
					//check if results returned
					if(mysql_num_rows($result)>0)
					{
						echo "<select id='d_restList' size='13'>";
						while($row = mysql_fetch_assoc($result))
						{
							echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>"; 
						}
						echo "</select>";
					}
					else
					{
						echo "No restaurants found.";
					}
				?>
				</div>
				<div id="dishListContainer" style="float:left">
				</div>
				<div id="gDishContainer" style="float:left">
				</div>
			</div>
				
        </div><!--accordian ends-->
        </div>
        <div id="footer">
        </div>
    </div>    
	
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>-->
<script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/js/'; ?>jquery.fileupload.js"></script>
<script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/js/'; ?>jquery.fileupload-ui.js"></script>
<script>
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
                rest_id: $('#restList option:selected').val(),
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
				$("#picWrapper").load("loadUploaded.php", {restID: $('#restList2 option:selected').val()});
				$('#picWrapper').show(); 
            }
		}
    });
});
</script> 
	
	
</body>
</html>
