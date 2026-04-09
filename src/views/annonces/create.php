<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Déposer une annonce</h1>
                    <p class="lead">Mettez votre bien en valeur et trouvez rapidement locataire ou acheteur</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <i class="fas fa-plus-circle" style="font-size: 150px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Informations de l'annonce
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= APP_URL ?>/annonces/store" enctype="multipart/form-data" id="annonceForm">
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations générales
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="titre" class="form-label">Titre de l'annonce *</label>
                                <input type="text" class="form-control" id="titre" name="titre" 
                                       value="<?= htmlspecialchars($old['titre'] ?? '') ?>" required
                                       placeholder="Ex: Bel appartement 3 pièces à Dakar">
                                <?php if (isset($errors['titre'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['titre'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type d'annonce *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="location" <?= ($old['type'] ?? '') === 'location' ? 'selected' : '' ?>>
                                        🏠 Location
                                    </option>
                                    <option value="vente" <?= ($old['type'] ?? '') === 'vente' ? 'selected' : '' ?>>
                                        🏡 Vente
                                    </option>
                                    <option value="hotel" <?= ($old['type'] ?? '') === 'hotel' ? 'selected' : '' ?>>
                                        🏨 Hôtel
                                    </option>
                                    <option value="voiture" <?= ($old['type'] ?? '') === 'voiture' ? 'selected' : '' ?>>
                                        🚗 Voiture
                                    </option>
                                </select>
                                <?php if (isset($errors['type'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['type'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="categorie_id" class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie_id" name="categorie_id">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" 
                                                <?= ($old['categorie_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">Prix *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="prix" name="prix" 
                                           value="<?= htmlspecialchars($old['prix'] ?? '') ?>" required
                                           placeholder="250000">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <?php if (isset($errors['prix'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['prix'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required
                                          placeholder="Décrivez votre bien en détail..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Localisation
                                </h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ville" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="ville" name="ville" 
                                       value="<?= htmlspecialchars($old['ville'] ?? '') ?>" required
                                       placeholder="Dakar">
                                <?php if (isset($errors['ville'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['ville'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quartier" class="form-label">Quartier</label>
                                <input type="text" class="form-control" id="quartier" name="quartier" 
                                       value="<?= htmlspecialchars($old['quartier'] ?? '') ?>"
                                       placeholder="Plateau">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="adresse" class="form-label">Adresse complète</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" 
                                       value="<?= htmlspecialchars($old['adresse'] ?? '') ?>"
                                       placeholder="123 rue Principale">
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-home me-2"></i>Caractéristiques du bien
                                </h5>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="superficie" class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" id="superficie" name="superficie" 
                                       value="<?= htmlspecialchars($old['superficie'] ?? '') ?>"
                                       placeholder="120">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="chambres" class="form-label">Nombre de chambres</label>
                                <input type="number" class="form-control" id="chambres" name="chambres" 
                                       value="<?= htmlspecialchars($old['chambres'] ?? '') ?>"
                                       placeholder="3" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="salles_bain" class="form-label">Salles de bain</label>
                                <input type="number" class="form-control" id="salles_bain" name="salles_bain" 
                                       value="<?= htmlspecialchars($old['salles_bain'] ?? '') ?>"
                                       placeholder="2" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="parking" class="form-label">Places de parking</label>
                                <input type="number" class="form-control" id="parking" name="parking" 
                                       value="<?= htmlspecialchars($old['parking'] ?? '') ?>"
                                       placeholder="1" min="0">
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-star me-2"></i>Équipements et services
                                </h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="meuble" name="meuble" 
                                           <?= ($old['meuble'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="meuble">
                                        <i class="fas fa-couch me-2"></i>Meublé
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="climatisation" name="climatisation" 
                                           <?= ($old['climatisation'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="climatisation">
                                        <i class="fas fa-snowflake me-2"></i>Climatisation
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="wifi" name="wifi" 
                                           <?= ($old['wifi'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="wifi">
                                        <i class="fas fa-wifi me-2"></i>WiFi
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="piscine" name="piscine" 
                                           <?= ($old['piscine'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="piscine">
                                        <i class="fas fa-swimming-pool me-2"></i>Piscine
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images me-2"></i>Photos
                                </h5>
                                <p class="text-muted small">Ajoutez jusqu'à 10 photos. Formats acceptés: JPG, PNG, GIF (max 5MB par photo)</p>
                            </div>
                            <div class="col-12">
                                <div class="image-upload-area" id="imageUploadArea">
                                    <div class="upload-placeholder text-center p-4 border rounded">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="mb-2">Cliquez ou glissez-déposez les photos ici</p>
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('images').click()">
                                            <i class="fas fa-folder-open me-2"></i>Choisir les fichiers
                                        </button>
                                    </div>
                                    <input type="file" class="d-none" id="images" name="images[]" multiple 
                                           accept="image/jpeg,image/png,image/gif" onchange="previewImages(event)">
                                    <div id="imagePreview" class="row mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Publier l'annonce
                                    </button>
                                    <a href="<?= APP_URL ?>/annonces" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.image-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    transition: border-color 0.3s;
}

.image-upload-area:hover {
    border-color: var(--primary-blue);
}

.upload-placeholder {
    cursor: pointer;
    transition: background-color 0.3s;
}

.upload-placeholder:hover {
    background-color: #f8f9fa;
}

.image-preview-item {
    position: relative;
    margin-bottom: 15px;
}

.image-preview-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.image-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-remove:hover {
    background: #dc3545;
    color: white;
}
</style>

<script>
function previewImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        if (file.size > 5 * 1024 * 1024) {
            alert('Le fichier ' + file.name + ' est trop gros (max 5MB)');
            continue;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-md-3';
            div.innerHTML = `
                <div class="image-preview-item">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="image-remove" onclick="removeImage(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(button) {
    const previewItem = button.closest('.col-md-3');
    previewItem.remove();
}

// Drag and drop
const uploadArea = document.getElementById('imageUploadArea');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = 'var(--primary-blue)';
});

uploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = '#dee2e6';
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = '#dee2e6';
    
    const files = e.dataTransfer.files;
    document.getElementById('images').files = files;
    previewImages({ target: { files } });
});

// Form validation
document.getElementById('annonceForm').addEventListener('submit', function(e) {
    const description = document.getElementById('description').value;
    if (description.length < 20) {
        e.preventDefault();
        alert('La description doit contenir au moins 20 caractères');
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Déposer une annonce - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
