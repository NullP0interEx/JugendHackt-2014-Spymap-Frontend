<?php
$db = mysqli_connect("localhost", "davidsql3", "123456", "davidsql3");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>SpyMap</title>
	<style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0;}
      #map-canvas { height: 100%; margin: 0; padding: 0;}
      .plus {
        text-indent: 1.1rem !important;
      }
      li, li p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
      }
      .nametag {
        padding-left: 10px;
        font-size: 14px;
        margin-top: 5px;
      }
      .legende {
        padding-left: 10px;
        margin-top: 10px;
        font-size: 12px;
      }
      .space {
        margin-top: 20px;
      }
      .off-canvas-wrap, .inner-wrap, .main-section {
        height: 100%;
      }
      
      #map-canvas img { max-width: none; }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhJ1I63kSMbX_YiP78BCHGRW7zqv27qnw&libraries=places">
    </script>
    <script type="text/javascript">
      var script = '<script type="text/javascript" src="js/markerclusterer';
      if (document.location.search.indexOf('compiled') !== -1) {
        script += '_compiled';
      }
      script += '.js"><' + '/script>';
      document.write(script);
    </script>
    <script type="text/javascript">
  
var map;
var markersArray = [];
var location_lat;
var location_long;
var markerCluster;

  function initialize() {
     
	location_lat = 51.165691;
    location_long = 10.451526000000058;

    var myOptions = {
      center: new google.maps.LatLng(location_lat, location_long),
      zoom: 6,
      mapTypeId: google.maps.MapTypeId.ROADMAP

    };
    
    map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);

	getJson();

  	navigator.geolocation.getCurrentPosition(function(position){ 
        location_lat = position.coords.latitude;
        location_long = position.coords.longitude;
        map.setCenter(new google.maps.LatLng(location_lat, location_long));
        map.setZoom(12);
        myicon = "images/standort.png";
          var marker = new google.maps.Marker({ map: map, title: "My Position" , position: new google.maps.LatLng(location_lat, location_long), icon: myicon, zindex: 999 });
     });

 // Create the search box and link it to the UI element.
  var input = (document.getElementById('search_place'));

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));

  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      var marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
  });

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });


  }
  
function getJson() { 

		if($('#checkbox_indoor').prop('checked')) {
			var state_indoor = "true";
		} else {
			var state_indoor = "false";
		}
		
		if($('#checkbox_outdoor').prop('checked')) {
			var state_outdoor = "true";
		} else {
			var state_outdoor = "false";
		}
		
		if($('#checkbox_traffic').prop('checked')) {
			var state_traffic = "true";
		} else {
			var state_traffic = "false";
		}
		
		if($('#checkbox_webcam').prop('checked')) {
			var state_webcam = "true";
		} else {
			var state_webcam = "false";
		}
		
		$.ajax({
 		 url: "ajax.php",
 		 data: {outdoor: state_outdoor, indoor: state_indoor, webcam: state_webcam, traffic: state_traffic},
 		 dataType: "json",
 		 success: function(data) {
 	 		setMarkers(map, data);
 		 }
		});
	}


  function setMarkers(a_map,locations){

var icon;
var marker;

for (var i = 0; i < locations.length; i++)
 {
 var loan = locations[i]['name']
 var lat = locations[i]['lat'][0]
 var long = locations[i]['long'][0]
 var add =  locations[i]['content']
 var type =  locations[i]['type']
 latlngset = new google.maps.LatLng(lat, long);

  if(type == "outdoor") {
  	icon = "images/outdoor.png";
  }
  if(type == "indoor") {
  	icon = "images/indoor.png";
  }
  if(type == "webcam") {
  	icon = "images/webcam.png";
  }
  if(type == "traffic") {
    icon = "images/traffic.png";
  }

  var marker = new google.maps.Marker({ title: loan , position: latlngset, icon: icon
        });
 markersArray.push(marker);

        var content = add;     

  var infowindow = new google.maps.InfoWindow()

google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {
           infowindow.setContent(content);
           infowindow.open(map,marker);
        };
    })(marker,content,infowindow)); 

  }
  markerCluster = new MarkerClusterer(map, markersArray);
  markerCluster.setMaxZoom(14);
  }
  
  google.maps.event.addDomListener(window, 'load', initialize);

	
function clearOverlays() {
  for (var i = 0; i < markersArray.length; i++ ) {
    markersArray[i].setMap(null);
  }
  markersArray.length = 0;
}
	
  function reloadLayers() {
  	clearOverlays();
    markerCluster.clearMarkers();
  	getJson();
  }
  
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<div class="off-canvas-wrap" data-offcanvas>
  <div class="inner-wrap">
    <nav class="tab-bar">
      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
      </section>

      <section class="middle tab-bar-section">
        <h1 class="title">SpyMap</h1>
      </section>

      <section class="right-small">
        <a class="menu-icon plus" href="add.php">+</a>
      </section>
    </nav>

    <aside class="left-off-canvas-menu">
      <ul class="off-canvas-list">

      <li><label>Suchen</label></li>
      <li class="legende"><p><input name="search" type="text" id="search_place" size="20" style="width: 230px;" maxlength="100"></p></li>

      <li><label>Darstellung</label></li>
      <li class="legende"><input type="checkbox" name="indoor" id="checkbox_indoor" value="true" onChange="reloadLayers();" checked="checked" /> <img src="images/indoor.png" size="16" width="16" /> Indoor<br>
      <input type="checkbox" name="outdoor" id="checkbox_outdoor" value="true" onChange="reloadLayers();" checked="checked" /> <img src="images/outdoor.png" size="16" width="16" /> Outdoor<br>
      <input type="checkbox" name="traffic" id="checkbox_traffic" value="true" onChange="reloadLayers();" checked="checked" /> <img src="images/traffic.png" size="16" width="16" /> Traffic<br>
      <input type="checkbox" name="outdoor" id="checkbox_webcam" value="true" onChange="reloadLayers();" checked="checked" /> <img src="images/webcam.png" size="16" width="16" /> Webcam<br></li>

      <li><label>Links</label></li>
      <li><a><b>Karte anzeigen</b></a></li>
      <li><a href="add.php">Kamera hinzufügen</a></li>
      <li><a href="impressum.php">Impressum</a></li>
      
      </ul>
    </aside>

    <section class="main-section">
      <!-- content goes here -->


  <div id="map-canvas"></div>


    </section>

  <a class="exit-off-canvas"></a>

  </div>
</div>

<script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
