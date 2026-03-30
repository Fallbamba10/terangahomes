<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Mes annonces</h1>
                    <p class="lead">Gérez toutes vos annonces publiées</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <a href="<?= APP_URL ?>/annonces/create" class="btn btn-secondary btn-lg">
                        <i class="fas fa-plus me-2"></i>Nouvelle annonce
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- My Annonces Section -->
<section class="container py-5">
    <?php if (empty($annonces)): ?>
        <div class="text-center py-5">
            <i class="fas fa-home fa-4x text-muted mb-3"></i>
            <h4>Vous n'avez pas encore d'annonces</h4>
            <p class="text-muted">Commencez par déposer votre première annonce</p>
            <a href="<?= APP_URL ?>/annonces/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Déposer une annonce
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
            <div class="col-lg-6 mb-4">
                <div class="card annonce-card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-md-4">
                                <?php 
                                $images = json_decode($annonce['images'] ?? '[]', true);
                                $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                                ?>
                                <img src="<?= APP_URL ?>/uploads/images/<?= $firstImage ?>" 
                                     class="img-fluid rounded" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?= htmlspecialchars($annonce['titre']) ?></h5>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-<?= $annonce['statut'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= $annonce['statut'] === 'active' ? 'Active' : 'Inactive' ?>
                                        </span>
                                        <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                                    </div>
                                </div>
                                
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                                </p>
                                
                                <div class="annonce-price mb-3">
                                    <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                    <?php if ($annonce['type'] === 'location'): ?>
                                        <small class="text-muted">/mois</small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-eye me-1"></i><?= $annonce['views_count'] ?? 0 ?> vues
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>" 
                                           class="btn btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= APP_URL ?>/annonces/<?= $annonce['id'] ?>/edit" 
                                           class="btn btn-outline-secondary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-outline-warning" 
                                                onclick="toggleStatus(<?= $annonce['id'] ?>)" 
                                                title="<?= $annonce['statut'] === 'active' ? 'Désactiver' : 'Activer' ?>">
                                            <i class="fas fa-<?= $annonce['statut'] === 'active' ? 'pause' : 'play' ?>"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" 
                                                onclick="deleteAnnonce(<?= $annonce['id'] ?>)" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <nav aria-label="Pagination" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>">
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
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>">
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
function toggleStatus(annonceId) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cette annonce ?')) {
        // Appel AJAX pour changer le statut
        fetch(`<?= APP_URL ?>/annonces/${annonceId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger la page pour voir les changements
                window.location.reload();
            } else {
                alert('Erreur lors du changement de statut: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion. Veuillez réessayer.');
        });
    }
}

function deleteAnnonce(annonceId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.')) {
        // Appel AJAX pour supprimer l'annonce
        fetch(`<?= APP_URL ?>/annonces/${annonceId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Mes annonces - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
