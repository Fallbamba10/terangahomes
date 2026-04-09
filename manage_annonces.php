<?php
// Système de gestion des annonces complet

// Configuration agressive de la session AVANT de la démarrer
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);

session_start();

// Forcer la régénération de l'ID de session si nécessaire
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(false);
    $_SESSION['initialized'] = true;
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'src/config/config.php';
require_once 'src/core/Database.php';

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Traitement des actions
$action = $_GET['action'] ?? '';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre' => $_POST['titre'] ?? '',
        'description' => $_POST['description'] ?? '',
        'type' => $_POST['type'] ?? '',
        'prix' => $_POST['prix'] ?? '',
        'ville' => $_POST['ville'] ?? '',
        'quartier' => $_POST['quartier'] ?? '',
        'superficie' => $_POST['superficie'] ?? '',
        'chambres' => $_POST['chambres'] ?? '',
        'salles_bain' => $_POST['salles_bain'] ?? '',
        'meuble' => isset($_POST['meuble']) ? 1 : 0,
        'climatisation' => isset($_POST['climatisation']) ? 1 : 0,
        'wifi' => isset($_POST['wifi']) ? 1 : 0,
        'parking' => isset($_POST['parking']) ? 1 : 0,
        'user_id' => $userId
    ];
    
    // Gestion des images
    $images = [];
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploadDir = 'uploads/annonces/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === 0) {
                $fileName = 'annonce_' . time() . '_' . $key . '.' . pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($tmp_name, $filePath)) {
                    $images[] = $filePath;
                }
            }
        }
    }
    $data['images'] = $images;
    
    if (!empty($data['titre']) && !empty($data['description']) && !empty($data['type']) && !empty($data['prix']) && !empty($data['ville'])) {
        try {
            $sql = "INSERT INTO annonces (titre, description, type, prix, ville, quartier, superficie, chambres, salles_bain, meuble, climatisation, wifi, parking, images, user_id, statut, created_at) 
                    VALUES (:titre, :description, :type, :prix, :ville, :quartier, :superficie, :chambres, :salles_bain, :meuble, :climatisation, :wifi, :parking, :images, :user_id, 'pending', NOW())";
            
            $db->execute($sql, $data);
            
            $success = "Annonce créée avec succès !";
            header("Location: manage_annonces.php?success=" . urlencode($success));
            exit;
            
        } catch (Exception $e) {
            $error = "Erreur lors de la création: " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires";
    }
}

if ($action === 'edit' && isset($_GET['id'])) {
    $annonceId = $_GET['id'];
    
    // Vérifier que l'annonce appartient à l'utilisateur
    $annonce = $db->fetch("SELECT * FROM annonces WHERE id = ? AND user_id = ?", [$annonceId, $userId]);
    
    if (!$annonce) {
        $error = "Annonce non trouvée ou accès non autorisé";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? '',
            'prix' => $_POST['prix'] ?? '',
            'ville' => $_POST['ville'] ?? '',
            'quartier' => $_POST['quartier'] ?? '',
            'superficie' => $_POST['superficie'] ?? '',
            'chambres' => $_POST['chambres'] ?? '',
            'salles_bain' => $_POST['salles_bain'] ?? '',
            'meuble' => isset($_POST['meuble']) ? 1 : 0,
            'climatisation' => isset($_POST['climatisation']) ? 1 : 0,
            'wifi' => isset($_POST['wifi']) ? 1 : 0,
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'id' => $annonceId
        ];
        
        // Gestion des images existantes
        $existingImages = json_decode($annonce['images'] ?? '[]', true) ?: [];
        $imagesToDelete = $_POST['delete_images'] ?? [];
        
        // Supprimer les images cochées
        foreach ($imagesToDelete as $imagePath) {
            if (in_array($imagePath, $existingImages)) {
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $existingImages = array_filter($existingImages, function($img) use ($imagePath) {
                    return $img !== $imagePath;
                });
            }
        }
        
        // Ajouter les nouvelles images
        if (isset($_FILES['new_images']) && !empty($_FILES['new_images']['name'][0])) {
            $uploadDir = 'uploads/annonces/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['new_images']['error'][$key] === 0) {
                    $fileName = 'annonce_' . $annonceId . '_' . time() . '_' . $key . '.' . pathinfo($_FILES['new_images']['name'][$key], PATHINFO_EXTENSION);
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmp_name, $filePath)) {
                        $existingImages[] = $filePath;
                    }
                }
            }
        }
        
        $data['images'] = json_encode(array_values($existingImages));
        
        if (!empty($data['titre']) && !empty($data['description']) && !empty($data['type']) && !empty($data['prix']) && !empty($data['ville'])) {
            try {
                $sql = "UPDATE annonces SET titre = :titre, description = :description, type = :type, prix = :prix, 
                        ville = :ville, quartier = :quartier, superficie = :superficie, chambres = :chambres, 
                        salles_bain = :salles_bain, meuble = :meuble, climatisation = :climatisation, 
                        wifi = :wifi, parking = :parking, images = :images, updated_at = NOW()
                        WHERE id = :id AND user_id = :user_id";
                
                $data['user_id'] = $userId;
                $db->execute($sql, $data);
                
                $success = "Annonce mise à jour avec succès !";
                header("Location: manage_annonces.php?success=" . urlencode($success));
                exit;
                
            } catch (Exception $e) {
                $error = "Erreur lors de la mise à jour: " . $e->getMessage();
            }
        } else {
            $error = "Veuillez remplir tous les champs obligatoires";
        }
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    $annonceId = $_GET['id'];
    
    // Vérifier que l'annonce appartient à l'utilisateur
    $annonce = $db->fetch("SELECT * FROM annonces WHERE id = ? AND user_id = ?", [$annonceId, $userId]);
    
    if ($annonce) {
        // Supprimer les données associées
        $db->execute("DELETE FROM favorites WHERE annonce_id = ?", [$annonceId]);
        $db->execute("DELETE FROM messages WHERE annonce_id = ?", [$annonceId]);
        $db->execute("DELETE FROM annonces WHERE id = ?", [$annonceId]);
        
        $success = "Annonce supprimée avec succès !";
        header("Location: manage_annonces.php?success=" . urlencode($success));
        exit;
    } else {
        $error = "Annonce non trouvée ou accès non autorisé";
    }
}

