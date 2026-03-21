<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1>Toutes nos annonces</h1>
                    <p class="lead">Découvrez notre sélection complète de biens immobiliers, hôtels et véhicules</p>
                </div>
            </div>
            <div class="col-lg-6">
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
                    <option value="location" <?= $filters['type'] === 'location' ? 'selected' : '' ?>>🏠 Location</option>
                    <option value="vente" <?= $filters['type'] === 'vente' ? 'selected' : '' ?>>🏡 Vente</option>
                    <option value="hotel" <?= $filters['type'] === 'hotel' ? 'selected' : '' ?>>🏨 Hôtel</option>
                    <option value="voiture" <?= $filters['type'] === 'voiture' ? 'selected' : '' ?>>🚗 Voiture</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $filters['categorie_id'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Prix min</label>
                <input type="number" class="form-control" name="prix_min" value="<?= $filters['prix_min'] ?>" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Prix max</label>
                <input type="number" class="form-control" name="prix_max" value="<?= $filters['prix_max'] ?>" placeholder="∞">
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
            <?= $pagination['total'] ?> annonce<?= $pagination['total'] > 1 ? 's' : '' ?> trouvée<?= $pagination['total'] > 1 ? 's' : '' ?>
            <?php if (!empty($filters['type']) || !empty($filters['ville'])): ?>
                <small class="text-muted">(filtré)</small>
            <?php endif; ?>
        </h3>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;">
                <option>Trier par</option>
                <option>Prix croissant</option>
                <option>Prix décroissant</option>
                <option>Récent</option>
                <option>Ancien</option>
            </select>
        </div>
    </div>
    
    <?php if (empty($annonces)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4>Aucune annonce trouvée</h4>
            <p class="text-muted">Essayez de modifier vos filtres de recherche</p>
            <a href="<?= APP_URL ?>/annonces" class="btn btn-outline-primary">Réinitialiser les filtres</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card annonce-card">
                    <div class="annonce-badge">
                        <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
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
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <nav aria-label="Pagination des annonces" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?><?= $this->buildQueryString($filters) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $start = max(1, $pagination['current_page'] - 2);
                $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $this->buildQueryString($filters) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?><?= $this->buildQueryString($filters) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php
function buildQueryString($filters) {
    $query = [];
    foreach ($filters as $key => $value) {
        if (!empty($value)) {
            $query[] = $key . '=' . urlencode($value);
        }
    }
    return empty($query) ? '' : '&' . implode('&', $query);
}
?>

<script>
function toggleFavorite(element, annonceId) {
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
$title = 'Toutes les annonces - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
