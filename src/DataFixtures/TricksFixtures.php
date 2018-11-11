<?php

namespace App\DataFixtures;

use App\Entity\MediaVideo;
use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        foreach ($this->getTricksData() as [$name, $description, $groupe, $url ]){
            $trick = new Tricks();
            $video = new MediaVideo();

            $trick->setName($name);
            $trick->setDescription($description);
            $trick->setGroupe($groupe);
            $video->setUrl($url);
            $video->setTrick($trick);

            $manager->persist($video);
            $manager->persist($trick);
        }
        $manager->flush();
    }
    private function getTricksData(): array {
        return [
            ['Air-to-fakie', 'Fakie est une chose assez simple à faire, mais il y a plusieurs façons de le faire. Dans sa forme la plus simple, vous prenez l’air et tournez à 180 ° pour atterrir à la manière de Fakie. Bien que le concept soit très simple, l\'exécution d\'un air à Fakie peut être un peu plus complexe.', 'Straight airs', 'https://www.youtube.com/watch?v=ephG_zaausE' ],
            ['Ollie', 'Le Ollie est une impulsion  avec déformation de la planche qui permet de faire un saut, comme un ollie de skate, mais en beaucoup plus facile car les deux pieds sont attachés sur la board.', 'Straight airs' , 'https://www.youtube.com/watch?v=SXFQCgw-V_k' ],
            ['Nollie', 'Un nollie est une variante de l\' ollie , où le snowborder utilise le pied avant pour pousser le nez de la planche et le pied arrière est glissé vers l\'arrière pour se décoller du sol et son pied avant pour glisser vers l’avant.', 'Straight airs', 'https://www.youtube.com/watch?v=H_tSuAipjWc' ],
            ['Shifty', 'Figure aérienne dans laquelle un snowboarder se tord le corps afin de déplacer ou de faire pivoter sa planche de 90 ° environ par rapport à sa position normale située sous eux, puis la ramène dans sa position initiale avant d\'atterrir. Cette astuce peut être exécutée à l’avant ou à l’arrière, mais également en variation avec d’autres astuces et rotations.', 'Straight airs', 'https://www.youtube.com/watch?v=RVoP-UFewdQ' ],
            ['Bloody Dracula', 'Un tour dans lequel le cavalier saisit la queue du tableau avec les deux mains. La main arrière saisit la planche comme elle le ferait lors d\'une prise de queue régulière, mais la main avant tendit aveuglément vers la planche derrière le dos des coureurs.', 'Grabs', 'https://www.youtube.com/watch?v=UU9iKINvlyU' ],
            ['Chicken salad', 'La main arrière pénètre entre les jambes et saisit le bord du talon entre les fixations tandis que la jambe avant est désossée. Le poignet est tourné vers l\'intérieur pour compléter la saisie', 'Grabs', 'https://www.youtube.com/watch?v=TTgeY_XCvkQ' ],
            ['Crail', 'La main arrière saisit le bord de l\'orteil devant le pied avant tandis que la jambe arrière est désossée. Alternativement, certains considèrent que toute prise à l’arrière devant le pied avant sur le bord des orteils est une prise au crail, classant celle-ci au nez comme un "crabe du nez" ou "vrai crail".', 'Grabs', 'https://www.youtube.com/watch?v=eTx2uVcbLzM' ],
            ['Lien air', 'Lors de la transition vers l’air frontal, le snowboarder saisit la talonnière devant ou derrière la fixation principale avec sa main principale. Pour que ce soit un lien, le tableau ne peut pas être modifié et doit rester à plat. L\'origine du nom de l\'astuce est l\'orthographe inversée du prénom du skateur Neil Blender.', 'Grabs', 'https://www.youtube.com/watch?v=QZfjThF8if4' ],
            ['Bluntslide', 'Une glissière a été réalisée à un endroit où le pied avant du cycliste passe au-dessus du rail lors de l’approche, son snowboard se déplaçant perpendiculairement et le pied tiré directement au-dessus du rail ou de tout autre obstacle (comme un talus glissant). Lors de l\'exécution d\'un bluntlide sur le front, le snowboarder fait face à la montée. Lors de l\'exécution d\'un bluntlide arrière, le snowboarder fait face à la descente.', 'Slides', 'https://www.youtube.com/watch?v=O5DpwZjCsgA'],
            ['Tailslide', 'Semblable à un boardslide ou un lipslide, mais seule la queue du tableau se trouve sur le trait. Les dérapages appropriés sont effectués avec la fonction directement sous le pied arrière ou plus loin vers la queue. ', 'Slides', 'https://www.youtube.com/watch?v=U-TtF3ZfsQ0' ]
        ];
    }
}