if ($action === 'toggle_status' && isset($_GET['id'])) {
    $annonceId = $_GET['id'];
    
    $annonce = $db->fetch("SELECT * FROM annonces WHERE id = ? AND user_id = ?", [$annonceId, $userId]);
    
    if ($annonce) {
        $newStatus = $annonce['statut'] === 'active' ? 'inactive' : 'active';
        $db->execute("UPDATE annonces SET statut = ? WHERE id = ?", [$newStatus, $annonceId]);
        
        $message = $newStatus === 'active' ? "Annonce activée" : "Annonce désactivée";
        header("Location: manage_annonces.php?message=" . urlencode($message));
        exit;
    }
}

// Récupérer les annonces de l'utilisateur
$annonces = $db->fetchAll("
    SELECT a.*, 
           (SELECT COUNT(*) FROM favorites f WHERE f.annonce_id = a.id) as favorite_count,
           (SELECT COUNT(*) FROM messages m WHERE m.annonce_id = a.id) as message_count
    FROM annonces a 
    WHERE a.user_id = ? 
    ORDER BY a.created_at DESC
", [$userId]);

// Statistiques
$stats = [
    'total' => count($annonces),
    'active' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE user_id = ? AND statut = 'active'", [$userId])['count'],
    'pending' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE user_id = ? AND statut = 'pending'", [$userId])['count'],
    'inactive' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE user_id = ? AND statut = 'inactive'", [$userId])['count']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Gestion des Annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .annonce-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            margin-bottom: 20px;
        }
        
        .annonce-card:hover {
            transform: translateY(-2px);
        }
        
        .annonce-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background: #007bff;
            color: white;
        }
        
        .btn-edit:hover {
            background: #0056b3;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
            color: white;
        }
        
        .btn-toggle {
            background: #28a745;
            color: white;
        }
        
        .btn-toggle:hover {
            background: #218838;
            color: white;
        }
        
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="user_dashboard.php">
                    <i class="fas fa-home me-2"></i>TerangaHomes
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="user_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    <a class="nav-link" href="messaging_system.php">
                        <i class="fas fa-comments me-1"></i>Messagerie
                    </a>
                    <a class="nav-link" href="annonces_direct_fixed.php">
                        <i class="fas fa-plus me-1"></i>Nouvelle annonce
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                    </a>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid py-4">
            <!-- Messages -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i><?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire de création/édition -->
            <?php if ($action === 'edit' && isset($annonce)): ?>
                <div class="form-section mb-4">
                    <h4 class="mb-4"><i class="fas fa-edit me-2"></i>Modifier l'annonce</h4>
                    <form method="post" action="manage_annonces.php?action=edit&id=<?= $annonceId ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Titre *</label>
                                <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type *</label>
                                <select class="form-select" name="type" required>
                                    <option value="location" <?= $annonce['type'] === 'location' ? 'selected' : '' ?>>Location</option>
                                    <option value="vente" <?= $annonce['type'] === 'vente' ? 'selected' : '' ?>>Vente</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($annonce['description']) ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Prix (FCFA) *</label>
                                <input type="number" class="form-control" name="prix" value="<?= $annonce['prix'] ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ville *</label>
                                <select class="form-select" name="ville" required>
                                    <option value="Dakar" <?= $annonce['ville'] === 'Dakar' ? 'selected' : '' ?>>Dakar</option>
                                    <option value="Saly" <?= $annonce['ville'] === 'Saly' ? 'selected' : '' ?>>Saly</option>
                                    <option value="Saint-Louis" <?= $annonce['ville'] === 'Saint-Louis' ? 'selected' : '' ?>>Saint-Louis</option>
                                    <option value="Mbour" <?= $annonce['ville'] === 'Mbour' ? 'selected' : '' ?>>Mbour</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Quartier</label>
                                <input type="text" class="form-control" name="quartier" value="<?= htmlspecialchars($annonce['quartier']) ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" name="superficie" value="<?= $annonce['superficie'] ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Chambres</label>
                                <input type="number" class="form-control" name="chambres" value="<?= $annonce['chambres'] ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Salles de bain</label>
                                <input type="number" class="form-control" name="salles_bain" value="<?= $annonce['salles_bain'] ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Équipements</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="meuble" <?= $annonce['meuble'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Meublé</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="climatisation" <?= $annonce['climatisation'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Climatisation</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="wifi" <?= $annonce['wifi'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">WiFi</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="parking" <?= $annonce['parking'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Parking</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gestion des images -->
                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-images me-2"></i>Images de l'annonce</label>
                            
                            <?php 
                            $existingImages = json_decode($annonce['images'] ?? '[]', true) ?: [];
                            if (!empty($existingImages)): 
                            ?>
                                <div class="mb-3">
                                    <h6><i class="fas fa-trash me-2"></i>Images existantes (cochez pour supprimer)</h6>
                                    <div class="row">
                                        <?php foreach ($existingImages as $index => $image): ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="<?= $image ?>" class="card-img-top" alt="Image <?= $index + 1 ?>" style="height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?= $image ?>" id="delete_img_<?= $index ?>">
                                                            <label class="form-check-label small" for="delete_img_<?= $index ?>">Supprimer</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <h6><i class="fas fa-plus me-2"></i>Ajouter de nouvelles images</h6>
                                <input type="file" class="form-control" name="new_images[]" multiple accept="image/*">
                                <small class="text-muted">Vous pouvez sélectionner plusieurs images (JPG, PNG, GIF, WebP - Max 5MB par image)</small>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                            <a href="manage_annonces.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3 class="text-primary"><?= $stats['total'] ?></h3>
                        <p class="mb-0">Total annonces</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3 class="text-success"><?= $stats['active'] ?></h3>
                        <p class="mb-0">Actives</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3 class="text-warning"><?= $stats['pending'] ?></h3>
                        <p class="mb-0">En attente</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3 class="text-danger"><?= $stats['inactive'] ?></h3>
                        <p class="mb-0">Inactives</p>
                    </div>
                </div>
            </div>
            
            <!-- Liste des annonces -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fas fa-list me-2"></i>Mes annonces</h4>
                        <a href="annonces_direct_fixed.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle annonce
                        </a>
                    </div>
                    
                    <?php if (empty($annonces)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune annonce</h5>
                            <p class="text-muted">Commencez par déposer votre première annonce</p>
                            <a href="annonces_direct_fixed.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Déposer une annonce
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($annonces as $annonce): ?>
                            <div class="annonce-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?= htmlspecialchars($annonce['titre']) ?></h5>
                                                <span class="annonce-status status-<?= $annonce['statut'] ?>">
                                                    <?= ucfirst($annonce['statut']) ?>
                                                </span>
                                            </div>
                                            <p class="text-muted mb-2"><?= htmlspecialchars(substr($annonce['description'], 0, 150)) ?>...</p>
                                            <div class="d-flex gap-3 mb-2">
                                                <span><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?></span>
                                                <span><i class="fas fa-tag me-1"></i><?= number_format($annonce['prix'], 0, ' ', ' ') ?> FCFA</span>
                                                <span><i class="fas fa-home me-1"></i><?= ucfirst($annonce['type']) ?></span>
                                            </div>
                                            <div class="d-flex gap-3 text-muted small">
                                                <span><i class="fas fa-heart me-1"></i><?= $annonce['favorite_count'] ?> favoris</span>
                                                <span><i class="fas fa-envelope me-1"></i><?= $annonce['message_count'] ?> messages</span>
                                                <span><i class="fas fa-calendar me-1"></i><?= date('d/m/Y', strtotime($annonce['created_at'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="action-buttons justify-content-end">
                                                <a href="manage_annonces.php?action=edit&id=<?= $annonce['id'] ?>" class="btn-action btn-edit">
                                                    <i class="fas fa-edit me-1"></i>Modifier
                                                </a>
                                                <a href="manage_annonces.php?action=toggle_status&id=<?= $annonce['id'] ?>" class="btn-action btn-toggle">
                                                    <i class="fas fa-power-off me-1"></i><?= $annonce['statut'] === 'active' ? 'Désactiver' : 'Activer' ?>
                                                </a>
                                                <a href="manage_annonces.php?action=delete&id=<?= $annonce['id'] ?>" 
                                                   class="btn-action btn-delete" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
