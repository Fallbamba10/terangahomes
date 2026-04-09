<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Modifier l'annonce</h1>
                    <p class="lead">Mettez à jour les informations de votre annonce</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <i class="fas fa-edit" style="font-size: 150px; opacity: 0.3;"></i>
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
                        <i class="fas fa-edit me-2"></i>Modifier l'annonce
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= APP_URL ?>/controllers/router.php?action=update&id=<?= $annonce['id'] ?>" enctype="multipart/form-data" id="annonceForm">
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
                                       value="<?= htmlspecialchars($annonce['titre'] ?? '') ?>" required
                                       placeholder="Ex: Bel appartement 3 pièces à Dakar">
                                <?php if (isset($errors['titre'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['titre'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="categorie_id" class="form-label">Catégorie *</label>
                                <select class="form-control" id="categorie_id" name="categorie_id" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?= $categorie['id'] ?>" 
                                                <?= ($annonce['categorie_id'] == $categorie['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($categorie['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['categorie_id'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['categorie_id'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Location Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Informations de localisation
                                </h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ville" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="ville" name="ville" 
                                       value="<?= htmlspecialchars($annonce['ville'] ?? '') ?>" required
                                       placeholder="Ex: Dakar">
                                <?php if (isset($errors['ville'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['ville'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quartier" class="form-label">Quartier</label>
                                <input type="text" class="form-control" id="quartier" name="quartier" 
                                       value="<?= htmlspecialchars($annonce['quartier'] ?? '') ?>"
                                       placeholder="Ex: Plateau">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" 
                                       value="<?= htmlspecialchars($annonce['adresse'] ?? '') ?>"
                                       placeholder="Ex: Rue 123, Dakar">
                            </div>
                        </div>
                        
                        <!-- Price and Type -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="prix" class="form-label">Prix (FCFA) *</label>
                                <input type="number" class="form-control" id="prix" name="prix" 
                                       value="<?= htmlspecialchars($annonce['prix'] ?? '') ?>" required
                                       placeholder="Ex: 50000">
                                <?php if (isset($errors['prix'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['prix'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Type de bien *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="location" <?= ($annonce['type'] === 'location') ? 'selected' : '' ?>>Location</option>
                                    <option value="vente" <?= ($annonce['type'] === 'vente') ? 'selected' : '' ?>>Vente</option>
                                </select>
                                <?php if (isset($errors['type'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['type'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type_location" class="form-label">Type de location</label>
                                <select class="form-control" id="type_location" name="type_location">
                                    <option value="">Sélectionner</option>
                                    <option value="court" <?= ($annonce['type_location'] === 'court') ? 'selected' : '' ?>>Court terme</option>
                                    <option value="long" <?= ($annonce['type_location'] === 'long') ? 'selected' : '' ?>>Long terme</option>
                                    <option value="vacances" <?= ($annonce['type_location'] === 'vacances') ? 'selected' : '' ?>>Vacances</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-home me-2"></i>Détails du bien
                                </h5>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="superficie" class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" id="superficie" name="superficie" 
                                       value="<?= htmlspecialchars($annonce['superficie'] ?? '') ?>"
                                       placeholder="Ex: 120">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="chambres" class="form-label">Nombre de chambres</label>
                                <input type="number" class="form-control" id="chambres" name="chambres" 
                                       value="<?= htmlspecialchars($annonce['chambres'] ?? '') ?>"
                                       placeholder="Ex: 3">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="salles_bain" class="form-label">Salles de bain</label>
                                <input type="number" class="form-control" id="salles_bain" name="salles_bain" 
                                       value="<?= htmlspecialchars($annonce['salles_bain'] ?? '') ?>"
                                       placeholder="Ex: 2">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="duree_minimale" class="form-label">Durée minimale</label>
                                <input type="number" class="form-control" id="duree_minimale" name="duree_minimale" 
                                       value="<?= htmlspecialchars($annonce['duree_minimale'] ?? '') ?>"
                                       placeholder="Ex: 7">
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-star me-2"></i>Équipements et services
                                </h5>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="meuble" name="meuble" value="1" 
                                           <?= ($annonce['meuble'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="meuble">Meublé</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="parking" name="parking" value="1" 
                                           <?= ($annonce['parking'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="parking">Parking</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="climatisation" name="climatisation" value="1" 
                                           <?= ($annonce['climatisation'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="climatisation">Climatisation</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="wifi" name="wifi" value="1" 
                                           <?= ($annonce['wifi'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="wifi">Wi-Fi</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required
                                          placeholder="Décrivez votre bien en détail..."><?= htmlspecialchars($annonce['description'] ?? '') ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-camera me-2"></i>Images
                                </h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="images" class="form-label">Ajouter des images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple 
                                           accept="image/*">
                                    <small class="form-text text-muted">Vous pouvez sélectionner plusieurs images</small>
                                </div>
                                
                                <?php 
                                error_log("Images brutes de l'annonce: " . ($annonce['images'] ?? 'NULL'));
                                $existingImages = json_decode($annonce['images'] ?? '[]', true);
                                error_log("Images décodées: " . print_r($existingImages, true));
                                if (!empty($existingImages)): 
                                ?>
                                    <div class="mt-3">
                                        <h6>Images actuelles</h6>
                                        <div class="row">
                                            <?php foreach ($existingImages as $index => $image): ?>
                                                <div class="col-md-3 mb-2">
                                                    <div class="position-relative">
                                                        <img src="<?= APP_URL ?>/uploads/annonces/<?= $image ?>" 
                                                             class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                                onclick="removeImage(<?= $index ?>)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                    <a href="<?= APP_URL ?>/my-annonces" class="btn btn-secondary">
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

<script>
function removeImage(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        // Logic to remove image would go here
        console.log('Remove image at index:', index);
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
