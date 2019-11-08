<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\JobsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Jobs
{
    public const FULL_TIME_TYPE = 'full-time';
    public const PART_TIME_TYPE = 'part-time';
    public const FREELANCE_TYPE = 'freelance';

    public const TYPES = [
        self::FULL_TIME_TYPE,
        self::PART_TIME_TYPE,
        self::FREELANCE_TYPE,
    ];

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     * @JMS\Type("int")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Type cannot be blank.")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Company cannot be blank.")
     * @Assert\Length(max="255")
     */
    private $company;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\Length(max="255")
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Position cannot be blank")
     * @Assert\Length(max="255")
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Location cannot be blank")
     * @Assert\Length(max="255")
     */
    private $location;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Description cannot be blank")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @JMS\Expose()
     * @JMS\Type("string")
     * @JMS\SerializedName("howToApply")
     *
     * @Assert\NotBlank(message="This field cannot be blank")
     */
    private $howToApply;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Token cannot be blank")
     * @Assert\Length(max="255")
     */
    private $token;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     * @JMS\Type("bool")
     *
     * @Assert\NotNull()
     */
    private $public;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     * @JMS\Type("bool")
     *
     * @Assert\NotNull()
     */
    private $activated;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @Assert\NotBlank(message="Email cannot be blank")
     * @Assert\Email()
     * @Assert\Length(max="255")
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     * @JMS\Type("DateTime")
     */
    private $expiresAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var Categories
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories", inversedBy="jobs", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @JMS\Expose()
     * @JMS\Type("integer")
     *
     * @Assert\NotBlank(message="Categories cannot be blank")
     */
    private $categories;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Categories")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $category;

    /**
     * @var string
     * @Assert\NotBlank()
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $logoname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @JMS\Accessor(setter="setLogodata");
     */
    private $logodata;

//    /**
//     * @var string
//     */
//    public $decodedLogodata;

    public function setLogodata(?string $logodata)
    {
        $this->logodata = $logodata;
//        $this->decodedLogodata = base64_decode($logodata);
//        $this->decodedLogodata = new Base64EncodedFile($logodata);
    }

    public function getLogodata(): ?string
    {
        return $this->logodata;
    }

//    public function getDecodedlogodata(): ?string
//    {
//        return $this->decodedLogodata;
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null|UploadedFile
     */
//    public function getLogo(): ?string
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string|null|UploadedFile $logo
     *
     * @return self
     */
//    public function setLogo(?string $logo): self
    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHowToApply(): ?string
    {
        return $this->howToApply;
    }

    public function setHowToApply(string $howToApply): self
    {
        $this->howToApply = $howToApply;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic() : ?bool
    {
        return $this->public;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated() : ?bool
    {
        return $this->activated;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("logo_path")
     *
     * @return string|null
     */
    public function getLogoPath()
    {
        return $this->getLogo() ? 'uploads/jobs/' . $this->getLogo() : null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("category_name")
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->getCategories()->getName();
    }

    public function getCategoryId()
    {
        return $this->categories;

    }

//    public function getCategory(): ?Categories
//    {
//        return $this->category;
//    }
//
//    public function setCategory(?Categories $category): self
//    {
//        $this->category = $category;
//
//        return $this;
//    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        if (!$this->expiresAt) {
            $this->expiresAt = (clone $this->createdAt)->modify('+30 days');
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}
