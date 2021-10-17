<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled'=> true,
                'label' => "mon adresse email"
            ])
            ->add('firstname',TextType::class,[
                'disabled' => true,
                'label' => "Mon prenom"
            ])
            ->add('lastname',TextType::class,[
                'disabled' => true,
                'label' => "Mon nom"
            ])
            ->add('old_password',PasswordType::class, [
                'label' => "mon mot de passe actuelle",
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Veuillez saisir votre mot de passe actuel"
                ]
            ])
            ->add('new_password',RepeatedType::class,[
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les deux mot de passe doivent etre identique',
                'required' => true,
                'label' => "Mon nouveau mot de passe",
                'first_options' => ['label' => "mot de passe",
                    'attr' => [
                        "placeholder" => "Mon nouveau mot de passe",
                    ]

                ],
                'second_options' => ['label'  => "Merci de confirmer votre nouveau mot de passe",
                    'attr'=>[
                        "placeholder" => "confirmez votre nouveau mot de passe"
                    ]
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Modifier mon mot de passe '
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
