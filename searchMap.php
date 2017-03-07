<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script type="text/javascript">
$(document).ready(function(){
	google.load("maps", "3",  {callback: initialize, other_params:"sensor=false"});

	var map; 
	var geocoder;
	var infowindow;
	var markers = []; 
	function markClientLocation(){
		// If ClientLocation was filled in by the loader, use that info instead
		if (google.loader.ClientLocation) {
			
			var latlng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
		
			// Create client marker icon
			var homeIcon = "./images/map/home2.png";
			var homeShadow = "./images/map/homeShadow.png";
			//Create client marker
			var marker = new google.maps.Marker({
							position: latlng,
							map: map,
							title: 'Your Location',
							icon: homeIcon
						});
			 
			map.setCenter(latlng, 12);
			map.setZoom(13);
			infowindow = new google.maps.InfoWindow({
					  content: 'Hello world'
					});
		}
		
	}

	function markAddress(lat, lon, name) {

		//create infowindow
		var contentString = "<div style='text-align: center;'>"+name+"</div>";  

		var latlng = new google.maps.LatLng(lat, lon);
		var restIcon = "./images/map/restaurant2.png";
		var marker = new google.maps.Marker({
						position: latlng,
						title: name,
						icon: restIcon,
						map: map
					});
		markers.push(marker);			
		google.maps.event.addListener(marker, 'click', function() {
			map.setCenter(marker.getPosition());
			map.setZoom(15);
			infowindow.setContent(contentString);
			infowindow.open(map,marker);
		});
		
  	}
	
	function initialize() {
		// Initialize default values
		var zoom = 3;
		var latlng = new google.maps.LatLng(37.4419, -100.1419);

		var myOptions = {
		  zoom: zoom,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("searchMap"), myOptions);
		//initialize map
		markClientLocation();
		
		//go through restaurants in the DB and make a marker for each restaurant
		<?php
			include('DB.php');
			//open database connection   RQST00072317220.
			$connection = mysql_connect($host, $user, $password) or die('could not connect');
			//select database
			mysql_select_db($dbName) or die('could not select database');
			//create query
			$query = "SELECT * from restaurants;";
			//execute query
			$result = mysql_query($query) or die('could not query database');

			while($row = mysql_fetch_assoc($result)){
				echo "markAddress(".$row['lat'].",".$row['lon'].",'".addslashes($row['name'])."');";
			}
			mysql_close($connection);
			mysql_free_result($result);
		?>
		
		google.maps.event.addListener(map, 'bounds_changed', function(){
			var mapBounds = map.getBounds();
			$('#searchBox').html('<br/> bound changed <br/>');
			for(var i=0; i<markers.length; i++)
			{
				if(mapBounds.contains(markers[i].getPosition()))
					$('#searchBox').append(markers[i].getTitle()+' and ');
			}
		});
	}
	
	 
 });      

</script>

<div id="searchMap" style="margin: 10px 0px 0px 10px; width: 570px; height: 405px; border: 1px solid orange;">
</div>