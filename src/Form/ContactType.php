<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir un nom.'),
                    new Assert\Length(max: 100),
                    new Regex(
                        pattern: '/^[a-zA-ZÀ-ÿ\-\' ]{2,50}$/u',
                        message: 'Le nom ne peut contenir que des lettres, espaces ou tirets.'
                    ),
                ],
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir un prénom.'),
                    new Assert\Length(max: 100),
                    new Regex(
                        pattern: '/^[a-zA-ZÀ-ÿ\-\' ]{2,50}$/u',
                        message: 'Le prénom ne peut contenir que des lettres, espaces ou tirets.'
                    ),
                ],
            ])

            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir une adresse e-mail.'),
                    new Assert\Email(message: 'Adresse e-mail invalide.'),
                ],
            ])

            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 6],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le message ne peut pas être vide.'),
                    new Assert\Length(min: 10, max: 2000),
                ],
            ])
            ->add('form_time', HiddenType::class, [
                'mapped' => false,
                'data' => time(),
                // Un humain met plus de 3 secondes à remplir le formulaire ; un robot non.
                'constraints' => [
                    new Assert\Callback(static function (mixed $value, ExecutionContextInterface $context): void {
                        if (!is_numeric($value) || time() - (int) $value < 3) {
                            $context->buildViolation('Envoi trop rapide, merci de réessayer.')->addViolation();
                        }
                    }),
                ],
            ])
            // Champ honeypot anti-bot
            ->add('website', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'style' => 'display:none',
                    'tabindex' => '-1',
                    'autocomplete' => 'off',
                ],
                // Champ invisible : s'il est rempli, c'est un robot.
                'constraints' => [new Assert\Blank(message: 'Envoi refusé.')],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
