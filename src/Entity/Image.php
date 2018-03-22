<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tricks", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;


    /**
     * @Assert\Image(
     *      mimeTypesMessage = "image.file.invalid",
     *      minWidth = 630,
     *      minHeight = 350,
     *      minWidthMessage = "image.file.min_width",
     *      minHeightMessage = "image.file.min_height",
     *      minRatio = 1.5,
     *      maxRatio = 2,
     *      minRatioMessage = "image.file.ratio",
     *      maxRatioMessage = "image.file.ratio"
     * )
     */
    private $file;


    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     */
    public function completeFileValidation(ExecutionContextInterface $context)
    {
        if ($this->id === null) {
            $constraint = new NotNull(array(
                'message' => 'image.file.null'
            ));
            $context
                ->getValidator()
                ->inContext($context)
                ->atPath('file')
                ->validate($this->file, $constraint)
            ;
        }
    }

}
