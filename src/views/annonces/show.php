<?php ob_start(); ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="container mt-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/annonces">Annonces</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($annonce['titre']) ?></li>
    </ol>
</nav>

<!-- Annonce Detail Section -->
<section class="container py-4">
    <div class="row">
        <!-- Images Gallery -->
        <div class="col-lg-8">
            <div class="annonce-gallery">
                <?php 
                $images = json_decode($annonce['images'] ?? '[]', true);
                if (!empty($images)):
                ?>
                <div id="mainImage" class="main-image mb-3">
                    <img src="<?= APP_URL ?>/uploads/annonces/<?= $images[0] ?>" 
                         class="img-fluid rounded" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                </div>
                <div class="thumbnail-gallery d-flex gap-2 overflow-auto">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>" 
                             onclick="changeMainImage('<?= $image ?>', this)">
                            <img src="<?= APP_URL ?>/uploads/annonces/<?= $image ?>" 
                                 class="img-thumbnail" alt="Miniature <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="main-image mb-3">
                        <img src="<?= APP_URL ?>/assets/images/default-property.jpg" 
                             class="img-fluid rounded" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Description -->
            <div class="description-section mt-4">
                <h3>Description</h3>
                <div class="description-content">
                    <?= nl2br(htmlspecialchars($annonce['description'])) ?>
                </div>
                
                <!-- Caractéristiques -->
                <h4 class="mt-4">Caractéristiques</h4>
                <div class="row characteristics">
                    <?php if ($annonce['superficie']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-expand text-primary"></i>
                                <span><?= $annonce['superficie'] ?> m²</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['chambres'] > 0): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-bed text-primary"></i>
                                <span><?= $annonce['chambres'] ?> chambre<?= $annonce['chambres'] > 1 ? 's' : '' ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['salles_bain'] > 0): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-bath text-primary"></i>
                                <span><?= $annonce['salles_bain'] ?> salle<?= $annonce['salles_bain'] > 1 ? 's' : '' ?> de bain</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['parking']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-car text-primary"></i>
                                <span>Parking</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['meuble']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-couch text-primary"></i>
                                <span>Meublé</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['climatisation']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-snowflake text-primary"></i>
                                <span>Climatisation</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['wifi']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-wifi text-primary"></i>
                                <span>WiFi</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['piscine']): ?>
                        <div class="col-md-4 mb-3">
                            <div class="characteristic-item">
                                <i class="fas fa-swimming-pool text-primary"></i>
                                <span>Piscine</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Localisation -->
            <div class="location-section mt-4">
                <h4>Localisation</h4>
                <div class="location-info">
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <?= htmlspecialchars($annonce['adresse'] ?? $annonce['quartier'] . ', ' . $annonce['ville']) ?>
                    </p>
                    <?php if ($annonce['latitude'] && $annonce['longitude']): ?>
                        <div id="map" style="height: 300px;" class="rounded mt-3"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price Card -->
            <div class="card price-card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <div class="price-section">
                        <div class="annonce-price">
                            <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                            <?php if ($annonce['type'] === 'location'): ?>
                                <small class="text-muted">/mois</small>
                            <?php endif; ?>
                        </div>
                        <div class="type-badge">
                            <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                            <?php if ($annonce['categorie_nom']): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="actions-section mt-4">
                        <button class="btn btn-primary w-100 mb-2" onclick="showContactModal()">
                            <i class="fas fa-phone me-2"></i>Contacter le propriétaire
                        </button>
                        <button class="btn btn-outline-primary w-100 mb-2" 
                                onclick="toggleFavorite(this, <?= $annonce['id'] ?>)">
                            <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart me-2"></i>
                            <?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
                        </button>
                        <button class="btn btn-outline-secondary w-100" onclick="shareAnnonce()">
                            <i class="fas fa-share-alt me-2"></i>Partager
                        </button>
                    </div>
                    
                    <!-- Propriétaire Info -->
                    <div class="owner-info mt-4">
                        <h5>Propriétaire</h5>
                        <div class="d-flex align-items-center">
                            <div class="owner-avatar me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($annonce['proprietaire_nom'] . ' ' . $annonce['proprietaire_prenom']) ?></h6>
                                <small class="text-muted">Membre depuis 2024</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="stats-info mt-4">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-eye me-1"></i><?= $annonce['views_count'] ?? 0 ?> vues</span>
                            <span><i class="fas fa-heart me-1"></i><?= rand(10, 50) ?> favoris</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Annonces -->
    <?php if (!empty($similar)): ?>
    <section class="similar-annonces mt-5">
        <h3>Annonces similaires</h3>
        <div class="row">
            <?php foreach ($similar as $sim_annonce): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card similar-card">
                    <?php 
                    $sim_images = json_decode($sim_annonce['images'] ?? '[]', true);
                    $sim_firstImage = !empty($sim_images) ? $sim_images[0] : 'default.jpg';
                    ?>
                    <img src="<?= APP_URL ?>/uploads/annonces/<?= $sim_firstImage ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($sim_annonce['titre']) ?>">
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($sim_annonce['titre']) ?></h6>
                        <p class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($sim_annonce['ville']) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-primary fw-bold">
                                <?= number_format($sim_annonce['prix'], 0, '.', ' ') ?> FCFA
                            </small>
                            <a href="<?= APP_URL ?>/annonces/<?= $sim_annonce['id'] ?>" 
                               class="btn btn-outline-primary btn-sm">Voir</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</section>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contacter le propriétaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="contact-info">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <p><?= htmlspecialchars($annonce['proprietaire_nom'] . ' ' . $annonce['proprietaire_prenom']) ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <p>
                            <i class="fas fa-phone me-2"></i><?= htmlspecialchars($annonce['proprietaire_telephone'] ?? 'Non disponible') ?>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <p>
                            <i class="fas fa-envelope me-2"></i><?= htmlspecialchars($annonce['proprietaire_email']) ?>
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <form id="messageForm">
                    <div class="mb-3">
                        <label class="form-label">Votre message</label>
                        <textarea class="form-control" name="message" rows="4" 
                                  placeholder="Bonjour, je suis intéressé(e) par votre annonce..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.main-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.thumbnail-item {
    flex: 0 0 80px;
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.3s;
}

