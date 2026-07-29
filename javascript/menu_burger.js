console.log("menu burger chargé");

const utilisateurGauche = document.getElementById("utilisateur_gauche");
const openBtn = document.getElementById("openBtn");
const closeBtn = document.getElementById("closeBtn");

openBtn.onclick = openUtilisateurGauche;
closeBtn.onclick = closeUtilisateurGauche;

function openUtilisateurGauche() {
  utilisateurGauche.classList.add("active");
}

function closeUtilisateurGauche() {
  utilisateurGauche.classList.remove("active");
}
console.log(utilisateurGauche);
console.log(openBtn);
console.log(closeBtn);
