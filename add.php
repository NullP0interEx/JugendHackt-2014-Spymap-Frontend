<?php $db = mysqli_connect("localhost", "davidsql3", "123456", "davidsql3"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <style>
      .off-canvas-wrap, .inner-wrap, .main-section {
        height: 100%;
      }
      .formholder {
      	padding: 10px;
      }
    </style>
	<title>Kamera hinzufügen - SpyMap</title>
	    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhJ1I63kSMbX_YiP78BCHGRW7zqv27qnw">
    </script>
        <script type="text/javascript">
geocoder = new google.maps.Geocoder();

  function codeAddress() {
    //In this case it gets the address from an element on the page, but obviously you  could just pass it to the method instead
    var address = document.getElementById("place").value;

    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
        address = results[0]['geometry']['location']['B'] + ", " + results[0]['geometry']['location']['k'];
        document.getElementById("place").value = address;
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
    </script>
  </head>
  <body>


<div class="off-canvas-wrap" data-offcanvas>
  <div class="inner-wrap">
    <nav class="tab-bar">
      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
      </section>

      <section class="middle tab-bar-section">
        <h1 class="title">Kamera Hinzufügen</h1>
      </section>
    </nav>

    <aside class="left-off-canvas-menu">
      <ul class="off-canvas-list">

      <li><label>Links</label></li>
      <li><a href="index.php">Karte anzeigen</a></li>
      <li><a><b>Kamera hinzufügen</b></a></li>
      <li><a href="impressum.php">Impressum</a></li>


      </ul>
    </aside>

    <section class="main-section">
 
<?php

if(!empty($_POST['place'])) {
$coords = explode(", ", $_POST['place'], 45678);
$add = mysqli_query($db, "INSERT INTO cams (lat, lon, name, operator, `desc`, `type`) VALUES ('$coords[0]', '$coords[1]', '".$_POST['name']."', '".$_POST['operator']."', '".$_POST['desc']."', '".$_POST['type']."')");
echo "<p>Erfolgreich eingefügt!</p>";
}

?>

 	<div class="formholder">
    	<form action="add.php" method="POST">
    		<p>Name*:<br/><input name="name" type="text" size="30" maxlength="30"></p>
    		<p>Standort*:<br/><input name="place" id="place" type="text" size="30" maxlength="60"><a class="button" onClick="codeAddress();">Suchen</a></p>
    		<p>Betreiber:<br/><input name="operator" type="text" size="30" maxlength="30"></p>
    		<p>Beschreibung<br/><textarea name="desc"></textarea></p>

        <p>Typ<br />
          <select name="type" size="1">
            <option>outdoor</option>
            <option>indoor</option>
            <option>traffic</option>
            <option>webcam</option>
          </select>
         </p>

    		<button type="submit" value="Hinzufügen" name="send">Hinzufügen</button>

    	</form>
    </div>
    </section>



  </div>
</div>


<script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
 </html>