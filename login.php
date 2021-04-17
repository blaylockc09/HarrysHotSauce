<?php include 'includes/core.php'; ?> 

<!DOCTYPE html>

<html> 

<head>

<meta charset="utf-8">

<title>Harry's Hot Sauce</title>

<meta http-equiv="X-UA-Compatible content="IE=edge, chrome-1"> 
<link href="css/layout.css" rel="stylesheet" type="text/css">

</head>

<body>

<?php include 'includes/connection.php'; ?>

<div id="wrapper">

<?php include 'includes/header.php'; ?>

<div id="body-wrapper">

<p class="titles">Sign In</p> <div id="register-wrapper">

<form action="" method="post" >
    
<div class="label-wrapper"> 

<p>Username:</p>

<input name="userName" type="text" placeholder="Username:" size="40"/>

</div>

<div class="label-wrapper">

<p>Password:</p>

<input type="password" placeholder="Password:" name="password"

size="40"/> </div>

<?php print '<p class="error">' ; ?>

<?php print $inUnPw; ?> 
<?php print '</p>'; ?>

<input type="submit" name="submitLogin" value="Sign In" class="submit-reset">

<input type="reset" name="reset" value="Reset" class="submit-reset">

</form>

<?php


if(isset($_POST['submitLogin']))
{
// HTML form names set into php variables

$dateTime = date("Y-m-d H:i:s");

$userName = $_POST['userName'];

$password = $_POST['password']; 
$passwordHash = md5 ($password);

// Check to see if the submit login button was clicked

// if it was clicked select login for customer from database 
$query = "SELECT `Login_id`, `Login_account_id` FROM `Login`
WHERE `Login_username` = '$userName' AND `Login_password` = '$passwordHash'"; 

if (!$query_run = mysqli_query($link, $query))
{
$query_run = 0;
echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR> ");
}
else
{
    $query_num_rows = mysqli_num_rows($query_run);

if($query_num_rows == 0) 
{ // If the login does not match to customer insert into loginlog

// insert error entry into loginlog 

$query2 = "INSERT INTO `LoginLog` (`LoginLog_login_username`, `LoginLog_login_password` , `Loginlog_login_id` , `Loginlog_login_date`) 
VALUES ('$userName', '$passwordHash', '$ID', '$dateTime')"; 
if (!$query_run2 = mysqli_query($link, $query2))
{
$query_run2 = 0;

echo ('Error executing query:' . mysqli_errno($link)." - ". mysqli_error($link)."<BR>");
}
// run error

$inUnPw = "Username or password is incorrect";

echo $inUnPw;
}
else if($query_num_rows == 1)
{
// if log in credentials match get login ID and make it to a variable ID 
if ($query_result = mysqli_fetch_assoc($query_run));
{
    $Login_id = $query_result[Login_id];
}

$_SESSION['Login_id'] = $Login_id; 
$ID = $_SESSION['Login Id'];
$Login_account_id = $query_result[Login_account_id];
$Login_username = $query_result[Login_username];
$_SESSION['Login_account_id'] = $Login_account_id;
$_SESSION['Login_username'] = $Login_username;

// Insert into login log all correct credentials for customer

$query3 = "INSERT INTO `LoginLog` (`LoginLog_login_username`, `LoginLog_login_password`, `Loginlog_login_id`, `Loginlog_login_date`) 
VALUES ('$userName', '$passwordHash', '$ID', '$dateTime')";

if (!$query_run3 = mysqli_query($link, $query3))
{
$query_run3 = 0;
echo ('Error executing query3: ' . mysqli_error($link). " - " . mysqli_errno($link)."<BR>");
}

// update the login to set the last login date to todays date and time 

$query4 = "UPDATE `Login` SET `Login_last_login_date` = '".$dateTime."', `Login_last_update_date` = '".$dateTime."', `Login_last_updated_by` = 'Login'
WHERE `Login_id` = '".$ID."'";

if (!$result4 = mysqli_query($link, $query4))
{
$result4 = 0;

echo ('Error executing query4: ' . mysqli_errno($link). " - ".mysqli_error($link)."<BR>");
}
// Link takes you to customer account page 

echo "<script type='text/javascript'> document.location = 'account.php'; </script>";
}
else{

// run error

$inUnPw = "Error Returning Username or Password, please Contact Customer Service";

echo $inUnPw;
}
}
}
?>

</div>

</div>

<?php include 'includes/footer.php'; ?> 

</div>



<?php include 'includes/signIn.php'; ?>

</body> </html>