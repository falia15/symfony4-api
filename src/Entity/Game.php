<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameUser", mappedBy="game")
     */
    private $gameUsers;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
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
}
