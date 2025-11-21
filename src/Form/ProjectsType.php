<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
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
                    new Assert\NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Assert\Length([
                        'max' => 150,
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.'
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
                    new Assert\NotBlank(['message' => 'La description est obligatoire.']),
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
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner au moins une compétence.']),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du projet (jpg, png, jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (jpeg, jpg, png).',
                    ])
                ],
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien vers le projet',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: https://monprojet.com',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le lien du projet est obligatoire.']),
                    new Assert\Url(['message' => 'Veuillez entrer une URL valide.']),
                ],
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
