const videos = document.getElementById('videos');

if (videos){
    videos.addEventListener('click', e => {
        if(e.target.className === 'btn btn-danger delete-item'){
        if (confirm('Es-tu sur de vouloir supprimer ?')) {
            const id = e.target.getAttribute('data-id');
            fetch('/supprimerVideo/${id}', {
                method: 'DELETE'
            }).then(res => window.location.reload());
        }
    }
    });
}