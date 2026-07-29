
    function updateCalendar() {
      const now = new Date();

      // Tableaux des jours et mois en français
      const jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
      const mois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

      // Récupérer le jour et la date
      const jour = jours[now.getDay()];
      const date = now.getDate() + " " + mois[now.getMonth()] + " " + now.getFullYear();

      // Mettre à jour le HTML
      document.getElementById("day").textContent = jour;
      document.getElementById("date").textContent = date;
    }

    // Initialiser le calendrier
    updateCalendar();

    // Calculer le temps restant jusqu'au prochain changement de jour (minuit)
    function scheduleNextUpdate() {
      const now = new Date();
      const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
      const msUntilMidnight = tomorrow - now;
      setTimeout(() => {
        updateCalendar();
        scheduleNextUpdate(); // Replanifie pour le jour suivant
      }, msUntilMidnight);
    }

    scheduleNextUpdate();
 
    function afficherHeureParis() {
    const now = new Date();

    //Format heure + minute + seconde pour le fuseau Paris (Europe)
    const heureParis = new Intl.DateTimeFormat("fr-FR", {
    hour: "2-digit", 
    minute: "2-digit", 
    second: "2-digit", 
    timeZone: "Europe/Paris"
    }).format(now);

    document.getElementById("heure").textContent = heureParis;
    }

    //Mettre à jour chaque seconde
    afficherHeureParis();
    setInterval(afficherHeureParis, 1000);











