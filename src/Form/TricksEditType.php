<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 19/12/2017
 * Time: 15:34
 */

namespace App\Form;

use App\Entity\Tricks;
use App\Form\TricksType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TricksEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('name');
    }

    public function getParent()
    {
        return TricksType::class;
    }

}