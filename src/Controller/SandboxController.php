<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SandboxController extends AbstractController
{
    public function printRandomNumber(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            $number
        );
    }
}