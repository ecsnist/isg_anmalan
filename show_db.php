<?php 

//session_start(); 

//includes
require_once "../secure/common.php";
require_once "../secure/set_defs.php";
require_once "../secure/db.php";

//defines
set_defines();      //Set common defines 

//This function prints initial html
html_begin("Tävlingsanmälan IS Göta");

function print_table_header($fontsize, $debug, $adm)
{
      echo '<tr>';
      echo "<td><b><font size=$fontsize>Namn&nbsp;</b></td>";
      echo "<td><b><font size=$fontsize>F-år&nbsp;</b></td>";
      echo "<td><b><font size=$fontsize>Tävling&nbsp;</b></td>";
      echo "<td><b><font size=$fontsize>Klass&nbsp;</b></td>";
      echo "<td><b><font size=$fontsize>RegTid&nbsp;</b></td>";
      echo '</tr>';
}

$args = $GLOBALS["HTTP_POST_VARS"];

// Project
$sort = $args["sort"];  //This is alternative from search formular

//Set session variable to be able to retrieve sort info after 
if ($sort)
{
  $_SESSION['sort'] = $sort;  //store session data
}
else
{
  $sort = $_SESSION['sort'];  //retrieve data
}
	
//Open database use secured function
$connection = db_open();

//Välj sorteringsalternativ
switch($sort)
{
  case 'Namn':
  	$query = 'SELECT * FROM isg_anmalan ORDER by Namn, Race, Heat';
    break;
  case 'Tävling':
  	$query = 'SELECT * FROM isg_anmalan ORDER by Race, Heat, Namn';
    break;
  default:
    $query = 'SELECT * FROM isg_anmalan ORDER by RegTid';  //default sorting
    break;            
}
  
$result = mysql_query($query)
or die('Fel i query: $query. ' . mysql_error());

//Koll om element finns i databasen
$participants=mysql_num_rows($result);
if ($participants > 0)
{
  echo '<div align="left">';
	echo '<table border="0" width="100%" style="border-collapse: collapse" id="table1">';
  $fontsize='2';
      	
  print_table_header($fontsize,$debug_print,$adm_flag); 

  while($row = mysql_fetch_object($result))
  {
    echo '<tr>';

    echo "<td><font size=$fontsize>$row->Namn&nbsp;</td>";
    echo "<td><font size=$fontsize>$row->Birth&nbsp;</td>";
    echo "<td><font size=$fontsize>$row->Race&nbsp;</td>";
    echo "<td><font size=$fontsize>$row->Heat&nbsp;</td>";
    echo "<td><font size=$fontsize>$row->RegTid&nbsp;</td>";

    echo '</tr>';
  }
  echo '</table>';
  echo '<br>';
}
else
{
  //Error
  echo 'Inga rader funna!<br>';
}

//free result
mysql_free_result($result);

//close connection
mysql_close($connection);

echo $participants." deltagare elektroniskt anmälda<br>";

if ($sort!='')
{
  echo "Sorterat på ".$sort."<br>";
}

echo '<p><a href="./generate_xls.php">Exportera till Excel</a></p>';

echo '<p><a href="./anmalan.php">Anmäl deltagare</a></p>';

// Åter huvudsida
echo '<p><a href="./isg_reg.html">Tillbaks till förstasida</a></p>';
  	
//Prints trailing html
html_end();

?>
