<?php ob_start(); ?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg fade-in">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Inscription</h2>
                            <p class="text-muted">Rejoignez la communauté TerangaHomes</p>
                        </div>
                        
                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $errors['general'] ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= APP_URL ?>/register">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nom
                                    </label>
                                    <input type="text" class="form-control" id="nom" name="nom" 
                                           value="<?= $old['nom'] ?? '' ?>" required>
                                    <?php if (isset($errors['nom'])): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['nom'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">
                                        <i class="fas fa-user me-2"></i>Prénom
                                    </label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" 
                                           value="<?= $old['prenom'] ?? '' ?>" required>
                                    <?php if (isset($errors['prenom'])): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['prenom'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= $old['email'] ?? '' ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['email'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>Téléphone (optionnel)
                                </label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                       value="<?= $old['telephone'] ?? '' ?>">
                                <?php if (isset($errors['telephone'])): ?>
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['telephone'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Type de compte
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="utilisateur" <?= ($old['role'] ?? '') === 'utilisateur' ? 'selected' : '' ?>>
                                        Utilisateur (chercheur de biens)
                                    </option>
                                    <option value="proprietaire" <?= ($old['role'] ?? '') === 'proprietaire' ? 'selected' : '' ?>>
                                        Propriétaire (je veux publier des annonces)
                                    </option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-toggle"></i>
                                        </button>
                                    </div>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['password'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <small class="text-muted">8 caractères minimum, 1 majuscule, 1 minuscule, 1 chiffre</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirm" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirm')">
                                            <i class="fas fa-eye" id="password_confirm-toggle"></i>
                                        </button>
                                    </div>
                                    <?php if (isset($errors['password_confirm'])): ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= $errors['password_confirm'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> 
                                    et la <a href="#" class="text-primary">politique de confidentialité</a>
                                </label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    Déjà un compte ? 
                                    <a href="<?= APP_URL ?>/login" class="text-primary text-decoration-none fw-bold">
                                        Se connecter
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = document.getElementById(fieldId + '-toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Validation en temps réel
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);
    updatePasswordStrength(strength);
});

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    return strength;
}

function updatePasswordStrength(strength) {
    const strengthText = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    const strengthColor = ['danger', 'warning', 'info', 'success', 'success'];
    
    // Vous pouvez ajouter un indicateur visuel ici si vous le souhaitez
}
</script>

<?php
$content = ob_get_clean();
$title = 'Inscription - TerangaHomes';
include 'views/layouts/app.php';
?>
