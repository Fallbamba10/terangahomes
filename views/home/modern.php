<?php ob_start(); ?>

<!-- Hero Section Moderne -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-3 fw-bold mb-4">
                        Trouvez votre espace de vie idéal
                    </h1>
                    <p class="lead mb-5 fs-4">
                        Découvrez notre sélection exclusive de biens immobiliers, hôtels et véhicules. 
                        Votre prochain chez-vous n'a jamais été aussi proche.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="<?= APP_URL ?>/annonces" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search me-2"></i>Explorer
                        </a>
                        <a href="<?= APP_URL ?>/register" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-plus me-2"></i>Déposer une annonce
                        </a>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <span>100% Vérifié</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-primary me-3 fs-4"></i>
                                <span>Paiement Sécurisé</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-headset text-info me-3 fs-4"></i>
                                <span>Support 24/7</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bolt text-warning me-3 fs-4"></i>
                                <span>Service Rapide</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <div class="position-relative">
                        <div class="bg-white rounded-4 shadow-lg p-4" style="transform: perspective(1000px) rotateY(-5deg);">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <i class="fas fa-home text-primary fs-2 mb-2 d-block"></i>
                                        <small class="d-block">Appartements</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <i class="fas fa-building text-success fs-2 mb-2 d-block"></i>
                                        <small class="d-block">Maisons</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <i class="fas fa-hotel text-warning fs-2 mb-2 d-block"></i>
                                        <small class="d-block">Hôtels</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <i class="fas fa-car text-info fs-2 mb-2 d-block"></i>
                                        <small class="d-block">Voitures</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section Moderne -->
<section class="container">
    <div class="search-section">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Recherchez votre bien idéal</h2>
            <p class="text-muted">Plus de 500 annonces vous attendent</p>
        </div>
        <form method="GET" action="<?= APP_URL ?>/search" class="row g-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Type de bien</label>
                <div class="form-select-wrapper">
                    <select class="form-select form-select-lg" name="type">
                        <option value="">Tous les types</option>
                        <option value="location">🏠 Location</option>
                        <option value="vente">🏡 Vente</option>
                        <option value="hotel">🏨 Hôtel</option>
                        <option value="voiture">🚗 Voiture</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Ville</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                    </span>
                    <input type="text" class="form-control form-control-lg border-start-0" name="ville" 
                           placeholder="Dakar, Thiès...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Budget min</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-coins text-success"></i>
                    </span>
                    <input type="number" class="form-control form-control-lg border-start-0" name="prix_min" placeholder="0">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Budget max</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-coins text-warning"></i>
                    </span>
                    <input type="number" class="form-control form-control-lg border-start-0" name="prix_max" placeholder="∞">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">TerangaHomes en chiffres</h2>
            <p class="text-muted">La confiance de milliers d'utilisateurs</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_annonces'] ?>">0</div>
                    <div class="text-muted fw-semibold mt-2">Annonces actives</div>
                    <div class="text-success small mt-1">
                        <i class="fas fa-arrow-up me-1"></i>+12% ce mois
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_users'] ?>">0</div>
                    <div class="text-muted fw-semibold mt-2">Utilisateurs</div>
                    <div class="text-success small mt-1">
                        <i class="fas fa-arrow-up me-1"></i>+25% ce mois
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="stat-number" data-count="<?= $stats['total_categories'] ?>">0</div>
                    <div class="text-muted fw-semibold mt-2">Catégories</div>
                    <div class="text-info small mt-1">
                        <i class="fas fa-plus me-1"></i>Nouvelles ajoutées
                    </div>
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
            <h2 class="fw-bold mb-3">Annonces en vedette</h2>
            <p class="text-muted">Notre sélection des meilleures offres</p>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredAnnonces as $annonce): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card annonce-card h-100 fade-in">
                    <div class="annonce-badge">
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                    </div>
                    <div class="annonce-favorite" onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                        <i class="far fa-heart fs-5"></i>
                    </div>
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($annonce['titre']) ?></h5>
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <?= ucfirst($annonce['type']) ?>
                            </span>
                        </div>
                        <p class="annonce-location text-muted mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($annonce['ville']) ?>
                        </p>
                        <p class="card-text text-muted small mb-4">
                            <?= substr(htmlspecialchars($annonce['description']), 0, 120) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="annonce-price">
                                <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                <?php if ($annonce['type'] === 'location'): ?>
                                    <small class="text-muted">/mois</small>
                                <?php endif; ?>
                            </div>
                            <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" 
                               class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= APP_URL ?>/annonces" class="btn btn-primary btn-lg rounded-pill px-5">
                <i class="fas fa-th me-2"></i>Voir toutes les annonces
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Pourquoi choisir TerangaHomes ?</h2>
            <p class="text-muted">Les avantages qui font la différence</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Sécurité garantie</h5>
                    <p class="text-muted">
                        Transactions sécurisées et vérification complète de toutes les annonces. 
                        Votre tranquillité d'esprit est notre priorité.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Recherche intelligente</h5>
                    <p class="text-muted">
                        Filtres avancés et géolocalisation précise pour trouver exactement 
                        ce que vous cherchez en quelques clics.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Communication directe</h5>
                    <p class="text-muted">
                        Chat intégré pour communiquer instantanément avec les propriétaires. 
                        Plus d'intermédiaires, plus de simplicité.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Prêt à trouver votre prochain chez-vous ?</h2>
        <p class="lead mb-5">
            Rejoignez des milliers d'utilisateurs satisfaits et commencez votre recherche aujourd'hui.
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="<?= APP_URL ?>/annonces" class="btn btn-light btn-lg rounded-pill px-5">
                <i class="fas fa-search me-2"></i>Commencer la recherche
            </a>
            <a href="<?= APP_URL ?>/register" class="btn btn-outline-light btn-lg rounded-pill px-5">
                <i class="fas fa-user-plus me-2"></i>S'inscrire gratuitement
            </a>
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
            element.textContent = Math.floor(progress * (end - start) + start).toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const endValue = parseInt(element.getAttribute('data-count'));
                animateValue(element, 0, endValue, 2000);
                observer.unobserve(element);
            }
        });
    });
    
    statNumbers.forEach(element => {
        observer.observe(element);
    });
});

// Toggle favorite (placeholder)
function toggleFavorite(element, annonceId) {
    const icon = element.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        element.classList.add('active');
        // Animation
        element.style.transform = 'scale(1.2)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        element.classList.remove('active');
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'TerangaHomes - Plateforme Immobilière Moderne';
include 'views/layouts/modern.php';
?>
