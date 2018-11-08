var more = document.querySelector('#more')
more.addEventListener('click', function(e) {
    e.preventDefault()
    var tricks = document.querySelectorAll('.hidden')
    for (var i = 0; i < tricks.length; i++) {
        tricks[i].classList.remove("hidden");
    }
    if ($(".more:hidden").length == 0) {
        $("#more").fadeOut('slow');
    }
})