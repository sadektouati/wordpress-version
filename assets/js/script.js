window.onload = function () {
  document.addEventListener("click", function (event) {
    const bouton = event.target.closest("._vw_mettre_a_jour");
    if (event.target.closest("._vw_mettre_a_jour")) {
      //proteger contre les cliques répétés avant le reponse du serveur
      bouton.disabled = true;

      fetch(
        my_script_data.ajax_url +
          "?action=manual_data_update&type=" +
          my_script_data.type +
          "&version=" +
          my_script_data.version +
          "&color=" +
          my_script_data.color
      )
        .then((response) => response.json())
        .then((data) => {
          bouton.closest("._vw_container").outerHTML = data["html"];
        })
        .catch((error) => {
          bouton.disabled = false;
          console.error("Fetch error:", error);
        });
    }
  });
};
