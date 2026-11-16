<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/function_optimize.php';

// --- Validation des paramètres (côté serveur) ---------------------------------
if (isset($_GET['year']) && $_GET['year'] !== '') {
    $startYear = lireAnnee($_GET['year']);
    if ($startYear === null) {                       // hors plage ou non numérique
        header('Location: index.php?erreur=annee');
        exit;
    }
} else {
    $startYear = max(anneeMin(), min((int) date('Y'), anneeMax()));
}

$formationId = filter_input(INPUT_GET, 'formation', FILTER_VALIDATE_INT);
$selectedFormation = null;
try {
    if ($formationId) {
        $stmt = getDb()->prepare("SELECT id, intitule FROM formations WHERE id = ?");
        $stmt->execute([$formationId]);
        $selectedFormation = $stmt->fetch() ?: null;
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit("Impossible de contacter la base de données.");
}
if ($selectedFormation === null) {                   // formation absente ou inconnue
    header('Location: index.php?erreur=formation');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Calendrier <?= $startYear ?> - <?= $startYear + 2 ?></title>
    <style>
        <?php include __DIR__ . '/styleCalendrier.css'; ?>
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>
    <h1>Calendrier de <?= $startYear ?> à <?= $startYear + 2 ?></h1>
    <header class="navbar">
        <div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#year<?= $startYear ?>"><?= $startYear ?></a></li>
                <li><a href="#year<?= $startYear + 1 ?>"><?= $startYear + 1 ?></a></li>
                <li><a href="#year<?= $startYear + 2 ?>"><?= $startYear + 2 ?></a></li>
                <li><a href="#download">Télécharger</a></li>
            </ul>
        </div>
    </header>

    <div class="calendar-container">
        <?php
            for ($year = $startYear; $year <= $startYear + 2; $year++) {
                generateCalendarAnnuaire($year, $selectedFormation);
            }
        ?>
    </div>
    <input class="javaScript" type="button" id="download" onclick="exportCalendarsToPDF()" value="Exporter en PDF"/>

    <div id="spinner" class="spinner"> Génération du PDF en cours...</div>

    <footer class="copyright">&copy; 2025 Greta-CFA-Bayonne </footer>

<script>
/* ===== Export PDF : capture de chaque calendrier puis assemblage (A4 paysage) ===== */
async function exportCalendarsToPDF() {
    const spinner = document.getElementById('spinner');
    const calendars = document.querySelectorAll('.calendrier');

    if (!spinner || calendars.length === 0) {
        alert('Aucun calendrier trouvé.');
        return;
    }

    spinner.style.display = 'block';

    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('l', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        for (let i = 0; i < calendars.length; i++) {
            try {
                const canvas = await html2canvas(calendars[i], { scale: 2 });
                const imgData = canvas.toDataURL('image/jpeg', 1.0);

                const ratio = Math.min(pageWidth / canvas.width, pageHeight / canvas.height);
                const imgWidth = canvas.width * ratio;
                const imgHeight = canvas.height * ratio;
                const x = (pageWidth - imgWidth) / 2;
                const y = (pageHeight - imgHeight) / 2;

                if (i !== 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', x, y, imgWidth, imgHeight, '', 'FAST');
            } catch (err) {
                console.error(`Erreur lors de la capture du calendrier ${i + 1} :`, err);
            }
        }

        pdf.save("calendrier.pdf");
    } catch (err) {
        console.error("Erreur lors de la génération du PDF :", err);
        alert("Une erreur est survenue lors de l'exportation du PDF.");
    } finally {
        spinner.style.display = 'none';
    }
}

/* ===== Sélection d'un jour : les 3 cellules du jour passent en violet ===== */
document.addEventListener("DOMContentLoaded", function () {
    // Les cellules « empty » (30 février, 31 avril…) ne sont pas sélectionnables
    document.querySelectorAll(".annuaire td:not(.empty)").forEach(cell => {
        cell.addEventListener("click", function () {
            const cellsInRow = [...this.parentElement.children];
            const index = cellsInRow.indexOf(this);
            const startIndex = index - (index % 3);          // début du groupe de 3
            const group = cellsInRow.slice(startIndex, startIndex + 3);

            const isSelected = group.every(c => c.classList.contains("selected-day"));
            group.forEach(c => c.classList.toggle("selected-day", !isSelected));
        });
    });
});
</script>
</body>
</html>
