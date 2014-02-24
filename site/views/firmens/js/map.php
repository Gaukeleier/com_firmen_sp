<?php  

// DB Info

require_once '/kunden/klaeranlagen-vergleich.de/solaranlagen-portal.de/php_inc/db_config_db14.php';


// Get parameters from URL

$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];
$map_cat = $_GET["cat"];
$sort = $_GET["sort"];

// mode 1 = nach Distanz, 2 Nach Datum

if ($sort == 1) {$sort = "distance";} else {$sort = "RAND()";}


// Start XML file, create parent node

$dom = new DOMDocument("1.0","utf-8");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);


// Opens a connection to a mySQL server

$connection=mysql_connect ($host , $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}
mysql_set_charset('utf8',$connection);

// Set the active mySQL database

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}


// Search the rows in the markers table

$query = sprintf("
	SELECT DISTINCT  a.AdresseID AS id, a.id AS kundennummer, a.firma, a.tel, a.fax, a.email, a.mobil, a.homepage, a.strasse, a.breite, a.laenge,  a.nr,  a.plz, a.ort, ROUND(( 6371 * acos( cos( radians('%s') ) * cos( radians( a.breite ) ) * cos( radians( a.laenge ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( a.breite ) ) ) ) , 1) 	AS distance 
	FROM firmenvz_sp AS a 

	
	WHERE a.filter_systemart LIKE '$map_cat'
	HAVING distance < '%s' 
	ORDER BY $sort 
	LIMIT 0 , 30",
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($center_lng),
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($radius)
	);



$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

//header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
// !!! AUF UTF8-CODIERUNG ACHTEN ... sonst wird die xml nicht ausgegeben!
require_once '/kunden/klaeranlagen-vergleich.de/solaranlagen-portal.de/php_inc/db_config_db35.php';

// Opens a connection to a mySQL server

$connection=mysql_connect ($host , $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}
mysql_set_charset('utf8',$connection);

// Set the active mySQL database

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

while ($row = @mysql_fetch_assoc($result)){
//	$userid = $row['id'];
	$alias = mysql_query("SELECT oldurl 
	FROM josx_3_sh404sef_urls WHERE newurl LIKE '%Itemid=1185&id=" . $row['kundennummer'] ."%'");
	$row['alias'] = mysql_fetch_row($alias);
 	$json[] = $row;

}


echo json_encode( $json );
//var_dump($json);


?>