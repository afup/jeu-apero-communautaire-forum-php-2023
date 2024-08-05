<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public const INVALID_CODE_MESSAGE = 'Code invalide';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 5, max: 5, exactMessage: self::INVALID_CODE_MESSAGE),
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Je m\'inscris !'])
        ;
    }
}
