<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1>Trouvez votre logement idéal au Sénégal</h1>
                    <p class="lead">Plus de 500 annonces de location, vente et réservation dans tout le pays</p>
                    <div class="d-flex gap-3">
                        <a href="<?= APP_URL ?>/annonces" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </a>
                        <a href="<?= APP_URL ?>/register" class="btn btn-secondary btn-lg">
                            <i class="fas fa-plus me-2"></i>Déposer une annonce
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-home" style="font-size: 200px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="container">
    <div class="search-section">
        <h3>Recherchez votre bien idéal</h3>
        <form method="GET" action="<?= APP_URL ?>/search" class="row g-3">
            <div class="col-md-3">
                <select class="form-select" name="type">
                    <option value="">Tous les types</option>
                    <option value="location">🏠 Location</option>
                    <option value="vente">🏡 Vente</option>
                    <option value="hotel">🏨 Hôtel</option>
                    <option value="voiture">🚗 Voiture</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="ville" placeholder="Ville">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="prix_min" placeholder="Prix min">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="prix_max" placeholder="Prix max">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>OK
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_annonces'] ?></div>
                    <div class="stat-label">Annonces</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_users'] ?></div>
                    <div class="stat-label">Utilisateurs</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_categories'] ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Annonces -->
<?php if (!empty($featuredAnnonces)): ?>
<section class="container py-5">
    <h3 class="mb-4">Annonces en vedette</h3>
    <div class="row">
        <?php foreach ($featuredAnnonces as $annonce): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card annonce-card">
                <div class="annonce-badge">
                    <span class="badge bg-warning">Featured</span>
                </div>
                <div class="annonce-favorite" onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                    <i class="far fa-heart"></i>
                </div>
                <?php 
                $images = json_decode($annonce['images'] ?? '[]', true);
                $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                ?>
                <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                    <p class="annonce-location">
                        <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                    </p>
                    <div class="annonce-price">
                        <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                        <?php if ($annonce['type'] === 'location'): ?>/mois<?php endif; ?>
                    </div>
                    <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" class="btn btn-outline-primary btn-sm mt-2">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h3>Pourquoi TerangaHomes ?</h3>
            <p class="text-muted">Les avantages de notre plateforme</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="feature-title">Sécurité</h5>
                    <p class="feature-description">Transactions sécurisées et vérification des annonces</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <h5 class="feature-title">Recherche avancée</h5>
                    <p class="feature-description">Filtres précis pour trouver exactement ce que vous cherchez</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="feature-title">Communication</h5>
                    <p class="feature-description">Chat intégré pour communiquer directement avec les propriétaires</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFavorite(element, annonceId) {
    const icon = element.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        element.classList.add('active');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        element.classList.remove('active');
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'TerangaHomes - Plateforme Immobilière';
include __DIR__ . '/../layouts/seloger.php';
?>
