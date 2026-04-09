<?php ob_start(); ?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-content">
                    <i class="fas fa-lock fa-5x text-white mb-4"></i>
                    <h1 class="display-1 fw-bold text-white">403</h1>
                    <h2 class="text-white mb-4">Accès interdit</h2>
                    <p class="text-white mb-4">
                        Vous n'avez pas les permissions nécessaires pour accéder à cette page.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= APP_URL ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <a href="<?= APP_URL ?>/login" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Accès interdit - TerangaHomes';
include 'views/layouts/app.php';
?>
