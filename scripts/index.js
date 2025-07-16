// scripts/index.js

const tabModules = {
  "tab-logs": {
    init: window.logsInit || function(){},
    destroy: window.logsDestroy || function(){}
  },
  "tab-check": {
    init: window.portScannerInit || function(){},
    destroy: window.portScannerDestroy || function(){}
  }
  // Ajoute ici tes futurs onglets !
};

document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".tab");
  const contents = document.querySelectorAll(".tab-content");

  let currentTab = "tab-logs"; // Par défaut

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      if(tab.classList.contains("active")) return; // Pas de double init si déjà actif

      tabs.forEach(t => t.classList.remove("active"));
      contents.forEach(c => c.classList.remove("active"));

      tab.classList.add("active");
      const targetId = tab.getAttribute("data-tab");
      document.getElementById(targetId).classList.add("active");

      // Détruit le module précédent
      if (tabModules[currentTab] && typeof tabModules[currentTab].destroy === 'function') {
        tabModules[currentTab].destroy();
      }
      // Initialise le module affiché
      if (tabModules[targetId] && typeof tabModules[targetId].init === 'function') {
        tabModules[targetId].init();
      }
      currentTab = targetId;
    });
  });

  // Appel initial à l’ouverture de la page
  if (tabModules[currentTab] && typeof tabModules[currentTab].init === 'function') {
    tabModules[currentTab].init();
  }
});
