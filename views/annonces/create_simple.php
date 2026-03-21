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
                    <i class="fas fa-plus" style="font-size: 150px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create Form Section -->
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
                    <form method="POST" action="<?= APP_URL ?>/annonces/create" enctype="multipart/form-data">
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Titre de l'annonce *</label>
                                <input type="text" class="form-control" name="titre" required
                                       placeholder="Ex: Bel appartement à Dakar">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type d'annonce *</label>
                                <select class="form-select" name="type" required>
                                    <option value="">Choisir...</option>
                                    <option value="location">🏠 Location</option>
                                    <option value="vente">🏡 Vente</option>
                                    <option value="hotel">🏨 Hôtel</option>
                                    <option value="voiture">🚗 Voiture</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="4" required
                                      placeholder="Décrivez votre bien en détail..."></textarea>
                        </div>

                        <!-- Price and Category -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Prix *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="prix" required
                                           placeholder="250000">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" name="categorie_id">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($categories ?? [] as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Ville *</label>
                                <input type="text" class="form-control" name="ville" required
                                       placeholder="Dakar">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quartier</label>
                                <input type="text" class="form-control" name="quartier"
                                       placeholder="Plateau">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control" name="adresse"
                                       placeholder="Adresse complète">
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" name="superficie"
                                       placeholder="120">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Chambres</label>
                                <input type="number" class="form-control" name="chambres"
                                       placeholder="3">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Salles de bain</label>
                                <input type="number" class="form-control" name="salles_bain"
                                       placeholder="2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Durée minimale</label>
                                <input type="text" class="form-control" name="duree_minimale"
                                       placeholder="1 mois">
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mb-4">
                            <label class="form-label">Équipements et services</label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="parking" value="1">
                                        <label class="form-check-label">Parking</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="meuble" value="1">
                                        <label class="form-check-label">Meublé</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="climatisation" value="1">
                                        <label class="form-check-label">Climatisation</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="wifi" value="1">
                                        <label class="form-check-label">WiFi</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="piscine" value="1">
                                        <label class="form-check-label">Piscine</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="mb-4">
                            <label class="form-label">Photos (jusqu'à 5)</label>
                            <div class="border rounded p-3 text-center" id="imagePreview">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                <p class="text-muted">Cliquez pour ajouter des photos</p>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*"
                                       style="display: none;" id="imageInput">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Publier l'annonce
                            </button>
                            <a href="<?= APP_URL ?>/annonces" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('imagePreview').addEventListener('click', function() {
    document.getElementById('imageInput').click();
});

document.getElementById('imageInput').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('imagePreview');
    
    if (files.length > 0) {
        let html = '<div class="row">';
        for (let i = 0; i < Math.min(files.length, 5); i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                html += `
                    <div class="col-md-3 mb-2">
                        <img src="${e.target.result}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                    </div>
                `;
                if (i === Math.min(files.length, 5) - 1) {
                    html += '</div>';
                    preview.innerHTML = html;
                }
            };
            
            reader.readAsDataURL(file);
        }
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Déposer une annonce - TerangaHomes';
include __DIR__ . '/../layouts/seloger.php';
?>
