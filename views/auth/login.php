<?php ob_start(); ?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg fade-in">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-home fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Connexion</h2>
                            <p class="text-muted">Accédez à votre espace TerangaHomes</p>
                        </div>
                        
                        <?php if (isset($errors['login'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $errors['login'] ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= APP_URL ?>/login">
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
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    Pas encore de compte ? 
                                    <a href="<?= APP_URL ?>/register" class="text-primary text-decoration-none fw-bold">
                                        S'inscrire
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Options de connexion sociale -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-3">Ou connectez-vous avec</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-primary">
                            <i class="fab fa-google me-2"></i>Google
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </button>
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
</script>

<?php
$content = ob_get_clean();
$title = 'Connexion - TerangaHomes';
include 'views/layouts/app.php';
?>
