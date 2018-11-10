<?php

namespace App\Form;

use App\Entity\MediaVideo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class MediaVideoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url',TextType::class,array('label'=>'URL de la video',
                'required' => false,
                'constraints' => [
                    new Regex('#^(http|https):\/\/(www.youtube.com|www.dailymotion.com|vimeo.com)\/#'),

                 ]
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediaVideo::class,
        ]);
    }
}
