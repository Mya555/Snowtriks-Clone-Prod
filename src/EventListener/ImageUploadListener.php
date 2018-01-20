<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Tricks;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
        {
        $this->uploader = $uploader;
        }

    public function prePersist(LifecycleEventArgs $args)
        {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
         }

    public function preUpdate(PreUpdateEventArgs $args)
        {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
        }

    private function uploadFile($entity)
        {
// upload only works for Tricks entities
    if (!$entity instanceof Tricks) {
        return;
        }

        $images =[];

        // only upload new files
      foreach($entity->getImages() as $image){
        if ($image instanceof UploadedFile) {
            $fileName = $this->uploader->upload($image);

            $images[] = $fileName;
            }else
        {
            $images[] = $image->getName();
        }
      }

        $entity->setImages($images);

}
    public function postLoad(LifecycleEventArgs $args)
        {
    $entity = $args->getEntity();

        if (!$entity instanceof Tricks) {
            return;
        }

        if ($fileName = $entity->getImages()) {
            $entity->setImages(new File($this->uploader->getTargetDir().'/'.$fileName));
        }


}
}