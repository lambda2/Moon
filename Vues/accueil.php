<div class="row-fluid">

    <!--<div class="span12 main-head"></div>-->
</div>

<div class="row-fluid">

    <?php
    dbg("Est on sur que la classe Vehicule existe ?");
    $tarjet->getEditable()
            ->getFieldByName('id_vehicule')
            ->bindFieldTo('vehicule_lw.id_vehicule', 'nom_vehicule');
    echo $tarjet->getEditable()->generateInsertForm();
    ?>

</div>







<hr>