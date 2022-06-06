<?php
adminProtection();

include_once("lib/ad.php");
include_once("lib/shape.php");
include_once("lib/designer.php");
include_once("lib/manufacturer.php");
include_once("lib/categorie.php");


$url_page = "admin_ad";

$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action',  FILTER_SANITIZE_SPECIAL_CHARS) : "liste";

$get_id     = isset($_GET["ad_id"])    ? filter_input(INPUT_GET, 'ad_id', FILTER_SANITIZE_NUMBER_INT)      : null;
$get_alpha  = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

switch($get_action){
    case "liste":
       
        $alphabet = range('A', 'Z');

        $result = getAd(0, $get_alpha);
        $page_view = "ad_liste";
        break;

    case "add":
        
        $category = isset($_POST["category"]) ? filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $shape = isset($_POST["shape"]) ? filter_input(INPUT_POST, 'shape', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $designer = isset($_POST["designer"]) ? filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $manufacture = isset($_POST["manufacture"]) ? filter_input(INPUT_POST, 'manufacture', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $nom = isset($_POST["nom"]) ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $breve_description = isset($_POST["breve_description"]) ? filter_input(INPUT_POST, 'breve_description', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $description_complete = isset($_POST["description_complete"]) ? filter_input(INPUT_POST, 'description_complete', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $prix_htva = isset($_POST["prix_htva"]) ? filter_input(INPUT_POST, 'prix_htva', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $prix_livraison = isset($_POST["prix_livraison"]) ? filter_input(INPUT_POST, 'prix_livraison', FILTER_SANITIZE_SPECIAL_CHARS) : null;


        $shape_options = getOptions(getShape(), "shape_id", "shape_title");
        $designer_options = getOptions(getDesigner(), "id", "full_name");
        $category_options = getOptions(getAllLevelCategories(), "category_level_2_id", "full_categories");
        $manufacturer_options = getOptions(getManufacturer(), "id", "manufacturer");
      
        $input = [];
        $input[] = addLayout("<h4>Ajouter un article</h4>");
        $input[] = addSelect("Catégorie associée", ["name" => "category", "class" => "u-full-width"], $category_options, $category, true, "twelve columns");
        $input[] = addSelect("Etat de l'objet", ["name" => "shape", "class" => "u-full-width"], $shape_options, $shape, true, "twelve columns");
        $input[] = addSelect("Designer", ["name" => "designer", "class" => "u-full-width"], $designer_options, $designer, true, "twelve columns");
        $input[] = addSelect("Manufacture", ["name" => "manufacture", "class" => "u-full-width"], $manufacturer_options, $manufacture, true, "twelve columns");
        $input[] = addInput('Nom de l´objet', ["type" => "text", "value" => $nom, "name" => "nom", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addTextarea('Breve description', array("name" => "breve_description", "class" => "u-full-width"), $breve_description, true, "twelve columns");
        $input[] = addTextarea('Description complète', array("name" => "description_complete", "class" => "u-full-width"), $description_complete, true, "twelve columns");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Prix htva', ["type" => "number", "step" => "1", "value" => $prix_htva, "name" => "prix_htva", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prix de la livraison', ["type" => "number", "step" => "1", "value" => $prix_livraison, "name" => "prix_livraison", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        if($show_form != false){
            $page_view = "ad_form";
        }else{


            $data_values = array();
            $data_values["category_level_2_id"] = $category;
            $data_values["shape_id"] = $shape;
            $data_values["designer_id"] = $designer;
            $data_values["manufacturer_id"] = $manufacture;
            $data_values["ad_title"] = $nom;
            $data_values["ad_description"] = $breve_description;
            $data_values["ad_description_detail"] = $description_complete;
            $data_values["price"] = $prix_htva;
            $data_values["price_delivery"] = $prix_livraison;

            if(insertAd($data_values)){
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            $alphabet = range('A', 'Z');
            $result = getAd(0, $get_alpha);
            $page_view = "ad_liste";
        }
        
        
        break;

    case "update":


        $shape_options = getOptions(getShape(), "shape_id", "shape_title");
        $designer_options = getOptions(getDesigner(), "id", "full_name");
        $category_options = getOptions(getAllLevelCategories(), "category_level_2_id", "full_categories");
        $manufacturer_options = getOptions(getManufacturer(), "id", "manufacturer");  

        if (empty($_POST)) {

            $result = getAd($get_id);

            $category = $result[0]["category_level_2_id"];
            $shape = $result[0]["shape_id"];
            $designer = $result[0]["designer_id"];
            $manufacture = $result[0]["manufacturer_id"];
            $nom = $result[0]["ad_title"];
            $breve_description = $result[0]["ad_description"];
            $description_complete = $result[0]["ad_description_detail"];
            $prix_htva = $result[0]["price_htva"];
            $prix_livraison = $result[0]["price_delivery"];
        } else {
            $category = isset($_POST["category"]) ? filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $shape = isset($_POST["shape"]) ? filter_input(INPUT_POST, 'shape', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $designer = isset($_POST["designer"]) ? filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $manufacture = isset($_POST["manufacture"]) ? filter_input(INPUT_POST, 'manufacture', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $nom = isset($_POST["nom"]) ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $breve_description = isset($_POST["breve_description"]) ? filter_input(INPUT_POST, 'breve_description', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $description_complete = isset($_POST["description_complete"]) ? filter_input(INPUT_POST, 'description_complete', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $prix_htva = isset($_POST["prix_htva"]) ? filter_input(INPUT_POST, 'prix_htva', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $prix_livraison = isset($_POST["prix_livraison"]) ? filter_input(INPUT_POST, 'prix_livraison', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        }


        $input = [];
        $input[] = addLayout("<h4>Modifier un article</h4>");      
        $input[] = addSelect("Catégorie associée", ["name" => "category", "class" => "u-full-width"], $category_options, $category, true, "twelve columns");       
        $input[] = addSelect("Etat de l'objet", ["name" => "shape", "class" => "u-full-width"], $shape_options, $shape, true, "twelve columns");                
        $input[] = addSelect("Designer", ["name" => "designer", "class" => "u-full-width"], $designer_options, $designer, true, "twelve columns");       
        $input[] = addSelect("Manufacture", ["name" => "manufacture", "class" => "u-full-width"], $manufacturer_options, $manufacture, true, "twelve columns");        
        $input[] = addInput('Nom de l´objet', ["type" => "text", "value" => $nom, "name" => "nom", "class" => "u-full-width"], true, "twelve columns");       
        $input[] = addTextarea('Breve description', array("name" => "breve_description", "class" => "u-full-width"), $breve_description, true, "twelve columns");
        $input[] = addTextarea('Description complète', array("name" => "description_complete", "class" => "u-full-width"), $description_complete, true, "twelve columns");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Prix htva', ["type" => "number", "step" => "1", "value" => $prix_htva, "name" => "prix_htva", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prix de la livraison', ["type" => "number", "step" => "1", "value" => $prix_livraison, "name" => "prix_livraison", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");

        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&ad_id=" . $get_id, "post", $input);
        
        if($show_form != false){
            $page_view = "ad_form";
        }else{

            $data_values = array();
            $data_values["category_level_2_id"] = $category;
            $data_values["shape_id"] = $shape;
            $data_values["designer_id"] = $designer;
            $data_values["manufacturer_id"] = $manufacture;
            $data_values["ad_title"] = $nom;
            $data_values["ad_description"] = $breve_description;
            $data_values["ad_description_detail"] = $description_complete;
            $data_values["price"] = $prix_htva;
            $data_values["price_delivery"] = $prix_livraison;


            if(updateAd($get_id, $data_values)){
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            
            $alphabet = range('A', 'Z');
            $result = getAd(0, $get_alpha);
            $page_view = "ad_liste";
        }    

    break;

    case "showHide":

        if(showHideAd($get_id)){
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }
        $alphabet = range('A', 'Z');
        $result = getAd(0, $get_alpha);
        $page_view = "ad_liste";
    break;    
}

?>