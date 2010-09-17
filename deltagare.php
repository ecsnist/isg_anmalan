<?php 

//includes
require_once "../secure/common.php";

//This function prints initial html
html_begin("Tävlingsanmälan IS Göta");

$adm_flag = $_GET['adm_flag'];  //This is for adm (fee followup)

?>

<form action="./show_db.php?adm_flag=<?php echo $adm_flag; ?>" method=POST>

<br>

<H2>Sorteringsalternativ:</H2>
<table border="1">
<tr>
<td>Sortera på:</td> 
<td>
<select name=sort>
	<option>
	<option> Namn
	<option> Tävling

</select></td>
</tr>
</table>


<br>
<input type=submit name="Visa lista" value="Visa lista">
<br>

<?php 

//Prints trailing html
html_end();

?>
