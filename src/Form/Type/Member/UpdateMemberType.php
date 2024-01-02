<?php

namespace App\Form\Type\Member;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateMemberType extends AbstractType
{
    public const ROLES_HIERARCHY = [
        'OWNER' => [MemberRole::EDITOR, MemberRole::VIEWER],
        'EDITOR' => [MemberRole::EDITOR, MemberRole::VIEWER],
        'VIEWER' => [MemberRole::VIEWER]
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('role', ChoiceType::class, [
            'choices' => [
                'Editeur' => MemberRole::EDITOR,
                'Lecteur' => MemberRole::VIEWER,
            ],
            'choice_attr' => function(MemberRole $status) use ($options) {
                $disabled = !in_array($status, self::ROLES_HIERARCHY[$options['current_role']->value]);
                return $disabled ? ['disabled' => 'disabled'] : [];
            }
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'current_role' => null,
        ]);
    }
}