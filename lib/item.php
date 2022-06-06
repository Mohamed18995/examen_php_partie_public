<?php
function getItem($id = 0, $is_visible = ""){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = $id > 0 ? " item_id = ".$id : " 1 ";
    $cond .= !empty($is_visible) ? " AND is_visible = '1'" : "";


    // requete permettant de récupérer les items suivant le(s) filtre(s)
    $sql = "SELECT item_id, item_menu, item_title, item_description, is_visible, sortkey 
            FROM item 
            WHERE ".$cond." 
            ORDER BY sortkey, item_title;";

    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertItem($data){
    $item_title         = $data["item_title"];
    $item_description   = $data["item_description"];
    $item_menu          = $data["item_menu"];
    $sortkey            = $data["sortkey"];

    $sql = "INSERT INTO item
                        (item_title, item_description, item_menu, sortkey) 
                    VALUES
                        ('$item_title', '$item_description', '$item_menu', $sortkey);
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateItem($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $item_title         = $data["item_title"];
    $item_description   = $data["item_description"];
    $item_menu          = $data["item_menu"];
    $sortkey            = $data["sortkey"];

    $sql = "UPDATE item 
                SET 
                    item_title          = '".$item_title."',
                    item_description    = '".$item_description."',
                    item_menu           = '".$item_menu."',
                    sortkey             = '".$sortkey."'
            WHERE item_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideItem($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM item WHERE item_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE item SET is_visible = '".$nouvel_etat."' WHERE item_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>