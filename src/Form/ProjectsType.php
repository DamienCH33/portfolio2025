<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du projet',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 150,
                    'placeholder' => 'Ex: Portfolio Symfony',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le titre est obligatoire.',
                    ]),
                    new Assert\Length([
                        'max' => 150,
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Décrivez votre projet...',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description est obligatoire.',
                    ]),
                ],
            ])

            ->add('techStack', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Technologies utilisées',
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Veuillez sélectionner au moins une technologie.',
                    ]),
                ],
            ])

            ->add('imageFile', FileType::class, [
                'label' => 'Image du projet',
                'mapped' => false,
                'required' => !$options['is_edit'],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png,image/webp'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                    ]),
                ],
            ])

            ->add('logoFile', FileType::class, [
                'label' => 'Logo du projet (optionnel)',
                'help' => 'PNG, JPG ou WebP carré, fond transparent de préférence. Affiché dans « En ligne en ce moment » sur l\'accueil.',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png,image/webp',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Logo au format PNG, JPG ou WebP.',
                    ]),
                ],
            ])

            ->add('link', UrlType::class, [
                'label' => 'Lien vers le projet',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://monprojet.com',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le lien du projet est obligatoire.',
                    ]),
                    new Assert\Url([
                        'message' => 'Veuillez entrer une URL valide.',
                    ]),
                ],
            ])

            ->add('demoUrl', UrlType::class, [
                'label' => 'Lien de la démo (optionnel)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://ma-demo.com',
                ],
                'constraints' => [
                    new Assert\Url([
                        'message' => 'Veuillez entrer une URL valide.',
                    ]),
                ],
            ])

            // ----- Étude de cas -----
            ->add('context', TextareaType::class, [
                'label' => 'Le besoin',
                'required' => false,
                'help' => 'Pour qui, quel problème. Laisser vide = pas de page détaillée. Une ligne commençant par « - » devient une puce.',
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('approach', TextareaType::class, [
                'label' => 'Mes choix techniques',
                'required' => false,
                'help' => 'Ce que tu as choisi et pourquoi.',
                'attr' => ['class' => 'form-control', 'rows' => 6],
            ])
            ->add('challenge', TextareaType::class, [
                'label' => 'La difficulté',
                'required' => false,
                'help' => 'Un vrai problème rencontré et comment tu l\'as résolu.',
                'attr' => ['class' => 'form-control', 'rows' => 6],
            ])
            ->add('outcome', TextareaType::class, [
                'label' => 'Le résultat',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('galleryFiles', FileType::class, [
                'label' => 'Captures supplémentaires (2 ou 3)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'help' => 'Ajoutées à la suite des captures existantes.',
                'attr' => ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/webp'],
                'constraints' => [
                    new Assert\All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                            'mimeTypesMessage' => 'Captures au format PNG, JPG ou WebP.',
                        ]),
                    ]),
                ],
            ])
            ->add('clearGallery', CheckboxType::class, [
                'label' => 'Supprimer les captures supplémentaires actuelles',
                'mapped' => false,
                'required' => false,
            ])

            ->add('createdAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La date est obligatoire.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'is_edit' => false,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
