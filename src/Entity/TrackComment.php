<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackCommentRepository")
 */
class TrackComment
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Track")
     * @ORM\JoinColumn(nullable=false)
     */
    private $track;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",options={"default" : "2018-01-01 00:00:00"})
     */
    private $created_at;


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

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(Track $track): self
    {
        $this->track = $track;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $date): self
    {
        $this->created_at = $date;

        return $this;
    }
}
