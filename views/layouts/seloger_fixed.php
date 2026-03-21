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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Inline pour éviter les problèmes de chargement -->
    <style>
        /* Style SeLoger-inspired - TerangaHomes */
        :root {
            --primary-blue: #0066cc;
            --primary-orange: #ff6600;
            --secondary-blue: #004499;
            --secondary-orange: #e55100;
            --light-blue: #e6f2ff;
            --light-orange: #fff5ed;
            --dark-blue: #003366;
            --gray-light: #f5f5f5;
            --gray-medium: #e0e0e0;
            --gray-dark: #666666;
            --white: #ffffff;
            --success-green: #28a745;
            --warning-yellow: #ffc107;
            --border-radius: 8px;
            --box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        /* Global Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
            color: var(--dark-blue);
            background-color: var(--gray-light);
        }

        /* Navbar SeLoger Style */
        .navbar {
            background: var(--white) !important;
            border-bottom: 3px solid var(--primary-blue);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue) !important;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--secondary-blue) !important;
        }

        .navbar-nav .nav-link {
            color: var(--dark-blue) !important;
            font-weight: 500;
            padding: 1rem 1.2rem !important;
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-blue) !important;
            background-color: var(--light-blue);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-blue) !important;
            background-color: var(--light-blue);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--white);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,133.3C960,128,1056,96,1152,90.7C1248,85,1344,107,1392,117.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content .lead {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: var(--white);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            background: var(--secondary-blue);
            border-color: var(--secondary-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,102,204,0.3);
        }

        .btn-secondary {
            background: var(--white);
            border-color: var(--white);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: var(--gray-light);
            color: var(--secondary-blue);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            background: transparent;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-primary:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .annonce-card {
            position: relative;
            height: 100%;
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
            background: var(--white);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            z-index: 10;
        }

        .annonce-favorite:hover {
            background: var(--primary-orange);
            color: var(--white);
            transform: scale(1.1);
        }

        .annonce-favorite.active {
            background: var(--primary-orange);
            color: var(--white);
        }

        .annonce-price {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .annonce-location {
            color: var(--gray-dark);
            font-size: 0.9rem;
        }

        /* Search Section */
        .search-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin: -50px auto 2rem;
            position: relative;
            z-index: 10;
        }

        .form-control, .form-select {
            border: 2px solid var(--gray-medium);
            border-radius: var(--border-radius);
            padding: 12px 16px;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0,102,204,0.25);
        }

        /* Features Section */
        .feature-card {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--gray-dark);
            line-height: 1.6;
        }

        /* Footer Moderne */
        footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 60px 0 30px;
            border-top: 1px solid #dee2e6;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--primary-orange) 50%, var(--primary-blue) 100%);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        footer h5 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        footer a {
            color: #6c757d;
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        footer a:hover {
            color: var(--primary-blue);
            transform: translateX(3px);
        }

        footer .list-unstyled li {
            margin-bottom: 12px;
            padding-left: 0;
        }

        footer .list-unstyled li i {
            margin-right: 10px;
            color: var(--primary-orange);
            font-size: 0.9rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .footer-brand i {
            font-size: 2rem;
            color: var(--primary-blue);
            margin-right: 12px;
        }

        .footer-brand span {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2c3e50;
        }

        .footer-description {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: var(--white);
            border: 2px solid #e9ecef;
            border-radius: 50%;
            color: #6c757d;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 102, 204, 0.2);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #dee2e6;
        }

        .footer-bottom p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .search-section {
                margin: -30px 1rem 1rem;
                padding: 1.5rem;
            }
        }
    </style>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= APP_URL ?>/assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= APP_URL ?>">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/annonces">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/search">Recherche</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/my-annonces">Mes annonces</a></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/profile">Mon profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/logout">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/login">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/register">Inscription</a>
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
                        Nous connectons les propriétaires avec les locataires pour des transactions sécurisées.
                    </p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_URL ?>"><i class="fas fa-chevron-right"></i>Accueil</a></li>
                        <li><a href="<?= APP_URL ?>/annonces"><i class="fas fa-chevron-right"></i>Annonces</a></li>
                        <li><a href="<?= APP_URL ?>/search"><i class="fas fa-chevron-right"></i>Recherche</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>À propos</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Location</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Vente</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Réservation hôtels</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Location voitures</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i>Services premium</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Contact</h5>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+221 33 123 45 67</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@terangahomes.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Dakar, Sénégal</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Lun-Ven: 8h-18h</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#">Mentions légales</a>
                        <a href="#">CGU</a>
                        <a href="#">Confidentialité</a>
                        <a href="#">Cookies</a>
                    </div>
                </div>
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
