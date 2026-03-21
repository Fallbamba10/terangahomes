<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?> - Plateforme Immobilière TerangaHomes</title>
    <meta name="description" content="<?= $description ?? 'TerangaHomes - Location, vente et réservation d\'hôtels et voitures' ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= APP_URL ?>/assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= APP_URL ?>">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>"><i class="fas fa-home me-1"></i>Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/annonces"><i class="fas fa-search me-1"></i>Annonces</a>
                    </li>
                    <?php if ($this->isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/favorites"><i class="fas fa-heart me-1"></i>Favoris</a>
                        </li>
                        <?php if ($this->isOwner()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/my-annonces"><i class="fas fa-list me-1"></i>Mes annonces</a>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/admin"><i class="fas fa-cog me-1"></i>Admin</a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if ($this->isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?php if ($_SESSION['user_avatar']): ?>
                                    <img src="<?= APP_URL ?>/uploads/avatars/<?= $_SESSION['user_avatar'] ?>" class="rounded-circle me-2" width="30" height="30" alt="Avatar">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                <?php endif; ?>
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord</a></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/profile"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/messages"><i class="fas fa-envelope me-2"></i>Messages</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/logout"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/login"><i class="fas fa-sign-in-alt me-1"></i>Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light ms-2" href="<?= APP_URL ?>/register">S'inscrire</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['flash_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['flash_error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3"><i class="fas fa-home me-2"></i>TerangaHomes</h5>
                    <p>Votre plateforme de confiance pour la location, la vente et la réservation d'hébergements et véhicules.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_URL ?>" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="<?= APP_URL ?>/annonces" class="text-white text-decoration-none">Annonces</a></li>
                        <li><a href="#" class="text-white text-decoration-none">À propos</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contact</h5>
                    <p><i class="fas fa-envelope me-2"></i>contact@terangahomes.com</p>
                    <p><i class="fas fa-phone me-2"></i>+221 33 123 45 67</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Dakar, Sénégal</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= APP_URL ?>/assets/js/app.js"></script>
    
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>
</html>
