<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="scrapping_results")
 * @ORM\Entity(repositoryClass="App\Repository\ScrapingResultRepository")
 */
class ScrapingResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScrapingRequest", inversedBy="results", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keyword;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $adWordsCount;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $linksCount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resultStat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    public function __construct()
    {
        $this->adWordsCount = 0;
        $this->linksCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?ScrapingRequest
    {
        return $this->request;
    }

    public function setRequest(?ScrapingRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getAdWordsCount(): ?int
    {
        return $this->adWordsCount;
    }

    public function setAdWordsCount(int $adWordsCount): self
    {
        $this->adWordsCount = $adWordsCount;

        return $this;
    }

    public function getLinksCount(): ?int
    {
        return $this->linksCount;
    }

    public function setLinksCount(int $linksCount): self
    {
        $this->linksCount = $linksCount;

        return $this;
    }

    public function getResultStat(): ?string
    {
        return $this->resultStat;
    }

    public function setResultStat(string $resultStat): self
    {
        $this->resultStat = $resultStat;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }
}
