<?php

ini_set("display_startup_errors", 1);
ini_set("display errors", 1);

/* Reports for either E ERROR | E WARNING | E NOTICE | Any Error*/

error_reporting(E_ALL);

echo('<b>You need to follow along with the source code to fully understand this example</b><br /><br />');

echo('The next statements are attempting to echo the value of an undefined constant (abc) and variable ($abc).<br />'); 

echo('You will get the following error messages displayed, because they are undefined. <br />');

echo('The value of the constant is an undefined constant /abc is '.abc.'<br />'); /* Notice: abc is an undefined variable */

echo('The value of the variable $abc is '.$abc); /* Notice: abc is an undefined variable */

echo('<br /><br />If you turn off the displaying of errors, then you simply do not get the error message. <br />'); 

ini_set('display_errors', 0);

echo('The value of the abc constant is '.abc.'<br />'); /* Notice: abc is an undefined constant */

echo('The value of the $abc variable is '.$abc); /* Notice: abc is an undefined variable */

$abc='test'; 

/* Notice: we are now defining the variable */ 

define ("abc", "Constant Value Assigned"); /* Notice: we are now defining the constant */



echo('<br /><br />We have now defined the variable $abc with the value "test" and the constant abc with the value "Constant Value Assigned". ');

echo('<br /><br />'); 
echo('The value of the variable $abc is <b>'.$abc.'</b>.'); /* Notice: abc is now a defined variable */ 
echo('<br />The value of the constant abc is <b>'.abc.'</b>.'); 
/*Notice: abc is now a defined variable */ 
echo('<br /><br />You now see the value of the variable and constant. ');

?>