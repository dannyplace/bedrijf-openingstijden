
document.addEventListener("DOMContentLoaded", function () {
    const ajaxContainer = document.getElementById("openingstijden-volgende-ajax");
    if (!ajaxContainer) return;

    const params = new URLSearchParams({
        action: 'haal_volgende_uitzonderingen_op',
        nonce: openingstijden_ajax_object.nonce
    });

    fetch(openingstijden_ajax_object.ajax_url + "?" + params.toString())
        .then((res) => res.text())
        .then((data) => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const newContent = doc.body.firstChild;
            if (newContent) {
                ajaxContainer.replaceWith(newContent);
            }
        });
});
