<?php

include 'includes/core.php'; 
include 'includes/connection.php';
date_default_timezone_set('America/New_York'); 
error_reporting(E_ERROR | E_PARSE);

if(loggedin()) {

$_SESSION['Login_username'] = getuserfield('Login_username'); 
$LoginID = $_SESSION['Login_id'];

$query = "SELECT * FROM `Accounts`, Login WHERE `Account_id` =  Login_account_id and Login_Id = '".$_SESSION['Login_id']."'";

if (!$result = mysqli_query($link, $query)) 
{
    $result = 0;
    echo ('Error executing query1: ' . mysqli_errno ($link)." - ". mysqli_error($link)."<BR>");
} 
else{

$row = mysqli_fetch_array($result, MYSQLI_BOTH); 
// Putting account table columns in variables 
$firstName = $row['Account_firstname'];

$accountID - $row['Account_id'];
}
?>

<!DOCTYPE html>

<html> <head>

<meta charset="utf-8"> 
<title>Harry's Hot Sauce</title>

<meta http-equiv="X-UA-Compatible" content="IE-edge, chrome-1"> 
<link href="css/layout.css" rel="stylesheet" type="text/css">

</head>

<body>

<div id="wrapper">

<?php include 'includes/header.php'; ?>

<div id="body-wrapper">

<?php

// select all of the products images and inventory for displaying 
$query = "SELECT * FROM Products, Images, Inventory WHERE `Products_image_id` = `Images_id` AND `Inventory_product_id` = `Products_id`";

if (!$result = mysqli_query($link, $query))
{
    $result = 0;

    echo ('Error executing query2: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
}
else
{
// Loop through all of the products images and inventory with a hidden variable of inventory ID

while($row = mysqli_fetch_array ($result, MYSQLI_BOTH))
{
    print '<form method="post" action="">';
        print '<input type="hidden" value="'.$row['Inventory_id'].'" name="id"/>';
        print '<div class="item-wrapper">';
            print '<div class="product-name">'; 
            print $row['Products_display_name'];
            print '</div>'; //end if product-name
            print '<div class="product-image">';
            print '<img src="'.$row['Images_url'].'"/>';
            print '</div>'; //end of product image
            print '<div class="product-description">';
            print $row['Products_description']; 
            print '</div>'; //end of product-description
            print '<div class="inventory">';
            // Select qty from the shopping cart
            $inv_id = $row['Inventory_id']; 
            $query16a = "SELECT SUM(SC_order_quantity) FROM Shopping_cart WHERE SC_inventory_id = $inv_id";

            if (!$result1 = mysqli_query($link, $query16a))
            {

                $result1 =0;

            echo ('Error executing query3: ' . mysqli_errno($link)." - ".mysqli_error($link). "<BR>");
            }
            else{
                $row16a = mysqli_fetch_array($result1, MYSQLI_BOTH); 
                $sCQtya = $row16a['SUM(SC_order_quantity)'];
                $availqty = $row['Inventory_units_in_stock'] - $sCQtya;

                if($availqty > 0)
                {
                // If units in stock are greater than display the avalable amount
                print 'Units Available - '.$availqty;
                }
                else{
                    // if they are less than a display
                    print 'Out of Stock';
                }
                print '</div>'; //end of inventory
                print '<div class="price">';
                print '$'.number_format($row['Products_unit_price'],2);
                print '<input class="quantity" type="text" name="quantity1" placeholder="0"/>'; 
                print '</div>'; //end of price

                if($availqty <= 0){
                    // if the units in stock are less than or equal to e display unaviable 
                    print '<span class="unavailable">Unavailable</span>';
                }
                else{
                    // If they are greater than 0 show button
                    print '<input type="submit" value="Add To Cart" name="submitItem"
                    class="test"/>';
                }
            } // end of if else (1$result = mysqli_query($link, $query16a))
        print '</div>'; //end of item-wrapper print '</form>';
    print '</form>';
} //end of while loop

mysqli_free_result($result); // clears result set.

mysqli_free_result($result1); // clears resulti set.
} //end of else of if ($result = mysqli_query($link, $query))

print $oDID;

$quantity = 0;

if(isset($_POST['submitItem']) and $_POST['quantity1'] > 0)
{
    // Check if the submit item button was clicked 
    $id=intval($_POST['id']); 
    $dateTime = date( "Y-m-d H:i:s");
    $headerNumber = mt_rand(000, 900);
    $quantity = $_POST['quantity1'];

    $a = 1; 
    // select the product unit price
    $query5 = "SELECT `Products_unit_price` FROM `Products`, `Inventory` WHERE `Inventory_Id` = $id and `Inventory_product_id` = Products_id"; 

    if (!$result = mysqli_query($link, $query5))
    {
    $result =0;
    echo ('Error executing query: '. mysqli_errno($link)."-" .mysqli_error($link). "<BR>");
    }
    else
    {
    $row5 = mysqli_fetch_array($result, MYSQLI_BOTH);
    // Product unit price turned into a variable 
    $unitPrice = $row5['Products_unit_price'];
    }
    // Select all the information from the shopping cart 
    $query16 = "SELECT * FROM `Shopping_cart` WHERE `SC_inventory_id` = $id and `SC_account_id` = $accountID"; 

    if ($result = mysqli_query($link, $query16))
    {
    $result = 0;

    echo ('Error executing query4: ' . mysqli_errno($link)." - " .mysqli_error($link)."<BR> ");
    }
    else
    {
    $row16 = mysqli_fetch_array($result, MYSQLI_BOTH);

    // create the variable of the shopping cart inventory ID 
    $sCInvId = $row16['SC_inventory_id']; 
    $sCQty=$row16['SC_order_quantity'];
    }
    if ($sCInvId != $id)
    {
        // if the shopping cart inventory ID doesnt equal the ID 
        $query8 = "INSERT INTO Shopping_cart (`SC_account_id`, `SC_inventory_id`, `SC_order_quantity`, `SC_unit_price`, `SC_created_date`, `SC_created_by`, `SC_last_updated_date`, `SC_last_updated_by`) 
        VALUES ('$accountID', '$id', '$quantity', '$unitPrice', '$dateTime', 'addToCartButton', '$dateTime', 'addToCartButton')";

        if (!$result = mysqli_query($link, $query8))
        {
        $result = 0;

        echo ('Error executing query5: '. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
        }
    }
    else{
        //If it does equal the ID select all from the shopping cart 
        $query19 = "SELECT * FROM `Shopping_cart` WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = $id";

        if (!$result = mysqli_query($link, $query19)){
            $result = 0;
            echo ('Error executing query: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
        }
        else{
            $row19 = mysqli_fetch_array($result, MYSQLI_BOTH);
            // shopping cart order quantity gets added to a vardiable
            $scDiffQuan = $row19['SC_order_quantity'];
            // the quantity gets added to the shopping cart order quantity and turned into a variable 
            $scSameItemDiffQuan = $scDiffQuan + $quantity ; 
            // update the shopping cart and set the order quantity to the new variable 
            $sql18 = "UPDATE `Shopping_cart` SET `SC_order_quantity` ='".$scSameItemDiffQuan."', `SC_last_updated_date` = now(), `SC_last_updated_by` = 'Update Cart (Updated)' WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = $id ";    

            if (!$result = mysqli_query($link, $sql18))
            {
                $result = 0; 
                echo ('Error executing query6: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
            }
        }
    }
    // zero out the quantity fields
    // zero out the quantity fields

    $quantity =0;
    $_POST['quantity1'] = 0; 
    unset($_POST['quantity1']); 
    unset($_POST['submitItem']);

    echo "<script type='text/javascript'> document.location = 'productsli.php';
    </script>";
} // end of if(isset(S_POST['submit item')) and $_POST['quantity1'] > 0)

}//end of if(loggedin() 
else{
    header("Location: index.php"); 
}
?>
</div>

<?php include 'includes/footer.php'; ?> 
</div>

<?php include 'includes/signout.php'; ?>

</body> 
</html>

<?php
?>