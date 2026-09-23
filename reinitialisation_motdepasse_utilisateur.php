<?php

session_start();

if (isset($_SESSION["id_utilisateur"])) {
    header("Location: espace_utilisateur.php");
    exit();
}

// Récupération du jeton présent dans le lien envoyé par e-mail
$jeton = $_GET["jeton"] ?? "";

// Vérification du format du jeton
if (strlen($jeton) !== 64 || !ctype_xdigit($jeton)) {
    echo "Le lien de réinitialisation n'est pas valide.";
    exit;
}

?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- Balises meta indispensables -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Liaison avec les pages externes de CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/menu_burger.css">
    <link rel="stylesheet" href="css/motdepasse_oublie.css">

    <title>
      Vite & Gourmand - Formulaire de réinitialisation du mot de passe
    </title>
  </head>

  <body>
    <header class="header_conteneur">
      <section>
        <?php include "menu_burger.php"; ?>
      </section>

      <section>
        <h1>Réinitialiser son mot de passe</h1>
      </section>

      <section class="logo">
        <img
          src="images/logo1.png"
          alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"
        >
      </section>
    </header>

    <main>
      <form
        action="traitement_reinitialisation_motdepasse_utilisateur.php"
        method="post"
      >
        <fieldset>
          <legend class="legend">
            Choix du nouveau mot de passe
          </legend>

          <!-- Transmission du jeton au fichier de traitement -->
          <input
            type="hidden"
            name="jeton"
            value="<?= htmlspecialchars($jeton, ENT_QUOTES, "UTF-8") ?>"
          >

          <p>
            <label for="mot_de_passe">
              NOUVEAU MOT DE PASSE * :
            </label>

            <input
              type="password"
              required
              minlength="10"
              id="mot_de_passe"
              name="mot_de_passe"
              autocomplete="new-password"
            >
          </p>

          <p>
            <label for="mot_de_passe_confirme">
              CONFIRMATION DU MOT DE PASSE * :
            </label>

            <input
              type="password"
              required
              minlength="10"
              id="mot_de_passe_confirme"
              name="mot_de_passe_confirme"
              autocomplete="new-password"
            >
          </p>

          <p>
            <button type="submit">
              Modifier mon mot de passe
            </button>
          </p>
        </fieldset>
      </form>
    </main>

    <!-- Liaison avec la page externe de JavaScript -->
    <script src="javascript/menu_burger.js"></script>
  </body>
</html>