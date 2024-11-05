export function updateProgressBar() {
    setInterval(() => {
        fetch('/api/get-progress')
            .then(response => response.json())
            .then(data => {
                console.log('updateProgressBar', data)
                // const progressBar = document.getElementById('progress-bar');
                // progressBar.style.width = data.progress + '%';
                // progressBar.innerText = Math.round(data.progress) + '%';
            });
    }, 1000);
}

window.updateProgressBar = updateProgressBar;
