//Bouton loadMore pour les commentaires

var more = document.querySelector('#more')
more.addEventListener('click', function(e) {
    e.preventDefault()
    var commentaire = document.querySelectorAll('.hidden')
    for (var i = 0; i < commentaire.length; i++) {
        commentaire[i].classList.remove("hidden");
    }
    if ($(".more:hidden").length == 0) {
        $("#more").fadeOut('slow');
    }
})
