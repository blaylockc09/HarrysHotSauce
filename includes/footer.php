<footer>
    <div id="copyright"> 
    <?php
        $copyYear = 2013; // start date
        $curYear = date('Y'); // second year 
        echo $copyYear . (($copyYear != $curYear) ? '-'. $curYear : ' ');  
    ?>
    Copyright.
    </div>
    <div id="sources">
        <a href="sources.php">Sources</a>
    </div>
    <div id="company-name">
        <span>Harry's Hot Sauce</span>
    </div>
</footer>