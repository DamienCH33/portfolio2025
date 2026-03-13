<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],

    ...(class_exists(\Symfony\Bundle\DebugBundle\DebugBundle::class) ? [\Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true]] : []),
    ...(class_exists(\Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class) ? [\Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true]] : []),
    ...(class_exists(\Symfony\Bundle\MakerBundle\MakerBundle::class) ? [\Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true]] : []),
    ...(class_exists(\Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class) ? [\Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true]] : []),
];
