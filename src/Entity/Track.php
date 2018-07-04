<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackRepository")
 */
class Track
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $trackurl;

    /**
     * @ORM\Column(type="integer")
     */
    private $played_times;

    /**
     * @ORM\Column(type="integer")
     */
    private $dowloaded_times;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @ORM\Column(type="boolean")
     */
    private $explicit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $downloadable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    public function getId()
    {
        return $this->id;
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

    public function getTrackUrl(): ?string
    {

        return $this->trackurl;
    }

    public function setTrack($trackurl): self
    {
        $this->trackurl = $trackurl;
        return $this;
    }

    public function getPlayedTimes(): ?int
    {
        return $this->played_times;
    }

    public function setPlayedTimes(int $played_times): self
    {
        $this->played_times = $played_times;

        return $this;   
    }

    public function getDowloadedTimes(): ?int
    {
        return $this->dowloaded_times;
    }

    public function setDowloadedTimes(int $dowloaded_times): self
    {
        $this->dowloaded_times = $dowloaded_times;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getExplicit(): ?bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): self
    {
        $this->explicit = $explicit;

        return $this;
    }

    public function getDownloadable(): ?bool
    {
        return $this->downloadable;
    }

    public function setDownloadable(bool $downloadable): self
    {
        $this->downloadable = $downloadable;

        return $this;
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

    public function setCreatedAt(\DateTime $date)
    {
        $this->created_at = $date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $date)
    {
        $this->updated_at = $date;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $isValidated): self
    {
        $this->validated = $isValidated;
        return $this;
    }

}
