<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScrapingRequestRepository")
 */
class ScrapingRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScrapingResult", mappedBy="request", orphanRemoval=true)
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|ScrapingResult[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(ScrapingResult $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setRequest($this);
        }

        return $this;
    }

    public function removeResult(ScrapingResult $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getRequest() === $this) {
                $result->setRequest(null);
            }
        }

        return $this;
    }
}
