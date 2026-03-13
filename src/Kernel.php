<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public const DEFAULT_TIMEZONE = 'Europe/Paris';

    public function __construct(string $environment, bool $debug)
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        parent::__construct($environment, $debug);
    }
}
