// assets/js/script.js

// Enkel JS for å vise en melding når siden lastes
document.addEventListener('DOMContentLoaded', function() {
    alert('Velkommen til jobbsøkesystemet!');
});

// Simulert AJAX-forespørsel (eksempel, ikke nødvendig for dette prosjektet)
function simulateAjaxRequest() {
    return new Promise(function(resolve) {
        setTimeout(function() {
            resolve('AJAX-forespørsel fullført!');
        }, 1000);
    });
}

// Eksempel på bruk av AJAX-forespørsel
document.getElementById('ajaxButton').addEventListener('click', function() {
    simulateAjaxRequest().then(function(response) {
        alert(response);
    });
});
