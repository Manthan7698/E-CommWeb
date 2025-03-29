// Live reload functionality
function checkForChanges() {
    fetch('check-changes.php')
        .then(response => response.json())
        .then(data => {
            if (data.changed) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error checking for changes:', error));
}

// Check for changes every 2 seconds
setInterval(checkForChanges, 2000); 