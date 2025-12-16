<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'username',
                    'placeholder' => 'Choississez un nom d\'utilisateur',
                ],
                
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'family-name',
                    'placeholder' => 'Votre nom',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'given-name',
                    'placeholder' => 'Votre prénom',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'tel',
                    'placeholder' => 'Votre téléphone',
                ],
            ])
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'label' => 'Avatar',
                ])

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'email',
                    'placeholder' => 'Votre email',
                ],
            ])
        ;

        if ($options['register']) {
            $builder
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est obligatoire',
                    ]),
                    new PasswordStrength([
                        'minScore' => 2,
                        'message' => 'Le mot de passe doit contenir au moins 12 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
                    ]),
                ],
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
                
                
            ]);
        } else {
            $builder
            ->add('password', PasswordType::class, [
            'label' => 'Mot de passe',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'new-password',
            ],
            'help'=> 'Laissez vide si vous ne voulez pas modifier le mot de passe',
        ])
        ;
    }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'register' => true,
        ]);
    }
}
