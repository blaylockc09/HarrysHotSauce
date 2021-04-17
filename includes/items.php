<?php

if (!mysqli_select_db($link, $db_dbname)) { 
    die("Uh oh, couldn't select database ". $db_dbname);

} 
$query = "SELECT * FROM Products, Images WHERE Products_image_id = Images_id";

if (!$result = mysqli_query ($link, $query))
{

    $result = 0;
    echo ("Error executing query: ". mysqli_error($link)." - ".mysqli_errno($link). "<BR>");

}
else
    {
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) 
        {
            print '<div class="item-wrapper">'; 
            print '<div class="product-name">';
            print $row [Products_name];
            print '</div>';
            print '<div class="product-image">'; 
            print '<img src="'.$row[Images_url].'"/>';
            print '</div>';
            print '<div class="product-description">'; 
            print $row [Products_description];
            print '</div>';
            print '</div>';
        }
    }
 
?>