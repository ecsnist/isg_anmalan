<?php 

//includes
require_once "../secure/common.php";

//This function prints initial html
html_begin("Tävlingsanmälan IS Göta");


//includes
require_once "../secure/db.php";
require_once "../secure/set_defs.php";

//defines
set_defines();      //Set common defines


if ($debug_logtime) {
  //start measurement of anmälan processing
  $reg_clock = new stopWatch();
  $reg_clock->start();
}
 
$ok=true;         //Formular default OK
$out_text = "";   //Reset text that will be presented and sent in mail

//Get arguments
$args = $GLOBALS["HTTP_POST_VARS"];

//This part validates the user input
//it will highligt in red if something is missing or not correct.
//If there is errors the user needs to go back and correct and submit again.

// Förnamn
$namn = $args["namn"];
if($namn!="")
{
  $namn_l = "Namn: \n".$namn;
	echo $namn_l."<br>";
	$out_text .= $namn_l."\n\n";
}
else
{
	$ok = false;
	echo $red."Namn ej angivet.<br>".$black;
}

// Födelseår
$birth = $args["birth"];
if($birth!="")
{
  $birth_l = "Födelseår: \n".$birth;
	echo $birth_l."<br>";
	$out_text .= $birth_l."\n\n";
}
else
{
	$ok = false;
	echo $red."Födelseår ej angivet.<br>".$black;
}

// Race
$race = $args["race"];
if($race!="")
{
  $race_l = "Tävling: \n".$race;
	echo $race_l."<br>";
	$out_text .= $race_l."\n\n";
}
else
{
	$ok = false;
	echo $red."Tävling ej angiven.<br>".$black;
}

// Heat
$heat = $args["heat"];
if($heat!="")
{
  $heat_l = "Tävlingsklass: \n".$heat;
	echo $heat_l."<br>";
	$out_text .= $heat_l."\n\n";
}
else
{
	$ok = false;
	echo $red."Tävlingsklass ej angiven.<br>".$black;
}

// Mailadress
$mailadress = $args["mailadress"];
if(validEmail($mailadress))
{
  $mailadress_l = "Mailadress: \n".$mailadress;
	echo $mailadress_l."<br>";
	$out_text .= $mailadress_l."\n\n";
}
else
{
	$ok = false;
	echo $red."Mailadress ej korrekt angiven.<br>".$black;
}

//Add date to outtext (set in set_def function)
$out_text .= $date_l."\n\n";

//Add ipadress to outtext (set in set_def function)
$out_text .= "IP: ".$ip."\n\n";

if($ok)   //Formuläret OK ifyllt
{

  if ($debug_logtime) {
    //start measurement of mySQL processing
    $mysql_clock = new stopWatch;
    $mysql_clock->start();
  }

	echo "<br><font color='#33CC33' size='4'><b>Vi har mottagit Din anmälan. </b>";
	echo "<font color='#000000' size='3'>";
  echo "<br>".$date_l."<br>";
  echo "IP Address= $ip<br>"; 

	echo "<font color='#000000' size='4'>";
	echo "<font color='#000000' size='3'>";

  if ($debug_logtime) {
    //start measurement of mail processing
    $mail_clock = new stopWatch;
    $mail_clock->start();
  }

	//Send mail with info
  mail('niklas@spaps.se', 'ISG Anmälan: '.$namn." ".$race, $heat."\n".$out_text);
	
  if ($debug_logtime) {
    //print execution of mail process_anmalan
    $mail_time = intval(1000*$mysql_clock->read())/1000;
  }

	//Open database use secured function
  $connection = db_open();

  //Insert in database
	$query = "INSERT INTO isg_anmalan ( Namn,
                                      Mailadress,
                                      Birth,
                                      Race,
                                      Heat,
                                      IP)
                                        
                          VALUES( '$namn',
                                  '$mailadress',
                                  '$birth',
                                  '$race',
                                  '$heat',
                                  '$ip')";
          
	$result = mysql_query($query) 
  or die('Fel i query: $query. ' . mysql_error());

  // Fler anmälningar
  echo '<br><b>"Backa" för att göra fler anmälningar (data bibehålls)</b>';

  // Presentera länk till Deltagarlistan
  echo '<p><a href="./show_db.php">Visa Deltagarlista</a></p>';

  // Åter huvudsida
  echo '<p><a href="./isg_reg.html">Tillbaks till förstasida</a></p>';

	//close database connection
	mysql_close($connection);

  if ($debug_logtime) {
    //print execution of mySQL processing
    $mysql_time = intval(1000*($mysql_clock->read() - $mail_time))/1000;

    //print execution of process_anmalan
    $reg_time = intval(1000*$reg_clock->read())/1000;
//    print "Total tid för att processa anmälan: ".$reg_time." sek<br>";
//    print "Mailtiden : ".$mail_time." sek<br>";
//    print "mySQL tiden : ".$mysql_time." sek<br>";

    //Insert into database
    //Open database use secured function
    $store_exec_info = true;
    if($store_exec_info) {
      $connection = db_open();

      //Insert in database
      $query = "INSERT INTO execlist (IP,
                                      RegTime,
                                      SqlTime,
                                      MailTime)
                              VALUES('$ip',
                                     '$reg_time',
                                     '$mysql_time',
                                     '$mail_time')";
      $result = mysql_query($query)
      or die('Fel i query: $query. ' . mysql_error());

      //close database connection
      mysql_close($connection);
    }
  }  
}
else   //Formuläret inte OK ifyllt
{
	print $red."<br><b>Ett eller fler obligatoriska fält är inte ifyllda.</b> <br><br>".$black;
  print "<input type='button' value='Klicka här' onClick='history.go(-1)'>"." för att komplementera saknad information.\n";

}

//Prints trailing html
html_end();


?>
