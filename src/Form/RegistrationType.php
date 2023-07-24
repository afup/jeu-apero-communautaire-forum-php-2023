<?php

namespace App\Form;

use App\Validator\CodeExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public const INVALID_CODE_MESSAGE = 'Code invalide ou inexistant';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usercode', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 7, max: 7, exactMessage: self::INVALID_CODE_MESSAGE),
                    new CodeExists(),
                ],
                'label' => 'Votre code',
                'attr' => [
                    'class' => 'form-control-lg',
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Commencer !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
