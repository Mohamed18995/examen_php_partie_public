<?php
// protéger par login et password
// vérifier si la variable de session admin_id ($_SESSION["admin_id"]) existe
// sinon, redirection vers le formulaire de login
adminProtection();
include_once("lib/categorie.php");

$url_page = "admin_level2";

// récupération/initialisation du paramètre "action" qui va permettre de diriger dans le switch vers la partie de code qui sera a exécuter
// si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut
$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";
// récupération des informations passées en _GET
$get_category_level_2_id   = isset($_GET["category_level_2_id"]) ? filter_input(INPUT_GET, 'category_level_2_id', FILTER_SANITIZE_NUMBER_INT) : null;
$get_alpha                 = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";


switch($get_action){
    case "list":
        // récupération des item correspondant
        $result = getLevel2();

        $page_view = "level2_liste";
        break;

    case "add":
        // récupération / initialisation des données qui transitent via le formulaire
        $post_level_2       = isset($_POST["level_2"])      ? filter_input(INPUT_POST, 'level_2', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
        $post_level_1_id    = isset($_POST["level_1_id"])   ? filter_input(INPUT_POST, 'level_1_id', FILTER_VALIDATE_INT)           : null;


        $level_1 = [0 => "=== choix ==="]+array_column(getLevel1(0, "1"), "level_1", "category_level_1_id");


        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter une sous-catégorie</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Catégorie associée", ["name" => "level_1_id", "class" => "u-full-width"], $level_1, $post_level_1_id, true, "twelve columns");
        $input[] = addInput("Nom de la sous-catégorie", ["type" => "text", "value" => $post_level_2, "name" => "level_2", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "level2_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values = array();
            $data_values["level_2"]     = $post_level_2;
            $data_values["level_1_id"]  = $post_level_1_id;
            // exécution de la requête
            if(insertLevel2($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getLevel2();

            $page_view = "level2_liste";
        }
        break;

    case "update":
        $level_1 = [0 => "=== choix ==="]+array_column(getLevel1(0, "1"), "level_1", "category_level_1_id");

        if(empty($_POST)){
            $result = getLevel2($get_category_level_2_id);

            $post_level_2         = $result[0]["level_2"];
            $post_level_1_id      = $result[0]["category_level_1_id"];
        }else{
            // récupération / initialisation des données qui transitent via le formulaire
            $post_level_2         = isset($_POST["level_2"])                ? filter_input(INPUT_POST, 'level_2', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
            $post_level_1_id      = isset($_POST["category_level_1_id"])    ? filter_input(INPUT_POST, 'category_level_1_id', FILTER_VALIDATE_INT)  : null;

        }



        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier une catégorie</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Catégorie associée", ["name" => "category_level_1_id", "class" => "u-full-width"], $level_1, $post_level_1_id, true, "twelve columns");
        $input[] = addInput("Nom de la sous-catégorie", ["type" => "text", "value" => $post_level_2, "name" => "level_2", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&category_level_2_id=".$get_category_level_2_id, "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "level2_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            $data_values = array();
            $data_values["level_2"]     = $post_level_2;
            $data_values["level_1_id"]  = $post_level_1_id;

            // exécution de la requête
            if(updateLevel2($get_category_level_2_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getLevel2();

            $page_view = "level2_liste";
        }


        break;

    case "showHide":
        if(showHideLevel2($get_category_level_2_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // récupération des item correspondant
        $result = getLevel2();

        $page_view = "level2_liste";

        break;
}

?>