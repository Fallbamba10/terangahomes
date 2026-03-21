<?php
// Formulaire pour contacter le propriétaire d'une annonce

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Message.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$annonceId = $_GET['annonce_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$annonceId) {
    header('Location: annonces.php');
    exit;
}

// Récupérer les informations de l'annonce et du propriétaire
$db = Database::getInstance();
$annonce = $db->fetch(
    "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, u.id as proprietaire_id
     FROM annonces a 
     JOIN users u ON a.user_id = u.id 
     WHERE a.id = ? AND a.statut = 'active'",
    [$annonceId]
);

if (!$annonce) {
    header('Location: annonces.php');
    exit;
}

// Vérifier que l'utilisateur ne contacte pas sa propre annonce
if ($annonce['proprietaire_id'] == $userId) {
    header('Location: annonces/' . $annonceId);
    exit;
}

// Envoyer le message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['message']);
    
    if (!empty($content)) {
        try {
            $messageModel = new Message();
            $messageModel->send($userId, $annonce['proprietaire_id'], $annonceId, $content);
            
            // Rediriger vers les messages
            header("Location: messages.php?user_id={$annonce['proprietaire_id']}&annonce_id=$annonceId");
            exit;
        } catch (Exception $e) {
            $error = "Erreur lors de l'envoi du message";
        }
    } else {
        $error = "Veuillez saisir un message";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter le propriétaire - TerangaHomes</title>
    
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
        
        .contact-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .annonce-preview {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .contact-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .proprietaire-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .btn-primary {
            background: #0066cc;
            border-color: #0066cc;
            color: white;
        }
        
        .btn-primary:hover {
            background: #004499;
            border-color: #004499;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item" href="messages.php">
                            <i class="fas fa-envelope me-2"></i>Messages
                        </a></li>
                        <li><a class="dropdown-item" href="favorites.php">
                            <i class="fas fa-heart me-2"></i>Favoris
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container contact-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="annonces">Annonces</a></li>
                <li class="breadcrumb-item"><a href="annonces/<?= $annonceId ?>"><?= htmlspecialchars($annonce['titre']) ?></a></li>
                <li class="breadcrumb-item active">Contacter le propriétaire</li>
            </ol>
        </nav>

        <!-- Annonce Preview -->
        <div class="annonce-preview">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3><?= htmlspecialchars($annonce['titre']) ?></h3>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($annonce['ville']) ?>
                        <?php if (!empty($annonce['quartier'])): ?>
                            - <?= htmlspecialchars($annonce['quartier']) ?>
                        <?php endif; ?>
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                        <span class="text-primary fw-bold">
                            <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                            <?php if ($annonce['type'] === 'location'): ?>
                                <small class="text-muted">/mois</small>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-home fa-4x text-primary mb-3"></i>
                    <p class="text-muted">ID: #<?= $annonceId ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h4 class="mb-4">
                <i class="fas fa-envelope me-2"></i>Contacter le propriétaire
            </h4>

            <!-- Propriétaire Info -->
            <div class="proprietaire-info">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-1"><?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?></h6>
                        <p class="text-muted mb-0">Propriétaire de cette annonce</p>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label for="message" class="form-label">Votre message *</label>
                    <textarea class="form-control" id="message" name="message" rows="6" required
                              placeholder="Bonjour, je suis intéressé(e) par votre annonce. Pourriez-vous me donner plus d'informations..."></textarea>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Conseils de communication :</strong>
                    <ul class="mb-0 mt-2">
                        <li>Soyez courtois et professionnel</li>
                        <li>Précisez vos questions sur le bien</li>
                        <li>Indiquez si vous souhaitez visiter</li>
                        <li>Ne partagez pas d'informations personnelles sensibles</li>
                    </ul>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                    </button>
                    <a href="annonces/<?= $annonceId ?>" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Retour à l'annonce
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
