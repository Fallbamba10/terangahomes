<?php ob_start(); ?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-content">
                    <i class="fas fa-search fa-5x text-white mb-4"></i>
                    <h1 class="display-1 fw-bold text-white">404</h1>
                    <h2 class="text-white mb-4">Page non trouvée</h2>
                    <p class="text-white mb-4">
                        La page que vous cherchez n'existe pas ou a été déplacée.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= APP_URL ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Page non trouvée - TerangaHomes';
include 'views/layouts/app.php';
?>
