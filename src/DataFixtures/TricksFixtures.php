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
            ['Mule kick', 'Une adaptation précoce de snowboarder de la méthode de skateboarders air. Souvent appelé un air Toyota, après sa posture similaire à la campagne publicitaire Toyota "Oh What A Feeling" mettant en vedette des personnes qui sautent du sol, se produisant en se jetant dans un arc arrière aérien avec les jambes fléchies jusqu\'à se taper dans le dos comme avec le ski backscratcher air, les deux bras penchés en arrière sur la tête et ne saisissant pas le conseil. Encore occasionnellement vu et largement considéré comme terrible.', 'Grabs', 'https://www.youtube.com/watch?v=H4i6BfRa7jQ'],
            ['Taipan air', 'La main arrière saisit le bord de l\'orteil juste devant le pied arrière. Cependant, le bras doit faire le tour de l’extérieur du genou. La planche est ensuite tirée derrière le cavalier (modifié). Le nom Taipan est un porte-manteau de queue / air japonais.', 'Grabs', 'https://www.youtube.com/watch?v=6a_SHm9voNI' ],
            ['Tindy', 'La pince tindy est une pince controversée, et le nom est un baguette de «queue» et «indy». La main traînante saisit entre la reliure arrière et la queue sur le bord des orteils. ', 'Grabs', 'https://www.youtube.com/watch?v=T-ZWwn4ISN0' ],
            ['Tailslide', 'Semblable à un boardslide ou un lipslide, mais seule la queue du tableau se trouve sur le trait. Les dérapages appropriés sont effectués avec la fonction directement sous le pied arrière ou plus loin vers la queue. ', 'Slides', 'https://www.youtube.com/watch?v=U-TtF3ZfsQ0' ],
            ['McTwist', 'Une face arrière 540 basculante vers l’avant réalisée dans un halfpipe, un quarterpipe ou un obstacle similaire. La rotation peut continuer au-delà de 540 ° (par exemple, McTwist 720). L\'origine de cette astuce vient du skateboarding vert ramp, et a été réalisée pour la première fois sur une planche à roulettes par Mike McGill .', 'Flips and inverted rotations', 'https://www.youtube.com/watch?v=WFG1QJQXoeM' ],
            ['Double McTwist', 'Shaun White est considéré comme le créateur du Double McTwist 1260, mais Ben Stewart joue le rôle dans certaines des images d\'archives les plus anciennes. Shaun White a été le premier athlète à effectuer le tour en compétition aux Jeux olympiques d\'hiver de 2010, ce qui lui a valu une reconnaissance mondiale et le nom de "Tomahawk".Depuis lors, de nombreux athlètes ont effectué le Double McTwist 1260, dont Iouri Podladtchikov .', 'Flips and inverted rotations', 'https://www.youtube.com/watch?v=YQIvm_2ay-U' ],
            ['Misty Frontside', 'Le Frontside misty finit par ressembler un peu à un rodéo au centre du tour, mais au décollage, le coureur utilise un type de mouvement plus frontflip pour commencer le tour. Le frontside Misty ne peut être fait que sur les orteils et le coureur va se mettre à tourner, puis son épaule se faufile vers son pied avant et l’épaule de tête se détache vers le ciel. comme ils se détendent au décollage. Habituellement, lorsqu’il attrape Indy, le coureur suit l’épaule de tête dans la rotation jusqu’à 540, 720 et même 900.', 'Flips and inverted rotations', 'https://www.youtube.com/watch?v=HEzgU_A16Gs' ]
        ];
    }
}
