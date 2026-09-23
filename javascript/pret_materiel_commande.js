const formulairePretMateriel = document.getElementById("formulaire_commande");
const affichagePretMateriel = document.getElementById(
  "affichage_pret_materiel",
);
const quantitesPlats = document.querySelectorAll(".quantite_plat");

function actualiserPretMateriel() {
  const platPrincipalAvecMateriel =
    Number(formulaireCommande.dataset.pretMaterielPlatPrincipal) === 1;

  let pretMaterielNecessaire = platPrincipalAvecMateriel;

  quantitesPlats.forEach(function (champQuantite) {
    const quantite = Number(champQuantite.value);
    const platAvecMateriel = Number(champQuantite.dataset.pretMateriel) === 1;

    if (quantite > 0 && platAvecMateriel) {
      pretMaterielNecessaire = true;
    }
  });

  if (pretMaterielNecessaire) {
    affichagePretMateriel.textContent = "Oui";
  } else {
    affichagePretMateriel.textContent = "Non";
  }
}

quantitesPlats.forEach(function (champQuantite) {
  champQuantite.addEventListener("input", actualiserPretMateriel);
});

actualiserPretMateriel();
