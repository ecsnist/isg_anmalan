<?php 

//includes
require_once "../secure/common.php";

//This function prints initial html
html_begin("Tävlingsanmälan IS Göta");

//includes
require_once "../secure/db.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function dropdown($intIdField, $strNameField, $strTableName, $strOrderField, $strNameOrdinal, $strMethod="asc") {

   //
   // PHP DYNAMIC DROP-DOWN BOX - HTML SELECT
   //
   // 2006-05, 2008-09, 2009-04 http://kimbriggs.com/computers/
   //
   // Function creates a drop-down box
   // by dynamically querying ID-Name pair from a lookup table.
   //
   // Parameters:
   // intIdField = Integer "ID" field of table, usually the primary key.
   // strMethod = Sort as asc=ascending (default) or desc for descending.
   // strNameField = Name field that user picks as a value, ordered by this field.
   // strNameOrdinal = For multiple drop-downs to same table on same page (Ex: strNameField.$i).
   // strOrderField = Which field you want results sorted by.
   // strTableName = Name of MySQL table containing intID and strName.
   //
   // Returns:
   // HTML Drop-Down Box Mark-up Code.
   //

   echo "<select name=\"$strNameOrdinal\">\n";
   echo "<option value=\"NULL\">Välj</option>\n";

   $strQuery = "select $intIdField, $strNameField from $strTableName order by $strOrderField $strMethod";
   $rsrcResult = mysql_query($strQuery);

   while($arrayRow = mysql_fetch_assoc($rsrcResult)) {
      $strA = $arrayRow["$intIdField"];
      $strB = $arrayRow["$strNameField"];
      echo "<option value=\"$strA\">$strB</option>\n";
   }

   echo "</select>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$file=fopen("anmalan_form.txt","r"); 

while(!feof($file))
  {
  	$fg = fgets($file);
  	if($fg[0]!="/")
  		echo $fg;
  }

fclose($file);




	//Open database use secured function
  $connection = db_open();

  $query = 'SELECT DISTINCT Namn,ID FROM isg_anmalan_namn ORDER by Namn';
          
	$result = mysql_query($query) 
  or die('Fel i query: $query. ' . mysql_error());

    while($row = mysql_fetch_object($result))
  {
    echo "<td><font size=$fontsize>$row->Namn&nbsp;</td>";
  }

  dropdown(ID, Namn, isg_anmalan_namn, Namn, namn);


?>


<br>
<input type=submit name="Skicka uppgifter" value="Skicka uppgifter">
<br>

<?php 
//Prints trailing html
html_end();
?>
