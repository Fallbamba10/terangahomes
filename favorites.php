<?php
// Page directe des favoris pour éviter les problèmes de routing

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Favorite.php';

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$favoriteModel = new Favorite();
$page = $_GET['page'] ?? 1;
$userId = $_SESSION['user_id'];

$favorites = $favoriteModel->getUserFavorites($userId, $page);
$count = $favoriteModel->getFavoriteCount($userId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes favoris - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }
        
        .navbar {
            background: white !important;
            border-bottom: 3px solid #0066cc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #0066cc !important;
            font-weight: 700;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 80px 0;
        }
        
        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .annonce-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        
        .annonce-favorite {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .annonce-favorite.active {
            background: #ff6600;
            color: white;
        }
        
        .annonce-price {
            color: #0066cc;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .annonce-location {
            color: #666666;
            font-size: 0.9rem;
        }
        
        footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 60px 0 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="annonces">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search">Recherche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="favorites.php">
                            <i class="fas fa-heart me-1"></i>Favoris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="my-annonces">
                                <i class="fas fa-list me-2"></i>Mes annonces
                            </a></li>
                            <li><a class="dropdown-item" href="favorites.php">
                                <i class="fas fa-heart me-2"></i>Mes favoris
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                    <a href="annonces" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Explorer les annonces
                    </a>
                    <a href="search" class="btn btn-outline-primary">
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
                        $firstImage = !empty($images) ? $images[0] : 'uploads/default.jpg';
                        ?>
                        <img src="<?= $firstImage ?>" 
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
                                <a href="annonces/<?= $favorite['id'] ?>" class="btn btn-outline-primary btn-sm">
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

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <i class="fas fa-home"></i>
                        <span>TerangaHomes</span>
                    </div>
                    <p class="footer-description">
                        Votre plateforme de confiance pour la location, la vente et la réservation d'hébergements et véhicules au Sénégal.
                    </p>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5>Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i>Accueil</a></li>
                        <li><a href="annonces"><i class="fas fa-chevron-right"></i>Annonces</a></li>
                        <li><a href="search"><i class="fas fa-chevron-right"></i>Recherche</a></li>
                        <li><a href="favorites.php"><i class="fas fa-chevron-right"></i>Favoris</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5>Contact</h5>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+221 33 123 45 67</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@terangahomes.com</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function toggleFavorite(element, annonceId) {
        const icon = element.querySelector('i');
        
        fetch('favorites_toggle.php', {
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
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    </script>
</body>
</html>
