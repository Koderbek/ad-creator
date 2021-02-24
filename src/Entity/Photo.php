<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Photo
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Photo
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups("show")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * @Groups("show")
     */
    private $link;

    /**
     * @var Ad
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="photos")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ad;

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
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return Ad
     */
    public function getAd(): Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad $ad
     */
    public function setAd(Ad $ad): void
    {
        $this->ad = $ad;
    }
}