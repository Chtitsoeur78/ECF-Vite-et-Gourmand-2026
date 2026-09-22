// Récupération des cinq filtres
const filtrePrix = document.querySelector("#filtre_prix");
const filtreTheme = document.querySelector("#filtre_theme");
const filtreRegime = document.querySelector("#filtre_regime");
const filtrePersonnes = document.querySelector("#filtre_personnes");
const filtreFourchettePrix = document.querySelector("#filtre_fourchette_prix");

// Récupération de toutes les cartes de menus
const cartesMenus = document.querySelectorAll(".carte_menu");

// Récupération du message affiché si aucun menu ne convient
const messageAucunMenu = document.querySelector("#message_aucun_menu");

// Récupération du bouton de réinitialisation
const boutonReinitialiser = document.querySelector("#reinitialiser_filtres");

// Fonction qui filtre les menus
function filtrerMenus() {
  // Valeurs choisies ou saisies par l'utilisateur
  const prixMaximal = Number(filtrePrix.value);
  const themeChoisi = filtreTheme.value;
  const regimeChoisi = filtreRegime.value;
  const nombrePersonnes = Number(filtrePersonnes.value);
  const fourchetteChoisie = filtreFourchettePrix.value;

  // Valeurs utilisées si aucune fourchette n'est choisie
  let prixMinimumFourchette = 0;
  let prixMaximumFourchette = Infinity;

  // Séparation des deux prix de la fourchette
  if (fourchetteChoisie !== "") {
    const limites = fourchetteChoisie.split("-");

    prixMinimumFourchette = Number(limites[0]);
    prixMaximumFourchette = Number(limites[1]);
  }

  // Compteur des menus visibles
  let nombreMenusVisibles = 0;

  // Vérification de chaque carte
  cartesMenus.forEach((carteMenu) => {
    // Lecture des informations data-* du menu
    const prixMenu = Number(carteMenu.dataset.prix);

    const themeMenu = carteMenu.dataset.theme;

    const regimeMenu = carteMenu.dataset.regime;

    const personnesMinimum = Number(carteMenu.dataset.personnesMinimum);

    // Premier critère : prix maximal
    const prixCorrespond = filtrePrix.value === "" || prixMenu <= prixMaximal;

    // Deuxième critère : thème
    const themeCorrespond = themeChoisi === "" || themeMenu === themeChoisi;

    // Troisième critère : régime
    const regimeCorrespond = regimeChoisi === "" || regimeMenu === regimeChoisi;

    // Quatrième critère : nombre de convives
    const personnesCorrespondent =
      filtrePersonnes.value === "" || personnesMinimum <= nombrePersonnes;

    // Cinquième critère : fourchette de prix
    const fourchetteCorrespond =
      fourchetteChoisie === "" ||
      (prixMenu >= prixMinimumFourchette && prixMenu <= prixMaximumFourchette);

    // Le menu doit respecter tous les critères renseignés
    const menuCorrespond =
      prixCorrespond &&
      themeCorrespond &&
      regimeCorrespond &&
      personnesCorrespondent &&
      fourchetteCorrespond;

    // Affichage ou masquage du menu
    carteMenu.hidden = !menuCorrespond;

    // Comptage des menus visibles
    if (menuCorrespond) {
      nombreMenusVisibles++;
    }
  });

  // Le message apparaît seulement si aucun menu n'est visible
  messageAucunMenu.hidden = nombreMenusVisibles > 0;
}

// Filtrage immédiat lors d'une saisie ou d'un choix
filtrePrix.addEventListener("input", filtrerMenus);

filtreTheme.addEventListener("change", filtrerMenus);

filtreRegime.addEventListener("change", filtrerMenus);

filtrePersonnes.addEventListener("input", filtrerMenus);

filtreFourchettePrix.addEventListener("change", filtrerMenus);

// Réinitialisation de tous les filtres
boutonReinitialiser.addEventListener("click", () => {
  filtrePrix.value = "";
  filtreTheme.value = "";
  filtreRegime.value = "";
  filtrePersonnes.value = "";
  filtreFourchettePrix.value = "";

  // Réaffichage de tous les menus
  filtrerMenus();
});
