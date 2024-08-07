<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NameType extends AbstractType
{
    public const INVALID_NAME = 'Nom invalide : il doit être compris entre 3 et 255 caractères';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 3, max: 255, exactMessage: self::INVALID_NAME),
                ],
                'label' => 'Comment vous appelez-vous ?',
                'help' => 'Votre nom sera visible par les autres joueuses et joueurs et doit respecter le <a href="https://coc.afup.org">Code de Conduite</a> de l\'AFUP.',
                'help_html' => true,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer et continuer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}