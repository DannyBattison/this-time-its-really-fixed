<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SearchTermRepository")
 */
class SearchTerm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $query;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastSynced;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getLastSynced(): ?\DateTimeInterface
    {
        return $this->lastSynced;
    }

    public function setLastSynced(?\DateTimeInterface $lastSynced): self
    {
        $this->lastSynced = $lastSynced;

        return $this;
    }
}
