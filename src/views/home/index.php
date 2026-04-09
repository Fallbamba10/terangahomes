<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4 fade-in">
                        Trouvez votre chez-vous avec TerangaHomes
                    </h1>
                    <p class="lead mb-4 fade-in">
                        La plateforme N°1 pour la location, la vente et la réservation d'hébergements et véhicules au Sénégal
                    </p>
                    <div class="d-flex gap-3 fade-in">
                        <a href="<?= APP_URL ?>/annonces" class="btn btn-light btn-lg">
                            <i class="fas fa-search me-2"></i>Explorer les annonces
                        </a>
                        <a href="<?= APP_URL ?>/register" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Déposer une annonce
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <i class="fas fa-home" style="font-size: 300px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Recherchez votre bien idéal</h3>
                <form method="GET" action="<?= APP_URL ?>/search" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type de bien</label>
                        <select class="form-select" name="type">
                            <option value="">Tous les types</option>
                            <option value="location">Location</option>
                            <option value="vente">Vente</option>
                            <option value="hotel">Hôtel</option>
                            <option value="voiture">Voiture</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ville</label>
                        <input type="text" class="form-control" name="ville" placeholder="Dakar, Thiès...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix min</label>
                        <input type="number" class="form-control" name="prix_min" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix max</label>
                        <input type="number" class="form-control" name="prix_max" placeholder="∞">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_annonces'] ?>">0</div>
                    <div class="text-muted">Annonces actives</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_users'] ?>">0</div>
                    <div class="text-muted">Utilisateurs</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_categories'] ?>">0</div>
                    <div class="text-muted">Catégories</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Annonces -->
<?php if (!empty($featuredAnnonces)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Annonces en vedette</h2>
            <p class="text-muted">Découvrez nos meilleures offres</p>
        </div>
        <div class="row">
            <?php foreach ($featuredAnnonces as $annonce): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card annonce-card h-100">
                    <div class="annonce-badge">
                        <span class="badge bg-danger">Featured</span>
                    </div>
                    <div class="annonce-favorite" onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                        <i class="far fa-heart"></i>
                    </div>
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                            <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                        </div>
                        <p class="annonce-location mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                        </p>
                        <p class="card-text text-muted small">
                            <?= substr(htmlspecialchars($annonce['description']), 0, 100) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="annonce-price">
                                <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                <?php if ($annonce['type'] === 'location'): ?>/mois<?php endif; ?>
                            </div>
                            <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" 
                               class="btn btn-outline-primary btn-sm">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Annonces -->
<?php if (!empty($latestAnnonces)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Dernières annonces</h2>
            <p class="text-muted">Les nouveautés de la plateforme</p>
        </div>
        <div class="row">
            <?php foreach ($latestAnnonces as $annonce): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card annonce-card h-100">
                    <div class="annonce-favorite" onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                        <i class="far fa-heart"></i>
                    </div>
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h6>
                        <p class="annonce-location small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="annonce-price h6 mb-0">
                                <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                            </div>
                            <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= APP_URL ?>/annonces" class="btn btn-primary btn-lg">
                <i class="fas fa-th me-2"></i>Voir toutes les annonces
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Pourquoi choisir TerangaHomes ?</h2>
            <p class="text-muted">Les avantages de notre plateforme</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Sécurité garantie</h5>
                    <p class="text-muted">Transactions sécurisées et vérification des annonces</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-search-location fa-3x text-primary"></i>
                    </div>
                    <h5>Recherche avancée</h5>
                    <p class="text-muted">Filtres précis pour trouver exactement ce que vous cherchez</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-comments fa-3x text-primary"></i>
                    </div>
                    <h5>Communication directe</h5>
                    <p class="text-muted">Chat intégré pour communiquer directement avec les propriétaires</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Animation des statistiques
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };
    
    statNumbers.forEach(element => {
        const endValue = parseInt(element.getAttribute('data-count'));
        animateValue(element, 0, endValue, 2000);
    });
});

// Toggle favorite (placeholder)
function toggleFavorite(element, annonceId) {
    if (!<?= $this->isLoggedIn() ? 'true' : 'false' ?>) {
        window.location.href = '<?= APP_URL ?>/login';
        return;
    }
    
    const icon = element.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        element.classList.add('active');
        // TODO: Appel AJAX pour ajouter aux favoris
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        element.classList.remove('active');
        // TODO: Appel AJAX pour retirer des favoris
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'TerangaHomes - Plateforme Immobilière';
include 'views/layouts/app.php';
?>
