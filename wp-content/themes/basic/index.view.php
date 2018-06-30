<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="header__logo">E-budżet.pl</h1>
            <h2 class="header__intro">Aby korzystać z aplikacji musisz posiadać konto i być zalogowanym</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php wp_login_form(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a class="btn btn-info btn-lg btn-block" href="<?php echo $registerLink; ?>">Zarejestruj się</a>
        </div>
    </div>
</div>
