setInterval(function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/Forum/CONFIG/status.php", true); 
    xhr.send();
}, 60000); 
