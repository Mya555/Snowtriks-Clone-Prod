<?php

namespace App\EventListener;


use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Image;

class ImageUploadListener
{
    private $imageDir;
    public function __construct($imageDir)
    {
        $this->imageDir = $imageDir;
    }
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $image = $eventArgs->getObject();
        if(!($image instanceof Image)) {
            return;
        }
        $this->save($image);
    }
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $image = $eventArgs->getObject();
        if(!($image instanceof Image)) {
            return;
        }
        $this->remove($this->imageDir . $image->getName());
    }
    private function save(Image $image)
    {
        if(null !== $image->getFile()) {
            $image->getFile()->move($this->imageDir, $image->getName());

        }
    }
    private function remove($absolutePath)
    {
        if(file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }
}