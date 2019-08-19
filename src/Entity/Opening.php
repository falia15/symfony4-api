<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpeningRepository")
 */
class Opening
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"anime_default"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"anime_default"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"anime_default"})
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"anime_default"})
     */
    private $moeLink;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Anime", inversedBy="openings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $anime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getMoeLink(): ?string
    {
        return $this->moeLink;
    }

    public function setMoeLink(string $moeLink): self
    {
        $this->moeLink = $moeLink;

        return $this;
    }

    public function getAnime(): ?Anime
    {
        return $this->anime;
    }

    public function setAnime(?Anime $anime): self
    {
        $this->anime = $anime;

        return $this;
    }
}
