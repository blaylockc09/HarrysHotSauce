<?php
session_start();
$current_file = $_SERVER['SCRIPT_NAME']; 


function loggedin()
{
    if(isset($_SESSION['Login_id'])&&!empty($_SESSION['Login_id']))
    {
        return true;
    }
    else
    {
        return false;
    }
}
/* ECHO ('Employee Id: = '.$Employee_id.'<br>');
ECHO ('Employee Status: = '.$Employee_status.'<br>'); */

function isEmployee($link){
    $_SESSION['Login_id'];
    $employeeQuery = "SELECT * FROM Employee where Employee_id = '".$_SESSION['Login_account_id']."' ";

    $result = mysqli_query($link, $employeeQuery);
    while ($row = mysqli_fetch_assoc($result)) {
        /* echo("rows from results" .$row["Employee_id"].'' .$row["Employee_status"].''); */
        $_SESSION['Employee_id'] = $row["Employee_id"];
        $_SESSION['Employee_status'] = $row["Employee_status"];
    }
    if (!$query_run1 = mysqli_query($link, $employeeQuery)) {
        $query_run1 = 0;
        echo ('Error executing query1 of Core:'. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
    }
    else{
        $row1 = mysqli_fetch_array($query_run1, MYSQLI_BOTH);
        $row_count = $row1[0];
        
       /*  echo ('row_count: '.$row_count."<BR>");
        echo ('row1: ' .$row1. "<BR>");
        echo ('row: ' .$row. "<BR>");
        echo ('employeeQuery: ' .$employeeQuery. "<BR>"); */
    }
    //empty cart test
    if ($_SESSION['Employee_status'] == 'Active'){
        return true;
    }
    else {
        return false;
    }
    /* $result = mysqli_query($link, $employeeQuery);
    $Employee_id = $result['Employee_id'];
    $Employee_status = $employeeQuery[Employee_status];
    $_SESSION['Employee_id'] = $Employee_id;
    $_SESSION['Employee_status'] = '69';
    
  

    if( $Employee_id = $Login_id || $Employee_status = "Inactive")
    {
        return true;
    }
    else
    {
        return false;
    }
 */




}


function getuserfield($field)
{
    $query = "SELECT `$field` FROM `Login` WHERE `Login_username`='".$_SESSION['Login_username']."'"; 
    if ($query_run = mysqli_query($field, $query))
    {
        if ($query_result = mysqli_fetch_assoc($query_run, MYSQLI_BOTH));
        {
            return $query_result[$field];
        }
    }
}
function build_order($account_id, $link){
//Select all the information from the shopping cart for a specific user account
$query1 = "SELECT count(*) FROM `Shopping_cart` WHERE `SC_account_id` = $account_id";

if (!$query_run1 = mysqli_query($link, $query1)) {
    $query_run1 = 0;
    echo ('Error executing query1 of Core:'. mysqli_errno($link)." - ".mysqli_error($link)."<BR>");
}
else{
    $row1 = mysqli_fetch_array($query_run1, MYSQLI_BOTH);
    $row_count = $row1[0];
}
//empty cart test
if ($row_count > 0){

    //get the next order number

    $query2 = "SELECT MAX(Order_header_number) FROM `Order_headers`"; 
    if (!$query_run2 = mysqli_query($link, $query2))
    {
    $query_run2 = 0;

    echo ('Error executing query2 of core: '. mysqli_errno($link)." ". mysqli_error ($link)."<BR>");

    }

    else
    { 
        $row2 = mysqli_fetch_array($query_run2, MYSQLI_BOTH); 
        $order_number = $row2[0];

        if (is_null($order_number)){
            $order_number = 1001;
        }
        else{
        $order_number++;
        }
    }
    //load order number into session variable 
    $_SESSION['OrderNumber'] = $order_number;

    //create the order header record 
    $query3 = "INSERT INTO Order_headers(`Order_header_number`, `Order_header_orderdate`, `Order_header_account_id`, `Order_header_status_id`, `Order_header_created_date`, `Order_header_created_by`, `Order_header_last_updated_date`, `Order_Header_last_updated_by`)
    VALUES ('$order_number', now(), '$account_id', '1', now(), 'CheckOut', now(), 'CheckOut') ";
    if (!$query_run3 = mysqli_query($link, $query3))
    {
        $query_run3 = 0;
        echo ('Error executing query3 of core:'. mysqli_errno($link)." ". mysqli_error ($link)."<BR>");
    }
        //get the order header id 
        $query4 = "SELECT Order_header_id FROM `Order_headers` WHERE `Order_header_number` = $order_number";

    if (!$query_run4 = mysqli_query($link, $query4))
    {
    $query_run4 = 0;

    echo ('Error executing query4 of Core: ' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
    }
    else
    {
    $row4 = mysqli_fetch_array($query_run4, MYSQLI_BOTH);

    $order_header_id = $row4[0]; 
    $order_details_line_number = 1;
    }
    //Select all the items from the shopping cart for a specific user account 
    $query5 = "SELECT * FROM `Shopping_cart` WHERE `SC_account_id` = $account_id"; 

    if (!$query_run5 = mysqli_query($link, $query5))
    {
        $query_run5 = 0;
        echo ('Error executing query5 of core:' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
    }
        while($row5 = mysqli_fetch_array($query_run5, MYSQLI_BOTH)) { 
            //create the variables from the shopping cart 
            $SCInvId = $row5['SC_inventory_id'];
            $SCQty=$row5['SC_order_quantity']; 
            $SCUPrice=$row5['SC_unit_price'];
            $SCDPercent=0.0; 
            $SCDAmt=0;
            
            //create order details records
            
            $query6 = "INSERT INTO `Order_details` (`Order_details_header_id`, `Order_details_line_number`, `Order_details_date`, `Order_details_inventory_id`, `Order_details_ordered_quantity`,
            `Order_details_unit_price`, `Order_details_discount_percentage`, `Order_details_discount_amount`, `Order_details_created_date`,`Order_details_created_by`,
            `Order_details_last_updated_date`, `Order_details_last_updated_by`)
            VALUES  ('$order_header_id', '$order_details_line_number', now(), '$SCInvId', '$SCQty', '$SCUPrice', '$SCDPercent', '$SCDAmt', now(),'Checkout' , now(), 'Checkout') ";
        
            if (!$query_run6 = mysqli_query($link, $query6))
            {
                $query_run6 = 0;
                echo ('Error executing query6 of core:' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
            }
            //decrement inventory on hand balance
            $query7 = "SELECT Inventory_units_in_stock FROM `Inventory` WHERE `Inventory_product_id` = $SCInvId ;"; 
            
            if (!$query_run7 = mysqli_query($link, $query7)){
                $query_runt = 0;
                echo ('Error executing query7 of core' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
            }
            else {
                $row7 = mysqli_fetch_array($query_run7, MYSQLI_BOTH); 
                $Onhand_qty = $row7[0];
                $New_onhand_qty = $Onhand_qty -$SCQty;
                if ($New_onhand_qty < 0) {
                    $New_onhand_qty = 0;
                }
            }
            //Update inventory on hand balance
            
            $query8 = "UPDATE Inventory SET `Inventory_units_in_stock` = $New_onhand_qty, `Inventory_last_updated_date` = now(), `Inventory_last_updated_by` = 'Check Out' WHERE `Inventory_product_id` = $SCInvId;"; 
            if (!$query_run8 = mysqli_query($link, $query8)) {
                $query_run8 = 0;
                echo ('Error executing query8 of core: ' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
            }
            //delete item from shopping cart 
            $query9 = "DELETE FROM `Shopping_cart` WHERE `SC_account_id` = $account_id"; 
            if (!$query_run9 = mysqli_query($link, $query9))
            {
                $query_rung = 0;
                echo ('Error executing query of core:' . mysqli_errno($link)." - ". mysqli_error($link)."<BR> ");
            }
            //increment line number for next line
            $order_details_line_number++; 
        } // end of while loop
    } //end of empty cart test//if ($row_count > 0) 
}

?>