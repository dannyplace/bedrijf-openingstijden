
document.addEventListener("DOMContentLoaded", function () {
    const ajaxContainer = document.getElementById("openingstijden-volgende-ajax");
    if (!ajaxContainer) return;

    fetch(openingstijden_ajax_object.ajax_url + "?action=haal_volgende_uitzonderingen_op")
        .then((res) => res.text())
        .then((data) => {
            ajaxContainer.innerHTML = data;
        });
});
