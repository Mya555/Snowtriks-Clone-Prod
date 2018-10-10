const videos = document.getElementsByClassName('videos');

if (videos){
    for (var i=0; i<videos.length; i++){
        videos[i].addEventListener('click', e => {
            if(e.target.className === 'btn btn-danger delete-item'){
            if (confirm('Es-tu sur de vouloir supprimer ?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/supprimerVideo/${id}`, {
                    method: 'DELETE'
                }).then(res => console.log('une chaine'));
            }
        }
        });
    }
}