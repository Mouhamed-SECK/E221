<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class PropertyType extends AbstractType
{

    private function getConfiguration($label, $placeholder, $cssClass = null)
    {
        return  [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
                'class' =>  $cssClass,
            ]

        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('Titre', 'Entrez le titre'),
            )
            ->add(
                'description',
                TextType::class,
                $this->getConfiguration('Description', 'Entrez la description'),
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration('Prix', 'Indiquez le priz que vous voulez'),
            )
            ->add(
                'badrooms',
                IntegerType::class,
                $this->getConfiguration('Chambres', 'Entrez le nombre de chambre'),
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfiguration('Piéces', 'Entrez le nombre de Piéce'),
            )
            ->add(
                'surface',
                IntegerType::class,
                $this->getConfiguration('Surface', 'Entrez la surface')
            )
            ->add(
                'city',
                TextType::class,
                $this->getConfiguration('Vile', 'Entrez la Ville')
            )
            ->add(
                'address',
                TextType::class,
                $this->getConfiguration('Address', 'Entrez le code Postal'),
            )
            ->add(
                'postalCode',
                TextType::class,
                $this->getConfiguration('Code Postal', 'Entrez le code Postal'),
            )

            ->add('duration')
            ->add(
                'CoverImage',
                UrlType::class,
                $this->getConfiguration('Imagel', 'Entrez l\'url de l\'image')
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}