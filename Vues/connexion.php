<!-- fenetre de connexion -->
    <div id="login">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h1 id="myModalLabel"><?php echo $login_title; ?></h1>
        </div>
        <form>
        <div class="modal-body">
            <div id="error-login" class="alert"></div>
            <div id="succes-login" class="alert alert-success"></div>
            <div id="login-aera">
                <input type="text" class="form-login" id="inputEmail" placeholder="Login">
                <input type="password" class="form-login" id="inputPassword" placeholder="Pass">
            </div>
            <div id="loading-login-aera">
                <div class="progress progress-striped active">
                    <div id="loginspbar" class="bar" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="connectMainButton" class="btn button">Connexion</button>
        </div>
        </form>
    </div>
