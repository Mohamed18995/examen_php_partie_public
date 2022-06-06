<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
    <title></title>
    <meta name="description" content="" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="language" content="fr" />
    <meta name="revisit-after" content="7 days" />
    <meta name="robots" content="index, follow" />

    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/menu_accordion/jquery.quiccordion.js"></script>
    <link rel="stylesheet" type="text/css" href="css/public/ScatteredPolaroidsGallery/component.css" />
    <script src="js/ScatteredPolaroidsGallery/modernizr.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#accordion").quiccordion();

        });

    </script>

</head>
<body>
<section class="container">

<?php

foreach ($articles as $items) { ?>
<div class='row'>
    <?php
       foreach ($items as $item) {
        $item_id = $item['id'];
        $title = $item["ad_title"];
        $description = substr($item["ad_description"], 0, 100);
        $designer_fullname = replaceEmptyString($item["designer_fullname"]);
        $manufacturer = replaceEmptyString($item["manufacturer"]);
        $title = $item["ad_title"];
        $item_price = number_format($item["price"], 2 , ',' ,'.') 

    
    
    ?>



    <article class="pres_product four columns border">
        <div class="thumb">
            <a href="./?p=detail&id=<?php echo $item_id ?>" title="">
                <span class="rollover"><i>+</i></span>
                <img src=<?php echo "upload/thumb/thumb_".$item_id."-1.jpg" ?> alt="" />
            </a>
        </div>

        <header>
            <h4>
                <a href="./?p=detail&id=<?php echo $item_id ?>" title=""><?php echo $title ?></a>
            </h4>
            <div class="subheader">
                <span class="fa fa-bars"></span> <a href="" title=""></a>
                <span class="separator">|</span>
                <span class="fa fa-pencil"></span> <a href="" title=""><?php echo $designer_fullname?></a>
                <span class="separator">|</span>
                <span class="fa fa-building-o"></span>
                <a href="" title="">
                    <small style="opacity: 0.5;"><?php echo $manufacturer?></small>
                </a>
            </div>
        </header>
            
        <div class="une_txt">
            <p>
                <?php echo $description ?>
                <a href="./?p=detail&id=<?php echo $item_id ?>" title="">[...]</a>
                <b><?php echo$item_price ?></b>
            </p>
        </div>
    </article>
   <?php
   } 
   ?>
</div>
<?php 
} 
?>
<br /><br />
<ul class='pagination'>
    <?php
        if($get_current_page>1) {
    ?>
    <li><a href='./?p=home&start=<?php echo 1?>' title='derniers résultats'>|<<</a></li>
    <li><a href='./?p=home&start=<?php echo $get_current_page-1?>' title='résultats suivants'><</a></li>
    <li>...</li>
    <?php 
    } 
   ?>

<?php 
    $activeClass = "";
    for($i=$prev ; $i<=$next ; $i++){
        $activeClass = $i == $get_current_page ? "active" : "";           
        echo " <li><a href='./?p=home&start=".($i)."' class='".$activeClass."'>".($i)."</a></li>";
    }
?>
<?php
    if($get_current_page < $last_page) { 
        ?>
            <li>...</li>
            <li><a href='./?p=home&start=<?php echo $get_current_page+1?>' title='résultats suivants'>></a></li>
            <li><a href='./?p=home&start=<?php echo $last_page?>' title='derniers résultats'>>>|</a></li>
                </ul>
            <?php } ?>
</section>

</body>
</html>