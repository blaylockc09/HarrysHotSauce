<?php

include 'includes/core.php';

include 'includes/connection.php'; 


date_default_timezone_set ('America/New_York');

error_reporting(E_ERROR | E_PARSE);

if(loggedin() && isEmployee($link) ) {
    $_SESSION['Login_username'] = getuserfield('Login_username');
    $accountID = $_SESSION['Login_account_id'];

    $query = "SELECT * FROM `Accounts` WHERE `Account_id` = '".$accountID."'"; 
    
    if (!$result = mysqli_query($link, $query))
    {
        $result = 0;
        echo ('Error executing query 1: ' .mysqli_errno($link)." - ".mysqli_error($link)."<BR>");  
    }
    else
    {
        $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    }
//Putting account table columns in variables 
    $firstName = $row['Account_firstname'];
    $lastName = $row[ 'Account_lastname']; 
    $email = $row['Account_email_address'];
    $address = $row['Account_address1']; 
    $city = $row['Account_city']; 
    $state = $row['Account_state_region']; 
    $zip = $row['Account_postal_code']; 
    ?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>Harry's Hot Sauce</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <link href="css/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="wrapper">
        <?php include 'includes/header.php'; ?>

        <div id="body-wrapper">

            <p class="titles">Inventory</p>

            <?

    //get the next employee number
    $productNumber = 0;
    $query2 = "SELECT MAX(Products_item_number) FROM `Products` ";

    if (!$query_run2 = mysqli_query($link, $query2))
    {
        $query_run2 = 0;
        $productNumber = null;
    }
    else
    {
        $row2 = mysqli_fetch_array($query_run2, MYSQLI_BOTH);
        $productNumber = $row2[0];
    }
    if (is_null($productNumber)) {
        $productNumber = 1001;
    }
    else
    {
        $productNumber++;
    }

    //build select combo for names

    $query1 = "SELECT * FROM `Products`";

    if (!$query_run1 = mysqli_query($link, $query1))
    {
        $query_run1 = 0;
        echo ('Error executing query1 in inventory: ' .mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
    }
    else
    {
    $row1 = mysqli_fetch_array($query_run1, MYSQLI_BOTH); 
    $productsbox='<select name="products" id="combo">';
    $productsbox.='<option value=0>' .' ' . '</option>'; 
    $productsbox.='<option value='.$row1['Products_id'] .'>'.$row1['Products_display_name']. '</option>';
    }
    while ($row = mysqli_fetch_array($query_run1, MYSQLI_BOTH))
    {
        $productsbox.='<option value=" '.$row['Products_id'].'">'.$row['Products_display_name']. '</option>';
    } 
    $productsbox.='</select>';

    mysqli_free_result($query_run1);

    ?>
            <div id="register-wrapper">

                <form action="" method="post">

                    <div class="label-wrapper">

                        <p>Inventory:</p>

                        <? echo $productsbox; ?>

                    </div>

                    <input type="submit" name="submitfind" value="Update" class="submit-reset">
                    <input type="reset" name="reset" value="Reset" class="submit-reset">

                </form>
                <?php
    
    $productID= "";
    $productImage = "";
    $productName = "";
    $price = "";
    $description = "";
    $smbutton='Add';
    $stock = "";

    if(isset($_POST['submitfind']))
    {
        $productID = $_POST['products']; 
        $smbutton='Update';

        //1f the submit find button is clicked do the following

        $fquery = "SELECT * FROM `Products`LEFT JOIN Inventory 
        ON `Products_id` = `Inventory_id`
        WHERE `Products_id` = '".$productID."'";
        if (!$fresult = mysqli_query($link, $fquery))
        {
            $fresult =0;

            echo ('Error executing query in Product selected:'. mysqli_errno($link)." - ". mysqli_error($link)."<BR>");

        }
        else
        {
        $frow = mysqli_fetch_array($fresult, MYSQLI_BOTH);
        //Putting account table columns in variables

        $productImage = $frow['Products_image'];
        $productName = $frow['Products_display_name'];
        $price = $frow['Products_unit_price'];
        $description = $frow['Products_description'];
        $stock = $frow['Inventory_units_in_stock'];
        }
    }
    ?>

            </div>

            <div id="register-wrapper">

                <form action="" method="post">
                    <input type="hidden" name="Product_ID" size="42" value="<?php echo $productID; ?>" />
                    <div class="label-wrapper">
                        Product Name:
                        <input type="text" name="Product_name" size="42" value="<?php echo $productName; ?>" required />

                    </div>

                    <div class="label-wrapper">

                        Products Image:

                        <input type="text" name="Products_image" size="42" value="<?php echo $productImage; ?>"
                            required />

                    </div>


                    <div class="label-wrapper">

                        Products Description:
                        <input type="text" name="Products_description" size="42" value="<?php echo $description; ?>"
                            required />

                    </div>

                    <div class="label-wrapper">

                        Products Price:
                        <input type="text" name="Products_unit_price" size="42" value="<?php echo $price; ?>"
                            required />

                    </div>

                    <div class="label-wrapper">

                        Units in stock:
                        <input type="text" name="unitsInStock" size="42" value="<?php echo $stock; ?>" required />

                    </div>

                    <input type="submit" name="submitProduct" value="<?php echo $smbutton; ?>" class="submit-reset">
                    <input type="reset" name="reset" value="Reset" class="submit-reset">

                </form>

                <?php

    if(isset($_POST['submitProduct']))
    {
    //All html form names turned in to php variables 
   
    $dateTime = date("Y-m-d H:1:s");
    $addOrSave = $_POST['submitProduct'];

    
    $productImage = $_POST['Products_image'];
    $productName = $_POST['Product_name'];
    $price = $_POST['Products_unit_price'];
    $description = $_POST['Products_description'];
    $productID = $_POST['Product_ID'];
    $stock = $_POST['unitsInStock'];

        if($addOrSave == "Update"){
            /* ECHO ('ProductID = '.$productID.'');
            ECHO ('ProductIMage = '.$productImage.'');
            ECHO ('ProductName = '.$productName.'');
            ECHO ('ProductPrice = '.$price.'<BR>');
            ECHO ('ProductDescription = '.$description.''); */
            
            
            $query =    "UPDATE `Products` 
                        SET `Products_image` ='$productImage', 
                        `Products_image_id` = '$productImage', 
                        `Products_display_name` = '$productName', 
                        `Products_unit_price` = $price,
                        `Products_description` = '$description',
                        `Products_last_updated_by` = 'Inventory (Updated)' 
                        WHERE `Products_id` = '$productID'";

                    if (!$query_run = mysqli_query($link, $query)) 
                    {
                        $query_run = 0;
                        echo ('Error executing query in Inventory Update: '. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
                        
                    }
                    mysqli_free_result($query_run);
                    
            $query =    "UPDATE `Inventory`
                    SET `Inventory_units_in_stock` =$stock

                    WHERE `Inventory_product_id` = $productID";

                if (!$query_run = mysqli_query($link, $query)) 
                {
                    $query_run = 0;
                    echo ('Error executing query in Inventory Update: '. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
                    
                }
                mysqli_free_result($query_run);
        }
        else{
           /*  ECHO ('ProductID = '.$productID.'<br>');
            ECHO ('ProductIMage = '.$productImage.'<br>');
            ECHO ('ProductName = '.$productName.'<br>');
            ECHO ('ProductPrice = '.$price.'<BR>');
            ECHO ('ProductDescription = '.$description.'<br>'); */


            ECHO ('ProductStock = '.$stock.'<BR>');

            $query = "INSERT INTO `Products` (`Products_image`,	`Products_name`, `Products_item_number` ,`Products_display_name`,
            `Products_unit_price` ,`Products_quantity_per_unit`, `Products_image_id`, `Products_description`,	
            `Products_created_date`,`Products_last_updated_date`,	`Products_last_updated_by`)
            VALUES ('$productImage', '$productName','$productNumber' , '$productName', '$price',  '1', '$productImage' ,'$description' , '$dateTime', '$dateTime', 'Inventory Page')";

            if (!$query_run = mysqli_query($link, $query)) 
            {
                $query_run = 0;
                echo ('Error executing query in Product Insert: '. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
                
            }

            mysqli_free_result($query_run);

            $query = "INSERT INTO `Inventory` (`Inventory_units_in_stock`, `Inventory_Vendor_id`, 
                                               `Inventory_unit_cost`, `Inventory_created_date`, `Inventory_created_by`	, `Inventory_last_updated_date` , `Inventory_last_updated_by` )
                        VALUES ($stock, 1 , $price , NOW() , 'Inventory' , NOW(), 'Inventory' )";

            if (!$query_run = mysqli_query($link, $query)) 
            {
                $query_run = 0;
                echo ('Error executing query in Inventory Insert: '. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
                
            }
        }
       

    }
    
    ?>

            </div>

        </div>

        <?php include 'includes/footer.php'; ?>

    </div>

    <?php include 'includes/signout.php'; ?>

</body>

</html>

<?php

} 
else 
{
    header( 'Location: login.php'); 

}

?>