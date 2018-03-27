<script>
var more = document.querySelector('#more')
more.addEventListner('click', function(e) {
    e.preventDefault()
    var tricks = document.querySelectorAll('.hidden')
    for (var i = 0; i < tricks.length; i++) {
        tricks[i].classList.remove("hidden");
    }
});
</script>