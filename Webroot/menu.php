
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
    <div class="row-fluid hidden-desktop">
        <div class="brand center-all">

            <div class="logo-top" id="logo-banner-0">
                <img src="Assets/images/logo.png" alt="logo image"/>
                <div class="logo-content">
                    <h1>lambdaweb</h1>
                    <h2>Compagnie de l'imaginaire</h2>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="container-fluid main-content">
    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                </ul>
            </div><!--/.well -->
        </div><!--/span-->
        
        <div class="span9">

