<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/function_optimize.php';

$erreur = null;
try {
    // Récupérer toutes les formations triées par intitulé
    $formations = getDb()->query("SELECT id, intitule FROM formations ORDER BY intitule ASC")
                         ->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $formations = [];
    $erreur = "Impossible de contacter la base de données.";
}

// Formation éventuellement présélectionnée (retour depuis calendrier.php)
$selectedFormationId = filter_input(INPUT_GET, 'formation', FILTER_VALIDATE_INT) ?: '';

// Année par défaut : année demandée si valide, sinon année courante ramenée dans la plage
$startYear = lireAnnee($_GET['year'] ?? null)
          ?? max(anneeMin(), min((int) date('Y'), anneeMax()));

if (($_GET['erreur'] ?? '') === 'annee') {
    $erreur = "Année invalide : choisissez une année entre " . anneeMin() . " et " . anneeMax() . ".";
} elseif (($_GET['erreur'] ?? '') === 'formation') {
    $erreur = "Formation introuvable : choisissez une formation dans la liste.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de calendrier</title>
    <link rel="stylesheet" href="styleIndex.css">
</head>
<body>
    <div class="form">
        <form method="GET" action="calendrier.php" target="_blank">
            <div class="title">Bienvenue</div>
            <div class="subtitle">Créons votre calendrier !</div>
            <?php if ($erreur): ?>
                <p role="alert" style="color:#ff8a8a;margin:0 0 12px"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>

            <div class="input-container ic1">
                <input type="number" class="input" id="year" name="year"
                       value="<?= htmlspecialchars((string) $startYear) ?>"
                       min="<?= anneeMin() ?>" max="<?= anneeMax() ?>" required placeholder=" ">
                <div class="cut1"></div>
                <label for="year" class="placeholder">Année de départ :</label>
            </div>

            <div class="input-container ic2">
                <select name="formation" id="formation" class="input" required>
                    <option value="">-- Sélectionnez --</option>
                    <?php foreach ($formations as $formation): ?>
                        <option value="<?= htmlspecialchars((string) $formation['id']) ?>"
                            <?= ($formation['id'] == $selectedFormationId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($formation['intitule']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="cut2"></div>
                <label for="formation" class="placeholder">Choisissez une formation :</label>
            </div>

            <button class="submit" type="submit">Générer le calendrier</button>
            <p class="copyright">&copy; 2025 Greta-CFA-Bayonne</p>
        </form>
    </div>
</body>
</html>
