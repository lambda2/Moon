
<div class="page-header">
    <h1>Gestion des trajets</h1>
<!--    <a class="button btn" data-toggle-target="insertTrajet" data-after="Retour">
    Ajouter
    </a>-->
</div>

<div class="user-unit" id="insertTrajet">
    <p class="gris-moyen">Ajouter un nouveau trajet :</p>
    <?php
    $trajet = new Trajet($GLOBALS['bdd']);
    $editable = $trajet->generateEditable();
    echo $editable->generateInsertForm(true, false, "trajetInfo");
    ?>
</div>
<div class="user-unit" id="insertTrajet-i">
    <p class="gris-moyen">Récapitulatifs des derniers trajets :</p>
    <?php
    $trajets = Trajet::getAllTrajets($GLOBALS['bdd']);
    if (count($trajets) <= 0)
        echo '<h2>Aucun trajet</h2>';
    else {
        ?>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>De</th>
                    <th>Vers</th>
                    <th>Distance</th>
                    <th>Coût</th>
                    <th>Vehicule</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tot = 0;
                $dist = 0;
                foreach ($trajets as $tra) {
                    
                    $prix = floatval($tra->distance_trajet / 100) * floatval($tra->consommation_vehicule) * 1.3;
                    $tot += $prix;
                    $dist += $tra->distance_trajet;
                    ?>

                    <tr>
                        <td><?php echo getCoolDate($tra->date_trajet); ?></td>
                        <td><?php echo $tra->nom_origine; ?></td>
                        <td><?php echo $tra->nom_destination; ?></td>
                        <td><?php echo $tra->distance_trajet; ?> Km</td>
                        <td><?php echo $prix ?> €</td>
                        <td><?php echo $tra->nom_vehicule; ?></td>
                    </tr>

                <?php }
                ?>
                    <tr class="info">
                        <td colspan="3">Total :</td>
                        <td><?php echo $dist ?> Km</td>
                        <td colspan="2"><?php echo $tot ?> €</td>
                    </tr>
            </tbody>
        </table>
       <?php  }
    ?>
</div>

