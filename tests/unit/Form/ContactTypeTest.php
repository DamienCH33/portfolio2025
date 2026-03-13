<?php

namespace App\Tests\Form;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ContactTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'firstname' => 'Damien',
            'lastname' => 'Chauveau',
            'email' => 'damien@test.com',
            'message' => 'Bonjour ceci est un message test.',
        ];

        $model = new Contact();

        $form = $this->factory->create(ContactType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals('Damien', $model->getFirstname());
        $this->assertEquals('Chauveau', $model->getLastname());
        $this->assertEquals('damien@test.com', $model->getEmail());
    }
}
