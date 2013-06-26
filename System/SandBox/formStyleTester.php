<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
try 
{
    ?>

<form action="/lambdawebv3/world/index.php?moon-action=insert&amp;target=project&amp;formName=insert-project" method="post" data-align="vertical" data-type="smart-input" name="insert-project"><label for="nom">Nom du projet</label><input type="text" placeholder="Nom du projet" name="nom" id="nom" required=""><label for="description">Description du projet</label><textarea placeholder="Description du projet" name="description" id="description" rows="2" required="" style="margin: 4px 1183.11px 4px 4px; width: 1643px; height: 224px;"></textarea><label for="id_equipe">Equipe assignée</label><select type="select" placeholder="Equipe assignée" name="id_equipe" id="id_equipe" style="display: none;"><option value="">Aucun</option><option value="1">Lambdaweb Team</option><option value="27" selected="selected">Mammuth</option><option value="new" data-toggle="insert-equipe-form">Nouveau</option></select><div class="minict_wrapper"><input type="text" value="Aucun" placeholder="Mammuth"><ul style="display: none;"><li data-value="" class="undefined minict_first">Aucun</li><li data-value="1" class="undefined">Lambdaweb Team</li><li data-value="27" class="undefined selected">Mammuth</li><li data-value="new" class="undefined minict_last">Nouveau</li><li class="minict_empty" style="display: none;">No results match your keyword.</li></ul></div><label for="type_projet">Type de projet</label><select type="select" placeholder="Type de projet" name="type_projet" id="type_projet" style="display: none;"><option value="">Aucun</option><option value="1" selected="selected">Site internet</option><option value="2">Framework</option><option value="3">Application web</option><option value="4">Application Desktop</option><option value="5">Bibliothèque</option></select><div class="minict_wrapper"><input type="text" value="Aucun" placeholder="Site internet"><ul style="display: none;"><li data-value="" class="undefined minict_first">Aucun</li><li data-value="1" class="undefined selected">Site internet</li><li data-value="2" class="undefined">Framework</li><li data-value="3" class="undefined">Application web</li><li data-value="4" class="undefined">Application Desktop</li><li data-value="5" class="undefined minict_last">Bibliothèque</li><li class="minict_empty" style="display: none;">No results match your keyword.</li></ul></div><label for="public">Le projet est il public ?</label><input type="checkbox" placeholder="Le projet est il public ?" name="public" id="public" class="red blue yellow "><label for="downloadLink">Lien de téléchargement</label><input type="text" placeholder="Lien de téléchargement" name="downloadLink" id="downloadLink"><label for="isPartnership">Partenariat</label><input type="checkbox" placeholder="Partenariat" name="isPartnership" id="isPartnership"><input type="hidden" value="Profile" name="moon-context"><button type="submit" name="submit-insert-project">valider</button></form>

    <?php
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
