<?php

namespace App\Form\Type\Member;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choices' => $options['projects'],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Lecteur' => MemberRole::VIEWER,
                    'Editeur' => MemberRole::EDITOR,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'projects' => [],
        ]);
    }
}