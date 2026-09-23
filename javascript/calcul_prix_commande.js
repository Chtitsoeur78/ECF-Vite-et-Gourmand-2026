const formulaireCommande = document.getElementById("formulaire_commande");

const champNombrePersonnes = document.getElementById("nombre_personnes");

const minimumPersonnes = Number(champNombrePersonnes.min);
const seuilReduction = minimumPersonnes + 5;
const tauxReduction = 0.1;

const affichagePrixInitial = document.getElementById("affichage_prix_initial");
const affichageReduction = document.getElementById("affichage_reduction");
const affichagePrixMenu = document.getElementById("affichage_prix_menu");
const affichagePrixTotal = document.getElementById("affichage_prix_total");
const prixParPersonne = Number(formulaireCommande.dataset.prixParPersonne);

let fraisLivraison = Number(formulaireCommande.dataset.fraisLivraison);
const champCommune = document.getElementById("livraison_commune");
const affichageFraisLivraison = document.getElementById(
  "affichage_frais_livraison",
);

function calculerPrix() {
  const nombrePersonnes = champNombrePersonnes.valueAsNumber;

  if (Number.isNaN(nombrePersonnes) || nombrePersonnes < minimumPersonnes) {
    affichagePrixMenu.textContent = "—";
    affichagePrixTotal.textContent = "—";

    return;
  }

  const prixMenuInitial = nombrePersonnes * prixParPersonne;

  const reduction =
    nombrePersonnes >= seuilReduction ? prixMenuInitial * tauxReduction : 0;

  const prixMenu = prixMenuInitial - reduction;

  const prixTotal = prixMenu + fraisLivraison;

  affichagePrixInitial.textContent =
    prixMenuInitial.toLocaleString("fr-FR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }) + " €";

  if (reduction > 0) {
    affichageReduction.textContent =
      "--" +
      reduction.toLocaleString("fr-FR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }) +
      " €";
  } else {
    affichageReduction.textContent = "--";
  }

  affichagePrixMenu.textContent =
    prixMenu.toLocaleString("fr-FR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }) + " €";

  affichagePrixTotal.textContent =
    prixTotal.toLocaleString("fr-FR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }) + " €";
}

async function actualiserFraisLivraison() {
  const commune = champCommune.value.trim();

  if (commune === "") {
    affichageFraisLivraison.textContent = "—";
    affichagePrixTotal.textContent = "—";
    return;
  }

  try {
    const reponse = await fetch(
      "rechercher_frais_de_livraison.php?commune=" +
        encodeURIComponent(commune),
    );

    const texteReponse = await reponse.text();

    console.log("Réponse du serveur :", texteReponse);

    const resultat = JSON.parse(texteReponse);

    if (!reponse.ok || !resultat.succes) {
      affichageFraisLivraison.textContent = "Commune introuvable";
      affichagePrixTotal.textContent = "—";
      return;
    }

    fraisLivraison = Number(resultat.frais_livraison);

    affichageFraisLivraison.textContent =
      fraisLivraison.toLocaleString("fr-FR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }) + " €";

    calculerPrix();
  } catch (erreur) {
    console.error("Erreur lors de la recherche des frais :", erreur);
    affichageFraisLivraison.textContent = "Erreur de calcul";
    affichagePrixTotal.textContent = "—";
  }
}

champNombrePersonnes.addEventListener("input", calculerPrix);
champCommune.addEventListener("change", actualiserFraisLivraison);
calculerPrix();
