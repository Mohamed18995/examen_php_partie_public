<?php
adminProtection();
include_once("lib/item.php");

$url_page = "admin_item";

$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";

$get_item_id   = isset($_GET["item_id"]) ? filter_input(INPUT_GET, 'item_id', FILTER_SANITIZE_NUMBER_INT) : null;
$get_alpha      = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";


switch($get_action){
    case "list":
        $result = getItem();

        $page_view = "item_liste";
        break;

    case "add":
        $post_item_title        = isset($_POST["item_title"])       ? filter_input(INPUT_POST, 'item_title', FILTER_SANITIZE_SPECIAL_CHARS)         : null;
        $post_item_description  = isset($_POST["item_description"]) ? filter_input(INPUT_POST, 'item_description', FILTER_SANITIZE_SPECIAL_CHARS)   : null;
        $post_item_menu         = isset($_POST["item_menu"])        ? filter_input(INPUT_POST, 'item_menu', FILTER_SANITIZE_SPECIAL_CHARS)          : null;
        $post_sortkey           = isset($_POST["sortkey"])          ? filter_input(INPUT_POST, 'sortkey', FILTER_VALIDATE_INT)                      : null;

        $input = [];

        $input[] = addLayout("<h4>Ajouter une page</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput("Contenu du menu", ["type" => "text", "value" => $post_item_menu, "name" => "item_menu", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addInput("Titre de la page", ["type" => "text", "value" => $post_item_title, "name" => "item_title", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addTextarea('Texte de la page', array("name" => "item_description", "class" => "u-full-width"), $post_item_description, true, "twelve columns");
        $input[] = addInput("Position souhaitée dans le menu", ["type" => "number", "value" => $post_sortkey, "name" => "sortkey"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        if($show_form != false){
            $page_view = "item_form";
        }else{

            $data_values = array();
            $data_values["item_title"]          = $post_item_title;
            $data_values["item_description"]    = $post_item_description;
            $data_values["item_menu"]           = $post_item_menu;
            $data_values["sortkey"]             = $post_sortkey;
            // exécution de la requête
            if(insertItem($data_values)){
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            $result = getItem();

            $page_view = "item_liste";
        }
        break;

    case "update":

        if(empty($_POST)){
            $result = getItem($get_item_id);

            $post_item_title        = $result[0]["item_title"];
            $post_item_description  = $result[0]["item_description"];
            $post_item_menu         = $result[0]["item_menu"];
            $post_sortkey           = $result[0]["sortkey"];
        }else{
            // récupération / initialisation des données qui transitent via le formulaire
            $post_item_title        = isset($_POST["item_title"])       ? filter_input(INPUT_POST, 'item_title', FILTER_SANITIZE_SPECIAL_CHARS)         : null;
            $post_item_description  = isset($_POST["item_description"]) ? filter_input(INPUT_POST, 'item_description', FILTER_SANITIZE_SPECIAL_CHARS)   : null;
            $post_item_menu         = isset($_POST["item_menu"])        ? filter_input(INPUT_POST, 'item_menu', FILTER_SANITIZE_SPECIAL_CHARS)          : null;
            $post_sortkey           = isset($_POST["sortkey"])          ? filter_input(INPUT_POST, 'sortkey', FILTER_VALIDATE_INT)                      : null;

        }

        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier un item</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput("Contenu du menu", ["type" => "text", "value" => $post_item_menu, "name" => "item_menu", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addInput("Titre de la page", ["type" => "text", "value" => $post_item_title, "name" => "item_title", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addTextarea('Texte de la page', array("name" => "item_description", "class" => "u-full-width"), $post_item_description, true, "twelve columns");
        $input[] = addInput("Position souhaitée dans le menu", ["type" => "number", "value" => $post_sortkey, "name" => "sortkey"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&item_id=".$get_item_id, "post", $input);

        if($show_form != false){

            $page_view = "item_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            $data_values = array();
            $data_values["item_title"]          = $post_item_title;
            $data_values["item_description"]    = $post_item_description;
            $data_values["item_menu"]           = $post_item_menu;
            $data_values["sortkey"]             = $post_sortkey;
            // exécution de la requête
            if(updateItem($get_item_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getItem();

            $page_view = "item_liste";
        }


        break;

    case "showHide":
        if(showHideItem($get_item_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // récupération des item correspondant
        $result = getItem();

        $page_view = "item_liste";

        break;
}

?>