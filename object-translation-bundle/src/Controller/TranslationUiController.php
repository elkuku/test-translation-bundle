<?php

namespace SymfonyCasts\ObjectTranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TranslationUiController extends AbstractController
{
    #[Route('/translationxx', name: 'trannn_ui')]
    public function test()
    {
        return $this->render('@ObjectTranslationBundle/demo.html.twig', [
            'title' => 'Hello from MyDemoBundle!',
        ]);
        return new Response('hello world');
    }
}
