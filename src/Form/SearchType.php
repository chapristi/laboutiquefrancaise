<?php
namespace App\Form;
use App\Classes\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string',TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "votre recherche ...",
                    'class' => 'form-control-sm',
                ]
            ])
            //entityType va nous permet de reflaiter notre entiter pour afficher automatiquement les
            //categories
            ->add('categories',EntityType::class,[
                'label' => false,
                "required" => false,
                'class' => Category::class,
                //multiple sert a dire que l'on peut avoir plusieurs choix
                'multiple' => true,
                'expanded'=>true,
            ])
            ->add('submit' , SubmitType::class,[
                'label' => "filtrer",
                'attr' => [
                    'class' => 'btn-block btn-primary'

                ]
            ])
        ;

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => "GET",
            'crsf_protection' => false,

        ]);
    }

    //la fonction sert a prefixer l'url du nom de notre class mais nous ne voulons pas ca
    public function getBlockPrefix()
    {
        return '';
    }
}