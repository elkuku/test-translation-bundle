<?php

namespace App\Factory;

use App\Entity\Article;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Article>
 */
final class ArticleFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Article::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'author' => self::faker()->text(255),
            'content' => self::faker()->text(),
            'publishedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'slug' => self::faker()->text(255),
            'title' => self::faker()->text(255),
            'category' => CategoryFactory::new(),
        ];
    }
}
