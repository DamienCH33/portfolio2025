<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la compétence',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 100,
                    'placeholder' => 'Ex : PHP, Symfony, JavaScript...',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom de la compétence est obligatoire.',
                    ]),
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\-\_]+$/u',
                        'message' => 'Le nom ne doit contenir que des lettres, chiffres et tirets.',
                    ]),
                ],
            ])

            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Langage' => 'langage',
                    'Framework' => 'framework',
                    'Outil' => 'outil',
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La catégorie est obligatoire.']),
                ],
            ])

            ->add('logo', TextType::class, [
                'label' => 'Logo (URL ou icône)',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'placeholder' => 'Ex : /images/logos/php.svg ou https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le logo est obligatoire.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le lien du logo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        // Autorise lettres, chiffres, / . - _ : et https:// ou http://
                        'pattern' => '/^(https?:\/\/[a-zA-Z0-9\-\.\/\_\?\=\&\#]+|\/[a-zA-Z0-9\-\.\/\_]+)$/',
                        'message' => 'Le logo doit être un chemin valide ou une URL commençant par http:// ou https://.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
