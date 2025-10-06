<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\ObjectTranslationBundle\Model\Translation as BaseTranslation;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation extends BaseTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
