<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 19/12/2017
 * Time: 14:02
 */

namespace App\Form;

use App\Entity\FormGeneral;
use App\Entity\Tricks;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('date',       DateTimeType::class)
            ->add('name',        TextType::class)
            ->add('user',       TextType::class)
            ->add('description', TextareaType::class, array('attr' => array('rows' => 6)))
            ->add('images',      CollectionType::class, [
             'entry_type' => FileType::class, 'data_class' => null, 'allow_add' => true, 'by_reference' => false,
                ])
            ->add('videos',      CollectionType::class, [])
            ->add('groupe',      TextType::class)
            ->add('save',       SubmitType::class);



    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Tricks::class,
        ));
    }


}
