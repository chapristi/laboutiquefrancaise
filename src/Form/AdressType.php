<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Quel nom souhaitez vous donner a votre adresse'
            ])
            ->add('firstname',TextType::class,[
                'label' => ' votre prenom'
            ])
            ->add('lastname',TextType::class,[
                'label' => 'votre nom'
            ])
            ->add('company',TextType::class,[
                'label' => ' votre société',
                'required' => false,
            ])
            ->add('adress',TextType::class,[
                'label' => 'votre adresse'
            ])
            ->add('postal',TextType::class,[
                'label' => 'votre code postal'
            ])
            ->add('city',TextType::class,[
                'label' => 'votre ville'
            ])
            ->add('country',CountryType::class,[
                'label' => 'Votre pays'
            ])
            ->add('phone',TelType::class,[
                'label' => 'Votre numéro de telephone'
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'ajouter mon adresse'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
