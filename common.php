<?php

//This file contains function to setup the connection to the database.
//It is kept under the secure dir and permissions are set with .ht* files

require_once "set_defs.php";

//This function prints initial html
function html_begin($h1)
{
  print("<html>\n");
  print("<head>\n");
  print("<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n");
  print("<title>$h1</title>\n");
  print("</head>\n");
  print("<body $bg_white>\n");
  print("<font face='Arial'>\n"); 

  //Print header
  if($h1!="")
  {
    print("<H1>$h1</H1>");
  }   
}

//This function prints trailing html
function html_end()
{
  print("</body>\n");
  print("</html>\n");
}

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain, "A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}






//This function implements a stopwatch used for measuring execution time
class stopWatch {
  private $timestamp;

  function stopWatch() {
    $this->timestamp = 0.0;    //reset time - probably unnecessary
  }
   
  function start() {
    $this->timestamp = microtime(true);    //set start time
  }

  function read() {
    return microtime(true) - $this->timestamp;   //returns elapsed time
  }   

}


?>
