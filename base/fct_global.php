<?php
// affichage de debugage -> print_r mis en forme
function print_q($val){
    echo "<pre style='background-color:#000;color:#3FBBD5;font-size:11px;z-index:99999;position:relative;'>";
    print_r($val);
    echo "</pre>";
}

function adminProtection(){
    if((!isset($_SESSION["admin_id"])) || (empty($_SESSION["admin_id"]))|| (!is_numeric($_SESSION["admin_id"]))){
        header("Location: index.php?p=login");
        exit;
    }
}

function adminMenu($public_menu = ""){

    if(isset($_SESSION["admin_id"])) {
    ?>
    <div class="row">
        <nav class="nav-show">
            <div class="container">
                <ul>
                    <?php
                    //if(isset($_SESSION["admin_id"])){
                    ?>
                    <li><a href="index.php?p=admin_item">Gestion des pages</a></li>
                    <li><a href="index.php?p=admin_admin">Gestion des utilisateurs</a></li>
                    <li>
                        <a>Gestion du shop</a>
                        <ul>
                            <li><a href="index.php?p=admin_designer">Designer</a></li>
                            <li><a href="index.php?p=admin_manufacturer">Manufacturier</a></li>
                            <li><a href="index.php?p=admin_shape">Etat</a></li>
                            <li><a href="index.php?p=admin_level1">Catégorie</a></li>
                            <li><a href="index.php?p=admin_level2">Sous-catégories</a></li>
                            <li><a href="index.php?p=admin_ad">Fiches produits</a></li>
                        </ul>
                    </li>
                    <li>
                        <a>Mon compte</a>
                        <ul>
                            <li><a href="index.php?p=admin_password">Modifier mon mot-de-passe</a></li>
                            <li><a href="index.php?p=admin_unlog">Déconnexion</a></li>
                        </ul>
                    </li>
                    <?php
                    /*}else {
                        if (is_array($public_menu)) {
                            foreach ($public_menu as $pm) {
                                $rm_id = $pm["item_id"];
                                $rm_menu = $pm["item_menu"];
                                echo "<li><a href=\"index.php?p=public_item&item_id=" . $rm_id . "\">" . $rm_menu . "</a></li>";
                            }
                        }
                    }*/
                    ?>
                    <li class="u-pull-right"><a href="index.php?p=login">Login</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <?php
    }
}


function pagination($limit_next , $limit_previous, $current_page , $last_page){

    if($current_page > $last_page || $current_page < 1 || !is_number($current_page)){
        header("Location: index.php?p=home");
    } 
 
    $previous_start = $current_page <= $limit_previous ? 1 : ($current_page - $limit_previous);
   $next_end = $current_page > ($last_page - $limit_next) ? $last_page : $current_page + $limit_next;

    return ["next_nb" =>$next_end , "prev_nb" => $previous_start];
};

// verifier si il y a espace ou non 
 function replaceEmptyString($string) {
     return  strlen(trim($string)) == 0 ? "- non précisé -" : $string;
 }

?>