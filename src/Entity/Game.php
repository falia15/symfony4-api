<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCreator;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     * )
    */
    private $status;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     * )
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 4,
     *      max = 15,
     * )
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameUser", mappedBy="game")
     */
    private $gameUsers;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    private $scoreToWin;

    public function __construct()
    {
        $this->gameUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCreator(): ?User
    {
        return $this->userCreator;
    }

    public function setUserCreator(?User $userCreator): self
    {
        $this->userCreator = $userCreator;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

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

    public function getAnswer(): ?int
    {
        return $this->answer;
    }

    public function setAnswer(int $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return Collection|GameUser[]
     */
    public function getUser(): Collection
    {
        return $this->gameUsers;
    }

    public function addUser(GameUser $gameUsers): self
    {
        if (!$this->gameUsers->contains($gameUsers)) {
            $this->gameUsers[] = $gameUsers;
            $gameUsers->setGame($this);
        }

        return $this;
    }

    public function removeUser(GameUser $gameUsers): self
    {
        if ($this->gameUsers->contains($gameUsers)) {
            $this->gameUsers->removeElement($gameUsers);
            // set the owning side to null (unless already changed)
            if ($gameUsers->getGame() === $this) {
                $gameUsers->setGame(null);
            }
        }

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getScoreToWin(): ?int
    {
        return $this->scoreToWin;
    }

    public function setScoreToWin(int $scoreToWin): self
    {
        $this->scoreToWin = $scoreToWin;

        return $this;
    }
}
