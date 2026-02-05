
document.addEventListener("DOMContentLoaded", function () {
    const ajaxContainer = document.getElementById("openingstijden-volgende-ajax");
    if (!ajaxContainer) return;

    const url = openingstijden_ajax_object.ajax_url +
        "?action=haal_volgende_uitzonderingen_op&nonce=" +
        encodeURIComponent(openingstijden_ajax_object.nonce);

    fetch(url)
        .then(function (res) { return res.text(); })
        .then(function (data) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(data, "text/html");
            var scripts = doc.querySelectorAll("script");
            scripts.forEach(function (s) { s.remove(); });
            ajaxContainer.textContent = "";
            while (doc.body.firstChild) {
                ajaxContainer.appendChild(doc.body.firstChild);
            }
        });
});
