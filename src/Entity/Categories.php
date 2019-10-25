<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Jobs[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Jobs", mappedBy="categories", cascade={"remove"})
     */
    private $jobs;

    /**
     * @var Affiliates[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Affiliates", mappedBy="categories")
     */
    private $affiliates;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Jobs[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    /**
     * @return Jobs[]|ArrayCollection
     */
    public function getActiveJobs()
    {
        return $this->jobs->filter(function(Jobs $job) {
            return $job->getExpiresAt() > new \DateTime() && $job->isActivated();
        });
    }

    public function addJob(Jobs $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setCategories($this);
        }

        return $this;
    }

    public function removeJob(Jobs $job): self
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getCategories() === $this) {
                $job->setCategories(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affiliates[]
     */
    public function getAffiliates(): Collection
    {
        return $this->affiliates;
    }

    public function addAffiliate(Affiliates $affiliate): self
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates[] = $affiliate;
        }

        return $this;
    }

    public function removeAffiliate(Affiliates $affiliate): self
    {
        if ($this->affiliates->contains($affiliate)) {
            $this->affiliates->removeElement($affiliate);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug() : ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
