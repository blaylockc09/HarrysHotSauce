<?php

date_default_timezone_set('America/New_York'); 
error_reporting(E_ERROR | E_PARSE);

require 'includes/connection.php'; 
require 'includes/core.php';

if(loggedin()) {

$_SESSION['Login Username'] = getuserfield('Login_username');

$accountID = $_SESSION['Login_id'];

$dateTime = date("Y-m-d H:i:s"); 
$last_id = mysqli_insert_id($link);

//Select all from the login log make sure its order by the loginlog ID and limit only one

//set the loginlog ID to a variable 
$query = "SELECT * FROM `LoginLog` where `LoginLog_login_id` = '".$accountID."' ORDER BY `LoginLog_id` DESC LIMIT 1"; 
if (!$result = mysqli_query($link, $query))
{
$result = 0;

echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
}
else
{ 
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    $LoginLogID = $row['LoginLog_id']; 
    //Update the loginlog and set the logout date of the customer to the current date and time


    $query2 = "UPDATE `LoginLog` set `LoginLog_logout_date` = '".$datetime."' WHERE `LoginLog_login_id` = '".$accountID."' AND `LoginLog_id` = '".$LoginlogID."' ";

if (!$result = mysqli_query($link, $query2))
{
$result = 0;

echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
}

//Destroy the session and unset the session login ID even if the update failed

session_destroy();

if(isset ($_SESSION['Login_id'])) unset($_SESSION['Login_id']);

//Link takes you to customer login page 
echo "<script type='text/javascript'> document.location = 'login.php';
</script>";
}}
?>