<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 19/12/2017
 * Time: 15:34
 */

namespace App\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('email')->remove('plainPassword')->remove('username');
    }

    public function getParent()
    {
        return UserType::class;
    }

}