<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Ad
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Ad
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"create", "show"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     *
     * @Groups("show")
     */
    private $name;

    /**
     * @var float
     * @ORM\Column(type="float")
     *
     * @Groups("show")
     */
    private $price;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, length=1000)
     *
     * @Groups("show")
     */
    private $description;

    /**
     * @var ArrayCollection|Photo[]
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="ad", cascade={"all"})
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     *
     * @Groups("show")
     */
    public function getPhotos(): array
    {
        return array_map(static fn(Photo $photo) => $photo->getLink(), $this->photos->toArray());
    }

    /**
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo): void
    {
        if (!$this->photos->contains($photo)){
            $this->photos->add($photo);
        }
    }
}