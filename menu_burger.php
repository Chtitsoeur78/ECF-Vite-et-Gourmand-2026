<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$menuLinks = [
"Accueil" => "index.php",
"Menus du moment" => "menus_du_moment.php",
"Connexion" => "formulaire_connexion_utilisateur.php",
"Contact" => "formulaire_contact.php", 
];
?> 
<button id="openBtn" class="burger" aria-label="ouvrir le menu de navigation" aria-expanded="false" aria-controls="utilisateur_gauche" type="button">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
 </button>
<nav id="utilisateur_gauche" class="utilisateur_gauche">
    <button id="closeBtn" type="button" class="close" aria-label="Fermer le menu de navigation">X</button>
    <ul id="menu_navigation" class="menu_navigation">  
    <?php foreach ($menuLinks as $label => $url): ?>
    <?php if (
            $currentPage !== $url &&
            !($currentPage === 'espace_utilisateur.php' && $url === 'formulaire_connexion.php')
    ): ?>
            <li>
                <a href="<?= htmlspecialchars($url) ?>">
                     <?= htmlspecialchars($label) ?>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    </nav>





    