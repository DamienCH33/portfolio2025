<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($this::$defaultName);
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crée un utilisateur admin.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l’admin')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l’admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $admin = new User();
        $admin->setEmail($email);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));
        $admin->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($admin);
        $this->em->flush();

        $output->writeln("Admin $email créé avec succès !");

        return Command::SUCCESS;
    }
}
