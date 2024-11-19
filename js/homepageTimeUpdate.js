document.addEventListener("DOMContentLoaded", function() {
    function updateTime() {
        const dateTimeElement = document.querySelector('.date-time p');
        const now = new Date();
        dateTimeElement.textContent = now.toLocaleString();
    }

    setInterval(updateTime, 1000);
});
