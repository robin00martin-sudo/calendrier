# calendrier

1er projet réaliser en développement web en autonomie.
Le but était de réaliser un site web capable de générer un calendrier français pour 3 années consécutive à partir d'une année donnée tout en s'assurant que les jour féries fixes et mobiles soit respecté, ainsi que la capacité de le télécharger en PDF, de pouvoir choisir la formation lié au calendrier depuis une BDD très simple, et la capacité de sélectionnée dans le calendrier des jours pour les passer en violet. 

! Très chaotique, aucune architecture existante, aucune arborescence visible, vous pouvez retrouver en un seul fichier du JavaScript, du PHP et du HTML !

Point d'entrée : index.php -> qui nous donne sur un menu pour la sélection d'une année et d'une formation.

calendrier.php permet de gérer la page web qui affichera les calendriers et les télécharger en PDF.
function_optimize.php permet de générer le calendrier et les jour féries en se basant sur pâques pour les jours mobiles.
