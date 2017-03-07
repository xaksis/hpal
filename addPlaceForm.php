<script type="text/javascript">
$(function(){
		//modal form to add restaurants here
	//***************************************************************************************
	var name = $( "#rest_name" ),
		rAddress = $( "#rest_address" ),
		city = $( "#rest_city" ),
		state = $( "#rest_state" ),
		zip = $( "#rest_zip" ),
		phone = $( "#rest_phone" ),
		allFields = $( [] ).add( name ).add( rAddress )
							.add( city ).add( state )
							.add( zip ).add( phone ),
		tips = $( ".validateTips" );

		function updateTips( t ) {
			tips.text( t );
			tips.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		
		
		
		var geocoder;
		
		//function to geocode. 
		function codeAddressAndAdd(rest) {
			var address = rest.address + ", " + rest.city + ", " + rest.state + " " + rest.zip;
			geocoder =  new google.maps.Geocoder();
			geocoder.geocode( { 'address': address}, function(results, status) {
				//alert(status);
				if (status == google.maps.GeocoderStatus.OK && results.length) {
					//alert(results[0].geometry.location.lat());
					var newRestId=0; 
					rest.lat = results[0].geometry.location.lat(); 
					rest.lng = results[0].geometry.location.lng(); 
					$.ajax({
						url: "./admin/addRestaurant.php",
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
									newRestId=parseInt(html); 
									
									//if restaurant was added successfully 
									if(newRestId != 0)
									{
										alert(newRestId); 
										updateTips("Restaurant successfully added.");	
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
														url: "./admin/addCategory.php",
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
									else {
										rAddress.addClass( "ui-state-error" );
										updateTips( 'Address seems wrong' );
									}
								}
					});
					 
				} else {
					rAddress.addClass( "ui-state-error" );
					updateTips( 'Address seems wrong' );
					//alert("geocode failed: " + status);
				}
			});
			//alert (location); 
			return location; 
		}
		
		
		$( "#addRest-form" ).dialog({
			autoOpen: false,
			width: 400,
			modal: true,
			buttons: {
				"Add Place": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					//bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
					bValid = bValid && checkRegexp( phone, /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/, "eg: (123) 456 7890" );
					bValid = bValid && checkRegexp( zip, /^(\d{5})$/, "eg: 11111" ); 
					bValid = bValid && checkRegexp( state, /^([A-Z]{2})$/, "eg: NY" ); 
					if ( bValid ) {
						var restaurant = {	name: $("#rest_name").val(),
									address: $("#rest_address").val(),
									phone: $("#rest_phone").val(),
									//genre: $("#rest_genre").val(),
									city: $("#rest_city").val(),
									state: $("#rest_state").val(),
									zip: $("#rest_zip").val(),
									adminRating: $("#rest_rating").html(),
									lat: 0,
									lng: 0
								}; 
						
						if(restaurant.name == ""){
							alert("name cannot be empty");
							return; 
						} 
						//geocode to get lat, long
						codeAddressAndAdd(restaurant);
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		
		
	function updateRating(slide)
	{ 
		$(slide).siblings("#rest_rating").html($(slide).slider('value'));
	}
	
	$("#addRest-form #rate_bar").slider({
		orientation: "horizontal",
		range: "min",
		min: 0,
		max: 5,
		step: 1,
		slide: function(event, ui){
					var slide = this;
					setTimeout(function(){updateRating(slide)}, 10); 
					//$(this).siblings("#rating").html($(this).slider('value')); 
				}
	});
	
	var noOfCats = 3; 
	$("#cat_tbl").load("./admin/subcategories.php", {selectId: noOfCats});
});
</script>

<!-- FORM for creating new restaurant -->
<style>
		#addRest-form {text-align: left;}
		#addRest-form label, input { display:block; }
		#addRest-form input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<div id="addRest-form" title="Create new user">
<p class="validateTips">All form fields are required.</p>

<form>
	<fieldset>
		<label for="name">Place Name</label>
		<input type="text" name="name" id="rest_name" class="text ui-widget-content ui-corner-all" />
		<label for="address">Address</label>
		<input type="text" name="address" id="rest_address" class="text ui-widget-content ui-corner-all" />
		<label for="city">City</label>
		<input type="text" name="city" id="rest_city" class="text ui-widget-content ui-corner-all" />
		<label for="state">State (ex: NY)</label>
		<input type="text" name="state" id="rest_state" class="text ui-widget-content ui-corner-all" />
		<label for="zip">Zip</label>
		<input type="text" name="zip" id="rest_zip" class="text ui-widget-content ui-corner-all" />
		<label for="phone">Phone</label>
		<input type="text" name="phone" id="rest_phone" class="text ui-widget-content ui-corner-all" />
		
		<div style="margin-top: 10px; float: left;">Rating bar: </div> 
		<div style="margin: 10px 0px 0px 20px; float: left; width: 100px;" id="rate_bar"></div>
		<span style="display: block; float: left; margin: 10px 0px 0px 10px" id="rest_rating">0</span>
		<div id="clear"></div>
		
		<table id="cat_tbl" style="float: left;">
						
		</table>
		<div id="clear"></div>
	</fieldset>
</form>
</div>