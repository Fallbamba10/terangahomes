<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Toutes nos annonces</h1>
                    <p class="lead">Découvrez notre sélection complète de biens immobiliers, hôtels et véhicules</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <i class="fas fa-search" style="font-size: 150px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="container">
    <div class="search-section">
        <h3>Filtrer les annonces</h3>
        <form method="GET" action="<?= APP_URL ?>/annonces" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type de bien</label>
                <select class="form-select" name="type">
                    <option value="">Tous les types</option>
                    <option value="location" <?= ($_GET['type'] ?? '') === 'location' ? 'selected' : '' ?>>🏠 Location</option>
                    <option value="vente" <?= ($_GET['type'] ?? '') === 'vente' ? 'selected' : '' ?>>🏡 Vente</option>
                    <option value="hotel" <?= ($_GET['type'] ?? '') === 'hotel' ? 'selected' : '' ?>>🏨 Hôtel</option>
                    <option value="voiture" <?= ($_GET['type'] ?? '') === 'voiture' ? 'selected' : '' ?>>🚗 Voiture</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories ?? [] as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($_GET['categorie_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Prix min</label>
                <input type="number" class="form-control" name="prix_min" value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Prix max</label>
                <input type="number" class="form-control" name="prix_max" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" placeholder="∞">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Results Section -->
<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            <?= count($annonces ?? []) ?> annonce<?= count($annonces ?? []) > 1 ? 's' : '' ?> trouvée<?= count($annonces ?? []) > 1 ? 's' : '' ?>
        </h3>
    </div>
    
    <?php if (empty($annonces)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4>Aucune annonce trouvée</h4>
            <p class="text-muted">Essayez de modifier vos filtres de recherche</p>
            <div class="mt-3">
                <a href="<?= APP_URL ?>/annonces" class="btn btn-outline-primary me-2">
                    <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
                </a>
                <a href="<?= APP_URL ?>/seed_data_fixed.php" class="btn btn-primary">
                    <i class="fas fa-database me-2"></i>Ajouter des données d'exemple
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card annonce-card">
                    <div class="annonce-badge">
                        <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                        <?php if ($annonce['featured']): ?>
                            <span class="badge bg-warning ms-1">Featured</span>
                        <?php endif; ?>
                    </div>
                    <div class="annonce-favorite" onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                        <i class="far fa-heart"></i>
                    </div>
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                        <p class="annonce-location">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                            <?php if (!empty($annonce['quartier'])): ?>
                                - <?= htmlspecialchars($annonce['quartier']) ?>
                            <?php endif; ?>
                        </p>
                        
                        <?php if ($annonce['categorie_nom']): ?>
                            <span class="badge bg-secondary small"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <?php if ($annonce['superficie']): ?>
                                <span class="text-muted small me-3">
                                    <i class="fas fa-expand me-1"></i><?= $annonce['superficie'] ?>m²
                                </span>
                            <?php endif; ?>
                            <?php if ($annonce['chambres'] > 0): ?>
                                <span class="text-muted small me-3">
                                    <i class="fas fa-bed me-1"></i><?= $annonce['chambres'] ?> chambres
                                </span>
                            <?php endif; ?>
                            <?php if ($annonce['parking']): ?>
                                <span class="text-muted small">
                                    <i class="fas fa-car me-1"></i>Parking
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="annonce-price">
                                <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                <?php if ($annonce['type'] === 'location'): ?>
                                    <small class="text-muted">/mois</small>
                                <?php endif; ?>
                            </div>
                            <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" class="btn btn-outline-primary btn-sm">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
$title = 'Toutes les annonces - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
