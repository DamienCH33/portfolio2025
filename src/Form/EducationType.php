<?php

namespace App\Form;

use App\Entity\Education;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EducationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('yearStart', IntegerType::class, [
                'label' => 'Année de début',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 2018',
                    'min' => 1900,
                    'max' => date('Y'),
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'année de début est obligatoire.']),
                    new Assert\Range([
                        'min' => 1900,
                        'max' => date('Y'),
                        'notInRangeMessage' => 'L\'année doit être entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('yearEnd', IntegerType::class, [
                'label' => 'Année de fin',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 2022 ou laisser vide si en cours',
                    'min' => 1900,
                    'max' => date('Y') + 10,
                ],
                'constraints' => [
                    new Assert\Range([
                        'min' => 1900,
                        'max' => date('Y') + 10,
                        'notInRangeMessage' => 'L\'année doit être entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('institution', TextType::class, [
                'label' => 'Établissement',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 150,
                    'placeholder' => 'Nom de l\'établissement',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom de l\'établissement est obligatoire.']),
                    new Assert\Length([
                        'max' => 150,
                        'maxMessage' => 'Le nom de l\'établissement ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Diplôme / Titre',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 150,
                    'placeholder' => 'Ex : Licence Informatique, Bac S...',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le diplôme ou titre est obligatoire.']),
                    new Assert\Length([
                        'max' => 150,
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Education::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
