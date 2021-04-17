<?php $allNavItems = array(
    array(
        'link' => 'index.php',
        'text' => 'Home'
    ),
    array(
        'link' => 'products.php',
        'text' => 'Products'
    ),
    array (

        'link' => '#',

        'text' => 'Account',

        'subs' => array(
            array(
                'link' => 'register.php',
                'text' => 'Register'
                ),
                array(
                'link' => 'login.php',
                 'text' => 'Sign in'
                )
        )
    )
);

function displayAllSiteLinks($items, $defaulticlass = '', $depth = 1)
{
    // Convert numbers to verbose words. This stops at 9 levels. 
    $mappedLevelNames = array (1 => 'first', 2 => 'second', 3 => 'third', 4 => 'fourth', 5 => 'fifth', 6 => 'sixth', 7 => 'seventh', 8 => 'eighth', 9 => 'ninth');
    
    $currentLevelClass = $mappedLevelNames [$depth].'-level';

// Create the class for the current level's UL 
    if($defaultuliclass != '')
        $ulClass = $defaultUlClass;
    else
        $ulClass = $currentLevelClass;

        echo '<ul class="' .$ulClass. '">';

        // Loop through each item at the current level 
        
        foreach($items as $item)
        {
            //Is it active?
        
        $activeClass = '';
        $disabledClass= '';
        
        $found = strpos ($_SERVER[ 'PHP_SELF'], $item['link']);
        
            if ($found !== false) {
                // it is found 
        $activeClass = 'active';
        }
        
        echo '<li class="' .$activeClass.'">';
        
        // display the link as normal
        echo '<a href="'.$item['link'].'">'.$item['text'];
        // close link
        
        echo '</a>';
        
        // This is where the magic really happens. 
        // If this item has sub-items, start this function all over!
        
        if(isset($item['subs']))
        {
        // Now we want to parse the current item's subs (Sitems) 
        // If the current item has a subsClass, it is $defaultuiclass 
        // The depth should always be + 1 since we're going a level deeper
        
        displayAllSiteLinks($item['subs'], (isset($item['subsClass']) ? $item['subsClass'] : ''), $depth + 1);
        }
        echo '</li>';
    }
        echo '</ul>';
        
        }
        
        ?>     
        <nav>        
           <?php displayAllSiteLinks($allNavItems); ?>         
        </nav>