<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Site internet'],
            ])
            ->add('summary', TextType::class, [
                'label' => 'Présentation en une phrase',
                'attr' => ['placeholder' => 'Un site rapide, à votre image, que vous mettez à jour vous-même.'],
            ])
            ->add('items', TextareaType::class, [
                'label' => 'Ce qui est inclus (une ligne par élément)',
                'required' => false,
                'attr' => ['rows' => 5],
            ])
            ->add('priceLabel', TextType::class, [
                'label' => 'Prix affiché',
                'attr' => ['placeholder' => '29 € par mois'],
            ])
            ->add('note', TextType::class, [
                'label' => 'Note sous le prix (facultatif)',
                'required' => false,
            ])
            ->add('icon', TextType::class, [
                'label' => 'Icône Bootstrap Icons',
                'help' => 'Nom de l\'icône sur icons.getbootstrap.com, précédé de « bi- » (ex. bi-window, bi-star-half).',
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Ordre d\'affichage',
                'help' => 'Les plus petits nombres apparaissent en premier.',
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Affichée sur le site',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Offer::class]);
    }
}
