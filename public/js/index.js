$('.biranje').on('click', function(){
    $('.biranje').removeClass('selected');
    $(this).addClass('selected');
});

/* Toggle between showing and hiding the navigation menu links when the user clicks on the hamburger menu / bar icon */
function myFunction(x) {
    var y = document.getElementById(x);
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
function sakriveno(x){
    var y = document.getElementsByClassName("sakriveno").item(x-1);
    if (y.style.display === "flex") {
        y.style.display = "none";
    } else {
        y.style.display = "flex";
    }
}

function popup(x){
    var y = document.getElementsByClassName("popup").item(x-1);
    if (y.style.display === "flex") {
        y.style.display = "none";
    } else {
        y.style.display = "flex";
    }
}
