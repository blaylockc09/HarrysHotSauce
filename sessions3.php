<?php

// sessions logout
// starting the session
session_start(); echo '<B>Sessions Logout Page Before Logout</B><br /><br />';

//display user variable
echo 'User variable: '.$_SESSION['user'];

//display the session ID
echo '<br /> Session_id: '.session_id(); 
//test if user variable is set

if(isset($_SESSION['user'])) {
     echo '<br >Session is still active<br >';
}
else{
    echo '<br />Session is not active<br />';
}
//display session status 

echo 'Session Status Variable: ' .session_status(); 

//session commands to clear session variables and stop the session

session_unset();

session_destroy(); 

echo '<BR /><BR /><br /><B>Sessions Logout Page After Logout</B><br /><br />';

//display user variable
echo 'User: '.$_SESSION['user'];

//display the session ID
echo '<br /> Session_id: '.session_id(); 
//test if user variable is set 
if(isset ($_SESSION['user'])) {
    echo '<br />Session is still active <BR />';
}
else{
    echo '<br />Session is not active <BR />';
} 
    
//display session status

echo 'Session Status Variable: '.session_status();
echo' <BR /><BR />';

 // create and display a link for the main page.

echo '<a href="main.html">Home page</a> ';

?>