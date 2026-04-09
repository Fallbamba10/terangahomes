<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Mes favoris</h1>
                    <p class="lead"><?= $count ?> annonce<?= $count > 1 ? 's' : '' ?> sauvegardée<?= $count > 1 ? 's' : '' ?></p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <i class="fas fa-heart" style="font-size: 150px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Favorites Section -->
<section class="container py-5">
    <?php if (empty($favorites['annonces'])): ?>
        <div class="text-center py-5">
            <i class="fas fa-heart fa-4x text-muted mb-3"></i>
            <h4>Vous n'avez pas encore de favoris</h4>
            <p class="text-muted">Explorez nos annonces et ajoutez vos préférées en favoris</p>
            <div class="mt-3">
                <a href="<?= APP_URL ?>/annonces" class="btn btn-primary me-2">
                    <i class="fas fa-search me-2"></i>Explorer les annonces
                </a>
                <a href="<?= APP_URL ?>/search" class="btn btn-outline-primary">
                    <i class="fas fa-filter me-2"></i>Recherche avancée
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favorites['annonces'] as $favorite): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card annonce-card">
                    <div class="annonce-badge">
                        <span class="badge bg-primary"><?= ucfirst($favorite['type']) ?></span>
                        <span class="badge bg-warning ms-1">
                            <i class="fas fa-heart"></i> Favori
                        </span>
                    </div>
                    <div class="annonce-favorite active" onclick="toggleFavorite(this, <?= $favorite['id'] ?>)">
                        <i class="fas fa-heart"></i>
                    </div>
                    <?php 
                    $images = json_decode($favorite['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($favorite['titre']) ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($favorite['titre']) ?></h5>
                        <p class="annonce-location">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($favorite['ville']) ?>
                            <?php if (!empty($favorite['quartier'])): ?>
                                - <?= htmlspecialchars($favorite['quartier']) ?>
                            <?php endif; ?>
                        </p>
                        
                        <?php if ($favorite['categorie_nom']): ?>
                            <span class="badge bg-secondary small"><?= htmlspecialchars($favorite['categorie_nom']) ?></span>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <?php if ($favorite['superficie']): ?>
                                <span class="text-muted small me-3">
                                    <i class="fas fa-expand me-1"></i><?= $favorite['superficie'] ?>m²
                                </span>
                            <?php endif; ?>
                            <?php if ($favorite['chambres'] > 0): ?>
                                <span class="text-muted small me-3">
                                    <i class="fas fa-bed me-1"></i><?= $favorite['chambres'] ?> chambres
                                </span>
                            <?php endif; ?>
                            <?php if ($favorite['parking']): ?>
                                <span class="text-muted small">
                                    <i class="fas fa-car me-1"></i>Parking
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="annonce-price">
                                <?= number_format($favorite['prix'], 0, '.', ' ') ?> FCFA
                                <?php if ($favorite['type'] === 'location'): ?>
                                    <small class="text-muted">/mois</small>
                                <?php endif; ?>
                            </div>
                            <a href="<?= APP_URL ?>/annonces/<?= $favorite['id'] ?>" class="btn btn-outline-primary btn-sm">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($favorites['total_pages'] > 1): ?>
        <nav aria-label="Pagination des favoris" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($favorites['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $favorites['current_page'] - 1 ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $start = max(1, $favorites['current_page'] - 2);
                $end = min($favorites['total_pages'], $favorites['current_page'] + 2);
                
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <li class="page-item <?= $i == $favorites['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($favorites['current_page'] < $favorites['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $favorites['current_page'] + 1 ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<script>
function toggleFavorite(element, annonceId) {
    const icon = element.querySelector('i');
    
    fetch('<?= APP_URL ?>/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'annonce_id=' + annonceId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'removed') {
                icon.classList.remove('fas');
                icon.classList.add('far');
                element.classList.remove('active');
                
                // Retirer l'élément du DOM après un court délai
                setTimeout(() => {
                    element.closest('.col-md-6').style.opacity = '0.5';
                    element.closest('.col-md-6').style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                }, 100);
            } else {
                showNotification('Ajouté aux favoris !', 'success');
            }
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Créer une notification simple
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto-retirer après 3 secondes
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<?php
$content = ob_get_clean();
$title = 'Mes favoris - TerangaHomes';
include __DIR__ . '/../layouts/seloger_clean.php';
?>
