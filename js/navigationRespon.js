var a = document.getElementById('nav-menu');
var x = 0;

    function openNav() {
    if (x == 0) {
        a.style.display = "flex";
        x = 1;
    } else {
        a.style.display = "none";
                
        x = 0;
    }
}