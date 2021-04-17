<?php
    include 'includes/connection.php';
    include 'includes/core.php';
    date_default_timezone_set('America/New_York'); 
    error_reporting(E_ERROR | E_PARSE);

    if(loggedin()) {

    $_SESSION['Login_username'] = getuserfield('Login_username');
    $LoginID = $_SESSION['Login_id'];

    $query = "SELECT * FROM `Accounts`, Login WHERE `Account_id` = Login_account_id and Login_id = '".$_SESSION['Login_id']."'";

    if (!$result = mysqli_query($link, $query)) {
        $result = 0;
        echo ('Error executing query1 in receipts:' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
    }
    else {
        $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    }

    //Putting account table columns in variables
    $firstName = $row['Account_firstname'];
    $lastName = $row['Account_lastname']; 
    $email = $row['Account_email_address'];
    $address = $row[ 'Account_address1']; 
    $city = $row['Account_city'];
    $state = $row['Account_state_region']; 
    $zip = $row['Account_postal_code'];
    $accountID = $row['Account_id' ];
?>

<!DOCTYPE html>

<html> 
<head>
<meta charset="utf-8">
<title>Harry's Hot Sauce</title> <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<link href="css/layout.css" rel="stylesheet" type="text/css"> </head>
<body>
<div id="wrapper">
<?php include 'includes/header.php'; ?>
<div id="body-wrapper">
<h1 class="titles">THANK YOU FOR YOUR ORDER</h1>
<?php
//order number from session
$order_Number = $_SESSION['OrderNumber'];
$ItemCtr = 1;
//Select all from the order headers limit by one record in descending order
$query2 = "SELECT * FROM Order_headers WHERE Order_header_number = $order_Number";

if (!$query_run2 = mysqli_query($link, $query2)){

$result = 0;

echo ('Error executing query2 in Receipts: '. mysqli_errno($link)."-". mysqli_error($link)."<BR>");

} else

{
    $row2 = mysqli_fetch_array($query_run2, MYSQLI_BOTH);
}
//variable creation of column names

$orderHeaderID = $row2[ 'Order_header_id']; 
$orderDate = $row2['Order_header_order_date'];

//total starts at e $total = e;

print '<br />';

print '<br/>';

print '<p class="product-name">Order Number: '. $order_Number . '</p>';

print '<br />';

print '<br />';

print '<br />';

print '<p>' .$firstName.' '.$lastName.'<br />'.$address.'<br />'.$city.', '.$state.' '.$zip.'</p><br />';

print '<p>Email: '.$email.'</p><br />Account Number: '.$accountID.'</p><br/>'; 
print '<br />';
print '<br />';
//Select al1 of the order details 
$query3 = "SELECT * FROM `Order_details`, `Products`, `Inventory` WHERE `Order_details_inventory_id` = `Inventory_id` 
and `Inventory_product_id` = `Products_id` AND `Order_details_header_id` = $orderHeaderID";

if (!$query_run3 = mysqli_query($link, $query3))
{
$result = 0;

echo ('Error executing query3 in receipts:' .mysqli_errno($link)." - " .mysqli_error($link)."<BR>");
}
else

{

while($row3 = mysqli_fetch_array($query_run3,MYSQLI_BOTH))
{
//loop through all the order details and display nicely 

print '<div style="width:200px; height: auto; float:left; font-weight:bold; font-size:18px;">'. $ItemCtr.'. '. $row3['Products_name'] . '</div>'; 
print '<div style="width:200px; height: auto; float: left; font-weight:bold; font-size:18px;">Quantity' .$row3['Order_details_ordered_quantity'] .'</div>';

print '<div style="width:200px; height: auto; float:left; font-weight:bold; font-size:18px;">$'. number_format($subtotal = $row3['Order_details_ordered_quantity'] * $row3[ 'Products_unit_price'],2) .'</div>';

print '<br />';

print '<hr>';

print '<br />'; 

$ItemCtr++;

//subtotals totaled into a variable

$total += $subtotal;
}
}

//shipping variable

$shipping = 17.90;

print '<div style="width:200px; height:auto; float:left; font-weight:bold; font-size:18px;">Shipping Cost $'. number_format ($shipping, 2). '</div>';

print '<br />'; 
print '<br />';

print '<div style="width:200px; height: auto; float:left; font-weight:bold; font-size:18px;">Total $' .number_format($grandtotal = $total + $shipping, 2) . '</div>';

print '<br />';

print '<br />';

print '<hr>';

print '<br />'; 
print '<br />';

print '<p style="font-weight:bold; font-size:18px;">Your order is currently

being processed. We will email you with a tracking number once your order

has shipped.</p>';

print '<br />';

print '<br />';

print '<form>

<input type="button" value="Print this page" onclick="window.print()"

class="removeItem">

</form>'; 
?>

</div>

<?php include 'includes/footer.php'; ?>

</div>

<?php include 'includes/signout.php'; ?>

</body> </html>

<?php

} else {

    header('Location: login. php');

}

?>

