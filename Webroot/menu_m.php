<div class="navbar navbar-static-top lambdanav">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
            </a>

            <div class="nav-collapse collapse">                

                <ul class="nav nav-pills">
                    <li class="active" data-move="dynamic"><a href="index.php">Accueil</a></li>
                </ul>

                

            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="container-fluid main-head">
    <?php if (isset($_GET['err'])): ?>
    <div id="error-aera"  class="row-fluid">
        <div class="span2">
        </div>
        <div class="span8">
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo getError($_GET['err']); ?>
            </div>
        </div>
        <div class="span2">
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['stat'])): ?>
    <div id="status-aera" class="row-fluid">
        <div class="span2">
        </div>
        <div class="span8">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo getStatut($_GET['stat']); ?>
            </div>
        </div>
        <div class="span2">
        </div>
    </div>
<?php endif; ?>

</div>
<div class="container main-content">
    <div class="row">
        <div class="span2"><!--span3-->
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                    <?php echo Page::getMenu(Core::getInstance()->bdd()); ?>
                </ul>
            </div>
        </div><!--/span3-->
        
        <div class="span10">