.thumbnail-item.active,
.thumbnail-item:hover {
    opacity: 1;
}

.thumbnail-item img {
    width: 80px;
    height: 60px;
    object-fit: cover;
}

.characteristic-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.price-card {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.similar-card {
    transition: transform 0.3s;
}

.similar-card:hover {
    transform: translateY(-5px);
}
</style>

<script>
function changeMainImage(imagePath, thumbnail) {
    document.querySelector('#mainImage img').src = '<?= APP_URL ?>/uploads/annonces/' + imagePath;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    thumbnail.classList.add('active');
}

function showContactModal() {
    <?php if ($this->isLoggedIn()): ?>
        new bootstrap.Modal(document.getElementById('contactModal')).show();
    <?php else: ?>
        window.location.href = '<?= APP_URL ?>/login';
    <?php endif; ?>
}

function toggleFavorite(button, annonceId) {
    const icon = button.querySelector('i');
    const text = button.querySelector('span') || button;
    
    <?php if ($this->isLoggedIn()): ?>
        // TODO: Appel AJAX pour gérer les favoris
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            if (text.textContent.includes('Ajouter')) {
                text.textContent = 'Retirer des favoris';
            }
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            if (text.textContent.includes('Retirer')) {
                text.textContent = 'Ajouter aux favoris';
            }
        }
    <?php else: ?>
        window.location.href = '<?= APP_URL ?>/login';
    <?php endif; ?>
}

function shareAnnonce() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($annonce['titre']) ?>',
            text: '<?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...',
            url: window.location.href
        });
    } else {
        // Fallback: copier le lien
        navigator.clipboard.writeText(window.location.href);
        alert('Lien copié dans le presse-papiers !');
    }
}

// Initialize map if coordinates are available
<?php if ($annonce['latitude'] && $annonce['longitude']): ?>
document.addEventListener('DOMContentLoaded', function() {
    // TODO: Intégrer Google Maps ici
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 bg-light"><div><i class="fas fa-map-marked-alt fa-3x text-muted mb-3 d-block"></i><p>Carte Google Maps</p></div></div>';
    }
});
<?php endif; ?>
</script>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($annonce['titre']) . ' - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
