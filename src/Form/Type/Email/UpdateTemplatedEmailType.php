<?php

namespace App\Form\Type\Email;

use App\Entity\TemplatedEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateTemplatedEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, ['label' => false])
            ->add('recipient',EmailType::class, ['label' => false])
            ->add('subject',null, ['label' => false])
            ->add('body', TextareaType::class, [
                'required' => false,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplatedEmail::class
        ]);
    }
}