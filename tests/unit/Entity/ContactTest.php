<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testContactCreation(): void
    {
        $contact = new Contact();

        $contact->setFirstname('Damien');
        $contact->setLastname('Chauveau');
        $contact->setEmail('test@test.com');
        $contact->setMessage('Bonjour');

        $this->assertEquals('Damien', $contact->getFirstname());
        $this->assertEquals('Chauveau', $contact->getLastname());
        $this->assertEquals('test@test.com', $contact->getEmail());
        $this->assertEquals('Bonjour', $contact->getMessage());
    }
}
