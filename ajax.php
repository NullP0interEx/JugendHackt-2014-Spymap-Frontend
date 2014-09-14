
[<?php
$db = mysqli_connect("localhost", "davidsql3", "123456", "davidsql3");

$add = "";

if($_GET['outdoor'] == 'true') {
	$add .= "type = 'outdoor'";
}

if($_GET['indoor'] == 'true') {
	if(!empty($add)) {
		$add .= " OR ";
	} 
	$add .= "type = 'indoor'";
}

if($_GET['webcam'] == 'true') {
	if(!empty($add)) {
		$add .= " OR ";
	}
	$add .= "type = 'webcam'";
}

if($_GET['traffic'] == 'true') {
	if(!empty($add)) {
		$add .= " OR ";
	}
	$add .= "type = 'traffic'";
}



$used = false;
$query = mysqli_query($db, "SELECT * FROM cams WHERE ".$add);
while($row = mysqli_fetch_object($query)) {
  	if($used) {
  		echo ",";
  	}

  	if($row->name == "#jugendhackt") {
  		$desc = $row->desc;
  	} else {
  		$desc = htmlentities($row->desc);
  	}

  	if(!empty($desc) AND !empty($row->operator)) {
  		$add_to = '<p><b>Beschreibung: </b>'.$desc.'<br /><b>Operator: </b> '.$row->operator.'</p>';
  	} elseif(!empty($desc)) {
  		$add_to = '<p><b>Beschreibung: </b>'.$desc.'</p>';
  	} elseif(!empty($row->operator)) {
  		$add_to = '<p><b>Operator: </b> '.$row->operator.'</p>';
  	}

  	echo '{"name": "'.htmlentities($row->name).'","lat":['.htmlentities($row->lat).'],"long":['.htmlentities($row->lon).'],"content":"<h5>'.htmlentities($row->name).'</h5>'.$add_to.'", "type": "'.$row->type.'", "operator": "'.$row->operator.'"}';
  	$used = true;
	}
?>
]