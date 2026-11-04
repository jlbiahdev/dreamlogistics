
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
