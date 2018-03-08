<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 01/02/2018
 * Time: 00:35
 */

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author',  TextType::class)
            ->add('comment', TextareaType::class, array('attr' => array('rows' => 6)))
            ->add('save',    SubmitType::class);
        //->add('comment', TextareaType::class, array(
       // 'entry_type' => CommentRepository::class,
        //'entry_options' => array('attr' => array('rows' => 6))
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class,
        ));
    }
}
