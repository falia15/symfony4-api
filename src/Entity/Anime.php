<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimeRepository")
 */
class Anime
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"anime_default"})
     */
    private $nameJap;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"anime_default"})
     */
    private $nameUs;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $season;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"anime_default"})
     */
    private $myanimelistId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"anime_default"})
     */
    private $anilistId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"anime_default"})
     */
    private $kitsuId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Opening", mappedBy="anime")
     * @Groups({"anime_default"})
     */
    private $openings;

    public function __construct()
    {
        $this->openings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameJap(): ?string
    {
        return $this->nameJap;
    }

    public function setNameJap(string $nameJap): self
    {
        $this->nameJap = $nameJap;

        return $this;
    }

    public function getNameUs(): ?string
    {
        return $this->nameUs;
    }

    public function setNameUs(?string $nameUs): self
    {
        $this->nameUs = $nameUs;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
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

    public function getMyanimelistId(): ?int
    {
        return $this->myanimelistId;
    }

    public function setMyanimelistId(int $myanimelistId): self
    {
        $this->myanimelistId = $myanimelistId;

        return $this;
    }

    public function getAnilistId(): ?int
    {
        return $this->anilistId;
    }

    public function setAnilistId(?int $anilistId): self
    {
        $this->anilistId = $anilistId;

        return $this;
    }

    public function getKitsuId(): ?int
    {
        return $this->kitsuId;
    }

    public function setKitsuId(?int $kitsuId): self
    {
        $this->kitsuId = $kitsuId;

        return $this;
    }

    /**
     * @return Collection|Opening[]
     */
    public function getOpenings(): Collection
    {
        return $this->openings;
    }

    public function addOpening(Opening $opening): self
    {
        if (!$this->openings->contains($opening)) {
            $this->openings[] = $opening;
            $opening->setAnime($this);
        }

        return $this;
    }

    public function removeOpening(Opening $opening): self
    {
        if ($this->openings->contains($opening)) {
            $this->openings->removeElement($opening);
            // set the owning side to null (unless already changed)
            if ($opening->getAnime() === $this) {
                $opening->setAnime(null);
            }
        }

        return $this;
    }
}
