<?php
    include 'includes/core.php';
    include 'includes/connection.php'; 
    date_default_timezone_set('America/New_York'); 
    error_reporting(E_ERROR | E_PARSE);
?>

<?php 
    if(loggedin()) {

        $_SESSION['Login_username'] = getuserfield('Login_username'); 
        $LoginID = $_SESSION['Login_id'];

        //Select all information from accounts where it equals to the session login ID 

        $query = "SELECT * FROM `Accounts`, Login WHERE `Account_id` = Login_account_id and Login_id = '".$_SESSION['Login_id']."'"; 

        //Result - mysql_query ($query) or die(mysql_error();
        // Srow = mysql_fetch_assoc($result); 
        if (!$result = mysqli_query($link, $query))
        {
            $result = 0;
            echo ('Error executing query1: ' . mysqli_errno($link)." - ".mysqli_error($link). "<BR>");
        }
        else {
        //$row = mysql_fetch_assoc($result);

        $row = mysqli_fetch_array($result, MYSQLI_BOTH); 
        // Putting account table columns in variables 
        $firstName = $row['Account_firstname'];
        $accountID = $row['Account_id']; 

        //Selecting all from inventory
        $query2 = "SELECT * FROM `Inventory`"; 
        if (!$result2 = mysqli_query($link, $query2))
        {
            $result2 = 0;
            echo ('Error executing query2: ' . mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
        }
        else
        {
            $row2 = mysqli_fetch_array($result2, MYSQLI_BOTH);
        }
    }
    ?>
    <!DOCTYPE html>
    <html> 
        <head>

    <meta charset="utf-8">
    <title>Harry's Hot Sauce</title> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"> <link href="css/layout.css" rel="stylesheet" type="text/css">
    </head>

    <body>
    <div id="wrapper">
    <?php include 'includes/header.php'; ?>
    <div id="body-wrapper">
    <?php
        //Selecting all from the shopping car and products
        $query = "SELECT * FROM `Shopping_cart`, Products, Inventory WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = `Inventory_id` AND `Inventory_product_id` = `Products_id` order by `SC_inventory_id`";

        if (!$result = mysqli_query($link, $query))
        {
            $result = 0;
            echo ('Error executing query: '. mysqli_errno($link)." - ". mysqli_error ($link)."<BR>");
        }
        else
        {
            print '<div style="width: 225px; height:40px; float:left; line height:40px; font-size:18px; font-weight:bold;">Product Name</div>';
            print '<div style="width:105px; height:40px; float:left; line height:40px; font-size:18px; font-weight:bold;">Quantity</div>';
            print '<div style="width:115px; height:48px; float:left; line height:40px; font-size:18px; font-weight:bold;">Unit Prices</div>'; 
            print '<div style="width:105px; height:40px; float:left; line height:40px; font-size:18px; font-weight:bold;">Subtotal</div>'; 
            //variable for total starts at 0
            $total = 0;
            $currentShipping = 0;
            // Loop through all shopping cart and products display nicely
            $itemsOrdered = 0;
            while($row = mysqli_fetch_assoc($result))
            {
                //add a variable for shopping cart order quantity
                $inventoryRemoveQuan = $row['SC_order_quantity'];
                print '<form method="post" action="">';
                print '<br>'; 
                print '<br>';
                print '<br>';
                print '<hr>';
                print '<input type="hidden" value="'.$row[SC_inventory_id].'" name="id"/>';
                print '<div style="width:235px; height:40px; float:left; line-height:40px;">' .$row[Products_name]. '</div>';
                print '<div style="width:110px; height:40px; float:left; line-height:40px;">
                <input type="text" name="quantityOrdered" value="'. $inventoryRemoveQuan .'" style="width:40px; height:20px;"></div>'; 
                print '<div style="width:110px; height:40px; float:left; line-height:40px; ">$'.number_format($row[SC_unit_price],2).'</div>'; 
                print '<div style="width:105px; height:40px; float:left; line-height:40px;">$'.number_format($subTotal = $inventoryRemoveQuan * $row[SC_unit_price],2). '</div>'; 
                print '<div style="width:150px; height:40px; float:left;"><input type="submit" name="remove" value="Remove Item" class="removeItem"/></div>'; 
                print '<div style="width:150px; height:40px; float:left;"><input type="submit" name="updateQuan" value="Update Quantity" class="updateItem"/></div>';
                print '<br>';
                print '<br>';
                print '<br>';
                print '<hr>';               
                $total += $subTotal;
                print '</form>';
                $itemsOrdered = $itemsOrdered + $inventoryRemoveQuan;
            } 
            print '<br>';
            print '<br>'; 
            print '<br>';
            //Display purchases cost
            print 'Purchases $ '. number_format($total, 2);
            print '<br>';
            //shipping variable

            mysqli_free_result($query_run1);
            $shipCostQuery = "SELECT * from `General_lookup` WHERE `GL_type` = 'Shipping Cost'";

            if(!$query_run1 = mysqli_query($link, $shipCostQuery)){
                $query_run1 = 0;
                echo('Error occurred - '. mysqli_errno($link). ' - ' .mysqli_error($link). "<BR>");
            }
            else {
                /* $shippingbox ='Shipping'; */
                $row1=mysqli_fetch_array($query_run1, MYSQLI_BOTH);
                $shippingbox='<select name="shipping" id="shippingCost" onchange="myFunction()">';
                $shippingbox.='<option value="0">Select</option>';
                $shippingbox.='<option value="'.$row1['GL_data'].'">'.$row1['GL_name'].' - '.$row1['GL_data'].'</option> ' ;
            }
            while($row = mysqli_fetch_array($query_run1, MYSQLI_BOTH)){
                $shippingbox.='<option value="'.$row['GL_data'].'">'.$row['GL_name'].' - '.$row['GL_data'].'</option>'  ;
            }
                $shippingbox.='</select> '  ;


           /* 
            $shippingbox ='Shipping';
            $shippingbox.='<select name="shipping" id="shippingCost" onchange="myFunction()">';
            $shippingbox.='<option value="0">Select</option>';
            $shippingbox.='<option value="12.95">USPS $12.95</option> ' ;
            $shippingbox.='<option value="21.95">Delivery Truck $21.95</option>'  ;
            $shippingbox.='<option value="13.55">FED EX $13.55</option>'  ;
            $shippingbox.='<option value="2.95">Customer Pickup $2.95</option>'  ;

            $shippingbox.='</select> '  ; */

            $shipping;

            if($total == 0 ){
                $shipping = 0;
            }
            else{
                $shipping =  $currentShipping;
            
            }

            $grandtotal;
           
            print '<form action="" method="post">';
            echo'Shipping: ';
            echo $shippingbox;
            print '<input type="hidden" value="'.$row['SC_inventory_id'].'" name="id"/>';
            print '</form>';
            print '<br>';
            print '<br>';
            //Display the total added to the shipping into vaiable grandtotal 
            print 'Total Items Ordered:'.$itemsOrdered.' <BR/>';
            print 'Total $ <span id="totalAmount">' .number_format($grandtotal = $total + $shipping, 2).'</span>';
            print '<br>';
            print '<br>';
            print '<br>';
            print $error;
            print '<form action="" method="post">';
            print '<input type="submit" name="submitCheckout" value="Check" class="test"/>';
            print '</form>';
            
            echo"<script language='javascript'>
                function myFunction() {
                    let shipping = document.getElementById('shippingCost').value;
                    let oldTotal = document.getElementById('totalAmount').value;
                    
                    let newTotal = document.getElementById('totalAmount').innerHTML = parseFloat(shipping) + parseFloat('$total');
                    console.log(shipping);
                    console.log(oldTotal);
                    console.log(newTotal);
                }    
            </script>";
               
            if(isset($_POST['remove']))
            {
                // if the remove item button is clicked post the ID into a variable ID
                $id=intval($_POST['id']);
                //Delete the item from shopping cart that was removed
                $query3 = "DELETE FROM `Shopping_cart` WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = $id";
                // $query_Run3 = mysql_query($query3);
            if (!$query_run3 = mysqli_query($link, $query3))
            {
                $result = 0;
                echo ("Error executing query3:" . mysqli_errno($link)." - ". mysqli_error($link)."<BR>");
            } 
            else
            {               
                header('Location: cart.php');
            }
            }// if(isset($_POST['remove))

            if(isset($_POST['updateQuan']))
            {
                //check to see if the update quantity was checked. if it was post variable to ID and other variables
                $id=intval($_POST['id']);
                $quantityOrdered = $_POST['quantityOrdered']; 
                if ($quantityOrdered !=0)
                {
                    //Update the shopping cart with the quantity \
                    $query19 = "UPDATE `Shopping_cart` 
                                SET `SC_order_quantity` ='".$quantityOrdered."', 
                                `SC_last_updated_date` = now(), 
                                `SC_last_updated_by` = 'Update Cart (Updated)' 
                                WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = $id ";

                    //$query_run 19 - mysql_query($query19); 
                    if (!$query_run19 = mysqli_query($link, $query19))
                    {
                        $result = 0;

                        echo ('Error executing query 19: ' . mysqli_errno($link)." - ".mysqli_error($link). "<BR>");
                    }
                }
                else
                {
                    //Delete the item from shopping cart that was removed 
                    $query3 = "DELETE FROM `Shopping_cart` WHERE `SC_inventory_id` = $id and `SC_account_id` = $accountID ";
                    //$query run 3 - mysql_query($query3) 
                    if (!$query_run3 = mysqli_query($link, $query3))
                    {
                        $result = 0;
                        echo ("Error executing query3: ". mysqli_errno($link)." - ".mysqli_error($link). "<BR>");
                    }
                }
                //Link to customer cart page 
                header('Location: cart.php');

            }
            if(isset($_POST['updateShipping']))
            {
                
                //check to see if the update quantity was checked. if it was post variable to ID and other variables
                $currentShipping = $_POST['shipping'];
                $id=intval($_POST['id']);
               /*  echo "<script type='text/javascript'> console.log('$currentShipping')</script>";
                ECHO('SHIPPING COST:'.$currentShipping.'<BR>');
                ECHO('account ID:'.$accountID.'<BR>');
                ECHO('SHIPPING ID:'.$id.'<BR>'); */
                
                 if ($quantityOrdered !=0)
                {
                    ECHO('SHIPPING COST:'.$currentShipping.'<BR>');
                    //Update the shopping cart with the quantity \
                    $query20 = "UPDATE `Shopping_cart` 
                                SET `SC_Shipping_Cost` = $currentShipping , 
                                `SC_last_updated_date` = now(), 
                                `SC_last_updated_by` = 'Update Cart (Updated)' 
                                WHERE `SC_account_id` = $accountID AND `SC_inventory_id` = $id ";

                    //$query_run 19 - mysql_query($query19); 
                    if (!$query_run20 = mysqli_query($link, $query20))
                    {
                        $result = 0;
                        echo ('Error executing query 20: ' . mysqli_errno($link)." - ".mysqli_error($link). "<BR>");
                    }
                } 
            }

            if(isset($_POST['submitCheckout']) and $total != 0 )
            {
                $shipmentQuery = "INSERT INTO `Shipping` ('Shipping_shipped_quantity', `Shipping_handling_costs`)
                VALUES($itemsOrdered,$currentShipping)";
                echo "<script type='text/javascript'> document.location = 'checkout.php'; </script>";
            }
        }

    ?>
    </div>
    <?php include 'includes/footer.php'; ?>
    </div>
<?php include 'includes/signout.php'; ?>
</body>
</html>
<?php
} else {
    header('Location: login.php');
}

?>