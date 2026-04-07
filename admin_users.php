<?php
// Page de gestion des utilisateurs pour l'admin

require_once 'config/config.php';
require_once 'core/Database.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'utilisateur') !== 'admin') {
    // Auto-création et connexion admin comme dans admin.php
    if (!isset($_SESSION['user_id'])) {
        $db = Database::getInstance();
        
        try {
            $admin = $db->fetch("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
            
            if (!$admin) {
                $adminData = [
                    'nom' => 'Admin',
                    'prenom' => 'TerangaHomes',
                    'email' => 'admin@terangahomes.com',
                    'telephone' => '22112345678',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'role' => 'admin',
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $sql = "INSERT INTO users (nom, prenom, email, telephone, password, role, is_active, created_at) 
                        VALUES (:nom, :prenom, :email, :telephone, :password, :role, :is_active, :created_at)";
                
                $db->query($sql, $adminData);
                $adminId = $db->lastInsertId();
                
                $_SESSION['user_id'] = $adminId;
                $_SESSION['user_email'] = 'admin@terangahomes.com';
                $_SESSION['user_prenom'] = 'Admin';
                $_SESSION['user_role'] = 'admin';
                $_SESSION['user_nom'] = 'Admin';
                
            } else {
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_prenom'] = $admin['prenom'];
                $_SESSION['user_role'] = $admin['role'];
                $_SESSION['user_nom'] = $admin['nom'];
            }
            
        } catch (Exception $e) {
            echo "<h1>❌ Erreur</h1>";
            echo "<p>Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<a href='index.php' class='btn btn-secondary'>Retour</a>";
            exit;
        }
    } else {
        echo "<div style='text-align: center; padding: 50px;'>";
        echo "<h1>⛔ Accès refusé</h1>";
        echo "<a href='index.php' class='btn btn-secondary'>Retour</a>";
        echo "</div>";
        exit;
    }
}

$db = Database::getInstance();

// Pagination
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filtres
$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';

// Construire la requête
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($role)) {
    $where[] = "role = ?";
    $params[] = $role;
}

if (!empty($status)) {
    $where[] = "is_active = ?";
    $params[] = $status === 'active' ? 1 : 0;
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Récupérer les utilisateurs
$sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$users = $db->fetchAll($sql, $params);

// Compter le total
$countSql = "SELECT COUNT(*) as total FROM users $whereClause";
$totalResult = $db->fetch($countSql, $params);
$total = $totalResult['total'] ?? 0;
$totalPages = ceil($total / $limit);

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = $_POST['user_id'] ?? '';
    
    if ($action === 'toggle_status' && $userId) {
        $user = $db->fetch("SELECT is_active FROM users WHERE id = ?", [$userId]);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $db->query("UPDATE users SET is_active = ? WHERE id = ?", [$newStatus, $userId]);
            header("Location: admin_users.php?status=" . ($newStatus ? 'active' : 'inactive'));
            exit;
        }
    }
    
    if ($action === 'delete_user' && $userId) {
        // Ne pas supprimer l'admin lui-même
        if ($userId != $_SESSION['user_id']) {
            $db->query("DELETE FROM users WHERE id = ?", [$userId]);
            header("Location: admin_users.php?deleted=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs - Admin TerangaHomes</title>
    
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            border-bottom: 3px solid #bd2130;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 40px 0;
        }
        
        .card {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        
        .admin-badge {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .table th {
            background: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .search-filters {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
                <span class="admin-badge ms-2">ADMIN</span>
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-white text-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin.php">
                            <i class="fas fa-cog me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item active" href="admin_users.php">
                            <i class="fas fa-users me-2"></i>Utilisateurs
                        </a></li>
                        <li><a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Mon Dashboard
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

    <!-- Header -->
    <section class="admin-header">
        <div class="container">
            <h1><i class="fas fa-users me-3"></i>Gestion des utilisateurs</h1>
            <p class="lead"><?= $total ?> utilisateur<?= $total > 1 ? 's' : '' ?> inscrit<?= $total > 1 ? 's' : '' ?></p>
        </div>
    </section>

    <!-- Content -->
    <section class="container py-5">
        <!-- Filtres de recherche -->
        <div class="search-filters">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, prénom, email...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Rôle</label>
                    <select class="form-select" name="role">
                        <option value="">Tous</option>
                        <option value="utilisateur" <?= $role === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="proprietaire" <?= $role === 'proprietaire' ? 'selected' : '' ?>>Propriétaire</option>
                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="">Tous</option>
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                        <a href="admin_users.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>Liste des utilisateurs</span>
                <span class="badge bg-primary"><?= $total ?> utilisateur<?= $total > 1 ? 's' : '' ?></span>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Utilisateur supprimé avec succès
                    </div>
                <?php endif; ?>

                <?php if (empty($users)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5>Aucun utilisateur trouvé</h5>
                        <p class="text-muted">Aucun utilisateur ne correspond à vos critères de recherche</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                    <th>Inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                                                </div>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="action" value="toggle_status">
                                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                        <button type="submit" class="btn btn-action btn-<?= $user['is_active'] ? 'warning' : 'success' ?>" title="<?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                                            <i class="fas fa-<?= $user['is_active'] ? 'pause' : 'play' ?>"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        <input type="hidden" name="action" value="delete_user">
                                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                        <button type="submit" class="btn btn-action btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="badge bg-info">Vous</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <nav aria-label="Pagination des utilisateurs" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($role) ? '&role=' . $role : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($role) ? '&role=' . $role : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($role) ? '&role=' . $role : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
