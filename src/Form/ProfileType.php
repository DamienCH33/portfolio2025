<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre principal (H1)',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'placeholder' => 'Ex : Damien Chauveau',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Sous-titre / poste (H2)',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'placeholder' => 'Ex : Développeur Back-End PHP / Symfony',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le sous-titre est obligatoire.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('intro', TextareaType::class, [
                'label' => "Paragraphe d'accroche",
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Quelques phrases de présentation...',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'accroche est obligatoire."]),
                ],
            ])
            ->add('githubUrl', UrlType::class, [
                'label' => 'Lien GitHub',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://github.com/...'],
            ])
            ->add('linkedinUrl', UrlType::class, [
                'label' => 'Lien LinkedIn',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://www.linkedin.com/in/...'],
            ])
            ->add('cvFileUpload', FileType::class, [
                'label' => 'Fichier CV (PDF)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'accept' => 'application/pdf'],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Merci de déposer un fichier PDF valide.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
