<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", length=500, nullable=true)
     */
    private $description;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Track")
     */
    private $tracks;


    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOwner(): ?UserEntity
    {
        return $this->owner;
    }

    public function setOwner(?UserEntity $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTitle(): String
    {
        return $this->title;
    }

    public function setTitle(String $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): String
    {
        return $this->description;
    }

    public function setDescription(String $desc): self
    {
        $this->description = $desc;
        return $this;
    }


    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
        }

        return $this;
    }
}
