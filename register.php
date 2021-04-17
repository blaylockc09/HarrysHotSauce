<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Harry's Hot Sauce</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="css/layout.css" rel="stylesheet" type="text/css">
</head>

<body>

    <?php include 'includes/connection.php'; ?>
    <?php include 'includes/core.php'; ?> 
    <div id="wrapper">

        <?php include 'includes/header.php'; ?>

        <div id="body-wrapper">

            <p class="titles">Registration</p>

            <div id="register-wrapper">

                <form action="" method="post">

                    <div class="label-wrapper">

                        <p>First Name:</p>

                        <input name="firstName" type="text" placeholder="First Name:" size="40" />

                    </div>

                    <div class="label-wrapper"> Last Name:

                        <input type="text" placeholder="Last Name:" name="lastName" size="40" /> </div>

                    <div class="label-wrapper">

                        <p>Email Address:</p>

                        <input type="text" placeholder="Email Address:" name="emailAddress" size="40" />

                    </div>



                    <div class="label-wrapper">

                        <p>Address:</p>

                        <input type="text" placeholder="Address:" name="address" size="40" />

                    </div>

                    <div class="label-wrapper">

                    <p>City:</p>

                        <input type="text" placeholder="City:" name="city" size="40" />

                    </div>

                    <div class="label-wrapper">

                    <p> State:</p>

                        <input type="text" placeholder="State:" name="state" size="40" />

                    </div>

                    <div class="label-wrapper">

                    <p>Zip Code:</p>

                        <input type="text" placeholder="zip Code:" name="zipCode" size="40" />

                    </div>

                    <div class="label-wrapper">

                    <p> Username:</p>

                        <input type="text" placeholder="Username:" name="userName" size="40" />

                    </div>

                    <div class="label-wrapper">

                    <p>Password:</p>

                        <input type="password" placeholder="Password:" name="password" size="40" />

                    </div>

                    <input type="submit" name="submitRegistration" value="Register" class="submit-reset">

                    <input type="reset" name="reset" value="Reset" class="submit-reset">

                </form>
            
<?php

// All html form names turned in to php variables 

$dateTime = date("Y-m-d H:i:s");

$lastName = $_POST['lastName'];

$firstName = $_POST[ 'firstName'];

$emailAddress = $_POST['emailAddress']; 

$address = $_POST['address'];

$city = $_POST['city'];

$state = $_POST['state']; 

$zipCode = $_POST['zipCode'];
$userName = $_POST['userName'];
$password = $_POST['password']; 
$passwordHash = md5($password);

if(isset($_POST['submitRegistration'])){
    //Check to see if the submit registration button was clicked 
    // If it was insert customer info into accounts table 

    $query = "INSERT INTO `Accounts` (`Account_lastname`, `Account_firstname`, `Account_email_address`, `Account_address1`, `Account_city`, `Account_state_region`, `Account_postal_code` , `Account_status` ,
    `Account_created_date`, `Account_created_by`, `Account_last_update_date`, `Account_last_update_by`, `Account_AT_id`) 
    VALUES ('$lastName' ,'$firstName', '$emailAddress', '$address', '$city', '$state', '$zipCode', 'active', '$dateTime',
    'Registration Form', '$dateTime', 'Registration Form', '1')"; 

    if (!$query_run = mysqli_query($link, $query)){
        $query_run = 0;
        echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>"); 
    }
    else{
        // Select the customer account ID and put it to a variable 
        $query4 = "SELECT `Account_id` FROM `Accounts` WHERE `Account_email_address` = '$emailAddress'"; 

        if (!$result4 = mysqli_query($link, $query4)){
            $result4 = 0;

            echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
        }
            $row4 = mysqli_fetch_array($result4, MYSQLI_BOTH); 
            $accountID = $row4['Account_id'];

            // Insert customer user name and password into the login table 

            $query2 = "INSERT INTO `Login` (`Login_username`, `Login_password`, `Login_status`, `Login_created_date`, `Login_created_by`, `Login_account_id`, `Login_last_update_date`, `Login_last_updated_by`) 
            VALUES ('$userName', '$passwordHash', 'active', '$dateTime', 'Registration Form', '$accountID', '$dateTime', 'Registration Form')"; 
            
            if (!$query_run2 = mysqli_query($link, $query2))
            {
                $query_run2 = 0;
                echo ('Error executing query: ' . mysqli_errno($link)." - ". mysqli_error($link)."<BR>");
            }


            // Link takes you to customer login page 

            echo "<script type='text/javascript'> document.location = 'login.php';
            </script>";
        }
}
?>
            </div>


        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- signing.php is created in chapter 6 -->

    <?php include 'includes/signIn.php'; ?>
</body>
</html>