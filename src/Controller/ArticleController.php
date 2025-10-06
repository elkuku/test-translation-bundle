<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\ObjectTranslationBundle\ObjectTranslator;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article_index')]
    public function index(
        ArticleRepository $articles,
    ): Response {
        return $this->render('article/index.html.twig', [
            'articles' => $articles->findBy([], orderBy: ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/news/{slug:article}', name: 'app_article_show')]
    public function show(
        Article $article,
        ObjectTranslator $translator,
    ): Response {
        $article = $translator->translate($article);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => [
                'I ate a normal rock once. It did NOT taste like bacon!',
                'Woohoo! I\'m going on an all-asteroid diet!',
                'I like bacon too! Buy some from my site! bakinsomebacon.com',
            ],
        ]);
    }
}
