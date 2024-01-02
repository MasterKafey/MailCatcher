<?php

namespace App\Form\Type\Parameter;

use App\Form\Model\Parameter\UpdateParameterModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateParameterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateParameterModel::class,
        ]);
    }
}