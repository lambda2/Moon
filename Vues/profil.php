
        <div class="span9">
            <div class="user-unit">
                <div class="user-img">
                    <?php echo getGravatar($membre->getEmail(), 200, 'identicon', 'x', true) ?>

                </div>
                <div class="user-infos">
                    <h1><?php echo $membre->getPrenom() . ' ' . $membre->getNom() ?> <small> (<?php echo $membre->getPseudo() ?>)</small></h1>
                    <h2><?php if ($membre->getSite_web_membre() != null) echo '<i class="icon-link"></i> ' . generateLink($membre->getSite_web_membre()); ?></h2>
                    <h3><?php
                    
                    /*echo 'vehicule a des roles ?';
                    var_dump(Access::haveDefinedRoles('vehicule_lw'));
                    echo 'membre a des roles ?';
                    var_dump(Access::haveDefinedRoles('membre_lw'));*/
                    $a = new Access();
                    $a->loadFromTable('vehicule');
                    var_dump($a);
                    $b = new Access();
                    $b->loadFromTable('membre');
                    var_dump($b);
                    //var_dump($membre->getRelationClassInstance('id_membre'));
                    $membre->addRelation('id_pays_membre','countries_lw.id_pays','pays');
                    //var_dump($membre->getLinkedClasses());
                    /*var_dump($membre->getProperName('Id_membres',false,true)) ;
                    var_dump($membre->getNameWithoutId('Id_membre')) ;*/
                    if ($membre->getDept() != null || $membre->getId_pays() != null)
                        echo '<i class="icon-map-marker"></i> ';
                    if ($membre->getDept() != null)
                        echo substr(getNomDepartement($membre->getDept()), 5);
                    if ($membre->getDept() != null && $membre->getId_pays() != null)
                        echo ', ';
                    if ($membre->getId_pays() != null)
                        echo $membre->getPays()->getLangFR();
                    ?>
                    </h3>
                    <a class="button btn pull-right" id="modifierInfoProfil"><i class="icon-pencil"></i> Modifier</a>
                </div>
                <div class="user-edit" style="display:none;">
                    <form class="form-horizontal">
                    <div class="control-group"><div class="controls">
                    <a class="button btn" href="https://fr.gravatar.com/signup"><i class="icon-picture"></i> Modifier votre Gravatar</a>
                    </div></div>
                    <?php
                    echo $membre->getEditable()->generateUpdateForm(false);
                    ?>
                        <div class="control-group"><div class="controls">
                                
                                </div></div>
                    </form>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span4">
                </div><!--/span-->
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details »</a></p>
                </div><!--/span-->
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details »</a></p>
                </div><!--/span-->
            </div><!--/row-->
            <div class="row-fluid">
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details »</a></p>
                </div><!--/span-->
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details »</a></p>
                </div><!--/span-->
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details »</a></p>
                </div><!--/span-->
            </div><!--/row-->

