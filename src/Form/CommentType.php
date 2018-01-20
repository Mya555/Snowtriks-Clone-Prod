<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 19/01/2018
 * Time: 10:16
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user')
            ->add('comment');
    }

}