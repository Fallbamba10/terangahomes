<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Recherche avancée</h1>
                    <p class="lead">Trouvez exactement ce que vous cherchez avec nos filtres précis</p>
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

<!-- Advanced Search Section -->
<section class="container py-4">
    <div class="search-section">
        <h3 class="mb-4">
            <i class="fas fa-filter me-2"></i>Filtres de recherche
        </h3>
        <form method="GET" action="<?= APP_URL ?>/search" class="row g-3">
            <div class="col-12">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" name="q" 
                           value="<?= htmlspecialchars($query) ?>"
                           placeholder="Rechercher par titre, description, ville...">
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Type de bien</label>
                <select class="form-select" name="type">
                    <option value="">Tous les types</option>
                    <option value="location" <?= $filters['type'] === 'location' ? 'selected' : '' ?>>
                        🏠 Location
                    </option>
                    <option value="vente" <?= $filters['type'] === 'vente' ? 'selected' : '' ?>>
                        🏡 Vente
                    </option>
                    <option value="hotel" <?= $filters['type'] === 'hotel' ? 'selected' : '' ?>>
                        🏨 Hôtel
                    </option>
                    <option value="voiture" <?= $filters['type'] === 'voiture' ? 'selected' : '' ?>>
                        🚗 Voiture
                    </option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" 
                                <?= $filters['categorie_id'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Prix min</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="prix_min" 
                           value="<?= $filters['prix_min'] ?>" placeholder="0">
                    <span class="input-group-text">FCFA</span>
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Prix max</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="prix_max" 
                           value="<?= $filters['prix_max'] ?>" placeholder="∞">
                    <span class="input-group-text">FCFA</span>
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Results Section -->
<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            <?php if (!empty($query)): ?>
                Résultats pour "<?= htmlspecialchars($query) ?>"
            <?php endif; ?>
            : <?= $pagination['total'] ?> annonce<?= $pagination['total'] > 1 ? 's' : '' ?> trouvée<?= $pagination['total'] > 1 ? 's' : '' ?>
        </h3>
        <div class="d-flex gap-2 align-items-center">
            <label class="form-label mb-0 me-2">Trier par:</label>
            <select class="form-select form-select-sm" style="width: auto;" id="sortSelect">
                <option value="recent" <?= ($filters['sort'] ?? 'recent') === 'recent' ? 'selected' : '' ?>>
                    Plus récent
                </option>
                <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>
                    Prix croissant
                </option>
                <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>
                    Prix décroissant
                </option>
                <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>
                    Plus populaire
                </option>
            </select>
        </div>
    </div>
    
    <?php if (empty($annonces)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4>Aucun résultat trouvé</h4>
            <p class="text-muted">
                <?php if (!empty($query)): ?>
                    Aucune annonce ne correspond à votre recherche "<?= htmlspecialchars($query) ?>"
                <?php else: ?>
                    Essayez de modifier vos critères de recherche
                <?php endif; ?>
            </p>
            <div class="mt-3">
                <a href="<?= APP_URL ?>/search" class="btn btn-outline-primary me-2">
                    <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
                </a>
                <a href="<?= APP_URL ?>/annonces" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>Voir toutes les annonces
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Search Summary -->
        <div class="alert alert-info mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-info-circle me-2"></i>
                    <?php if (!empty($query)): ?>
                        Recherche pour "<?= htmlspecialchars($query) ?>"
                    <?php endif; ?>
                    <?php if (!empty($filters['type'])): ?>
                        • Type: <?= ucfirst($filters['type']) ?>
                    <?php endif; ?>
                    <?php if (!empty($filters['prix_min'])): ?>
                        • Prix min: <?= number_format($filters['prix_min'], 0, '.', ' ') ?> FCFA
                    <?php endif; ?>
                    <?php if (!empty($filters['prix_max'])): ?>
                        • Prix max: <?= number_format($filters['prix_max'], 0, '.', ' ') ?> FCFA
                    <?php endif; ?>
                </div>
                <a href="<?= APP_URL ?>/search" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Effacer les filtres
                </a>
            </div>
        </div>
        
        <!-- Results Grid -->
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
        <nav aria-label="Pagination des résultats" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?><?= $this->buildQueryString() ?>">
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
                        <a class="page-link" href="?page=<?= $i ?><?= $this->buildQueryString() ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?><?= $this->buildQueryString() ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<!-- Quick Search Suggestions -->
<section class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-fire me-2"></i>Recherches populaires
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= APP_URL ?>/search?q=appartement+dakar" class="badge bg-light text-dark p-2">
                            Appartement Dakar
                        </a>
                        <a href="<?= APP_URL ?>/search?q=maison+luxueuse" class="badge bg-light text-dark p-2">
                            Maison luxueuse
                        </a>
                        <a href="<?= APP_URL ?>/search?q=studio+meuble" class="badge bg-light text-dark p-2">
                            Studio meublé
                        </a>
                        <a href="<?= APP_URL ?>/search?q=villa+piscine" class="badge bg-light text-dark p-2">
                            Villa piscine
                        </a>
                        <a href="<?= APP_URL ?>/search?q=hotel+almadies" class="badge bg-light text-dark p-2">
                            Hôtel Almadies
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-map me-2"></i>Recherches par ville
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= APP_URL ?>/search?q=dakar" class="badge bg-light text-dark p-2">
                            Dakar
                        </a>
                        <a href="<?= APP_URL ?>/search?q=thies" class="badge bg-light text-dark p-2">
                            Thiès
                        </a>
                        <a href="<?= APP_URL ?>/search?q=kaolack" class="badge bg-light text-dark p-2">
                            Kaolack
                        </a>
                        <a href="<?= APP_URL ?>/search?q=saint-louis" class="badge bg-light text-dark p-2">
                            Saint-Louis
                        </a>
                        <a href="<?= APP_URL ?>/search?q=touba" class="badge bg-light text-dark p-2">
                            Touba
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
function buildQueryString() {
    $query = [];
    $filters = $GLOBALS['filters'];
    $queryText = $GLOBALS['query'];
    
    if (!empty($queryText)) {
        $query[] = 'q=' . urlencode($queryText);
    }
    
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

// Auto-redirect on sort change
document.getElementById('sortSelect').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>

<?php
$content = ob_get_clean();
$title = 'Recherche - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
