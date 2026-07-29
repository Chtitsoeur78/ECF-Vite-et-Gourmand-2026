const form = document.getElementById("formulaire_inscription");
const message = document.getElementById("message");

form.addEventListener("submit", function(event){
const email = document.getElementById("email").value;
const password = document.getElementById("password").value;

if(!email.includes("@")) {
event.preventDefault();
message.textContent = "Veuillez entrer une adresse email valide svp";
message.style.color="red";
return;
}

if(password.length <10) {
event.preventDefault();
message.textContent = "Le mot de passe doit contenir au moins 10 caractères"; 
message.style.color="red";
return;
}
});