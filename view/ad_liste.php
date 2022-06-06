<div class="row">
    <div class="six columns">
        <?php
        echo "<h5>Recherche alphabétique :</h5>";
        echo "<p>";
        foreach($alphabet as $lettre){
            echo "<a href='index.php?p=".$url_page."&alpha=".$lettre."' class='bt-action'>".$lettre."</a> ";
        }
        echo "</p>";
        ?>
    </div>
    <div class="six columns">
        <form action="index.php?p=<?php echo $url_page; ?>" method="get" id="search">

            <div>
                <input type="hidden" name="p" value="<?php echo $url_page; ?>" />
                <input type="text" id="quicherchez_vous" name="alpha" value="" placeholder="Tapez votre recherche ici" />
                <input type="submit" value="trouver" />
                <a href="index.php?p=<?php echo $url_page; ?>&action=add" class="button"><i class="fas fa-user-plus"></i> Ajouter</a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="four columns">
        <?php if (is_array($result)) {
            foreach ($result as $r) {
                $ad_id = $r['id'];
                $ad_title = $r['title'];
                $is_visible = $r['is_visible'];
                $category = $r['category'];
                $manufacturer = $r['manufacturer'];
                $designer = $r['fullName'];
                if ($is_visible == '1') {
                    $txt_nom = $ad_title;
                    $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
                    $txt_title = 'Masquer cette entrée';
                } else {
                    $txt_nom =
                        "<s style='color:#b1b1b1;'>" . $ad_title . '</s>';
                    $txt_visible = "<i class=\"fas fa-eye\"></i>";
                    $txt_title = 'Réactiver cette entrée';
                }

                echo "<p>
                        <a href='index.php?p=" .
                    $url_page .
                    '&ad_id=' .
                    $ad_id .
                    "&action=update' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                        <a href='index.php?p=" .
                    $url_page .
                    '&ad_id=' .
                    $ad_id .
                    "&action=showHide' title='" .
                    $txt_title .
                    "' class='bt-action'>" .
                    $txt_visible .
                    "</a> 
						" .
                    $txt_nom .
                    " 
                        </br>
                        Categorie : " .
                    $category .
                    "
                        <br>
                        Designer : " .
                    $designer .
                    "
                        <br>
                        Manufacturer :  " .
                    $manufacturer .
                    "
                    </p>";
            }
        } else {
            echo '<p>Aucun résultat pour la lettre ' . $get_alpha . '</p>';
        } ?>
    </div>
    <div class="eight columns">
        <?php
        echo isset($msg) && !empty($msg)
            ? "<div class='missingfield $msg_class'>" . $msg . '</div>'
            : '';

        if (isset($show_description) && $show_description) { ?>
            <h1><?php echo $detail_nom . ' ' . $detail_prenom; ?></h1>
            <?php echo '<p>' . $detail_description . '</p>';}
        ?>
    </div>
</div>