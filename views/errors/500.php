<?php ob_start(); ?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-content">
                    <i class="fas fa-exclamation-triangle fa-5x text-white mb-4"></i>
                    <h1 class="display-1 fw-bold text-white">500</h1>
                    <h2 class="text-white mb-4">Erreur serveur</h2>
                    <p class="text-white mb-4">
                        Une erreur technique est survenue. Nos équipes travaillent pour la résoudre.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= APP_URL ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sync me-2"></i>Réessayer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Erreur serveur - TerangaHomes';
include 'views/layouts/app.php';
?>
