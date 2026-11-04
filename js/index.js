
var navLinks = document.getElementById("navLinks");

function showMenu() {
    navLinks.style.display = "block"
}
function hideMenu() {
    navLinks.style.display = "none"
}

// Create the script tag, set the appropriate attributes
var script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap';
script.async = true;

// Attach your callback function to the `window` object
window.initMap = function() {
  // JS API is loaded and available
};

// Append the 'script' element to 'head'
document.head.appendChild(script);

//Annule le rechargement du formulaire
$("#contact-form").submit(function(e) {
    e.preventDefault();
    sendEmail();
});

//Annule le rechargement du formulaire
$("#comment-form").submit(function(e) {
    e.preventDefault();
    sendComment();
});

//Fermeture des message boxes
$("#message-box .fa-times").click(function(e) {
    setTimeout(() => {
        $("#message-box").css("display", "none")
    }, 1000);
});