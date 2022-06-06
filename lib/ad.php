<?php
function getAd($id = 0, $alpha = ""){

    if (!is_numeric($id)) {
        return false;
    }

    $cond = !empty($alpha) ? " ad.ad_title LIKE '" . $alpha . "%' " : " 1 ";
    $cond .= $id > 0 ? " AND ad_id = " . $id : "";

    $sql = 'SELECT ad.ad_id AS id, ad.category_level_2_id, ad.manufacturer_id, ad.designer_id, ad.shape_id, ad.ad_title as title, concat(d.firstname," ",d.lAStname) AS fullName , ad.is_visible  ,m.manufacturer ,concat(cl1.level_1 , " -> ", cl2.level_2) AS category, ad.category_level_2_id as category_id, ad.shape_id, ad.designer_id , ad.manufacturer_id , ad.ad_title , ad.ad_description , ad.ad_description_detail , ad.price_htva ,ad.price_delivery
              FROM ad AS ad JOIN category_level_2 AS cl2 
              ON ad.category_level_2_id = cl2.category_level_2_id 
              JOIN category_level_1 AS cl1 
              ON cl2.category_level_1_id = cl1.category_level_1_id 
              JOIN manufacturer AS m 
              ON ad.manufacturer_id = m.manufacturer_id 
              JOIN designer d 
              ON d.designer_id = ad.designer_id
              WHERE ' . $cond . '
              ORDER BY fullName';

    $result = requeteResultat($sql);

    return $result;
}


function getOptions($array, $key, $value) {
    $options = [];
    $options[""] = "=== choix ===";
    foreach ($array as $item) {
        $options[$item[$key]] = $item[$value];
    }
    return $options;
}

function insertAd($data){
    $category_level_2_id    = $data["category_level_2_id"];
    $shape_id               = $data["shape_id"];
    $designer_id            = $data["designer_id"];
    $manufacturer_id        = $data["manufacturer_id"];
    $ad_title               = $data["ad_title"];
    $ad_description         = $data["ad_description"];
    $ad_description_detail  = $data["ad_description_detail"];
    $price_htva             = $data["price"];
    $price_delivery         = $data["price_delivery"];

    $amount_tva = round($price_htva * 0.21, 2);
    $price = $price_htva + $amount_tva;


    $sql = "INSERT INTO ad
                (category_level_2_id, shape_id, designer_id, manufacturer_id, ad_title, ad_description, ad_description_detail, price, price_htva, amount_tva, price_delivery, admin_id, date_add) 
            VALUES
                ($category_level_2_id, $shape_id, $designer_id, $manufacturer_id, '$ad_title', '$ad_description', '$ad_description_detail', $price, $price_htva, $amount_tva, $price_delivery, ".$_SESSION['admin_id'].", NOW());
            ";

    // exécution de la requête
    return ExecRequete($sql);
}

function updateAd($id, $data){
    if(!is_numeric($id)){
        return false;
    }
    $category_level_2_id    = $data["category_level_2_id"];
    $shape_id               = $data["shape_id"];
    $designer_id            = $data["designer_id"];
    $manufacturer_id        = $data["manufacturer_id"];
    $ad_title               = $data["ad_title"];
    $ad_description         = $data["ad_description"];
    $ad_description_detail  = $data["ad_description_detail"];
    $price_htva             = $data["price"];
    $price_delivery         = $data["price_delivery"];

    $amount_tva = round($price_htva * 0.21, 2);
    $price = $price_htva + $amount_tva;

    $sql = "UPDATE ad 
                SET
                    category_level_2_id = $category_level_2_id, 
                    shape_id = $shape_id, 
                    designer_id = $designer_id, 
                    manufacturer_id = $manufacturer_id, 
                    ad_title = '$ad_title', 
                    ad_description = '$ad_description', 
                    ad_description_detail = '$ad_description_detail', 
                    price = $price, 
                    price_htva = $price_htva, 
                    amount_tva = $amount_tva, 
                    price_delivery = $price_delivery 
            WHERE ad_id = $id;
            ";

    return ExecRequete($sql);
}

function showHideAd($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM ad WHERE ad_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE ad SET is_visible = '".$nouvel_etat."' WHERE ad_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>