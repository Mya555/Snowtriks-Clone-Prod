<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Tricks;
use App\Service\FileUploader;

class BrochureUploadListener
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
// upload only works for Product entities
if (!$entity instanceof Product) {
return;
}

$file = $entity->getImage();

// only upload new files
if ($file instanceof UploadedFile) {
$fileName = $this->uploader->upload($file);
$entity->setImage($fileName);
}
}
}