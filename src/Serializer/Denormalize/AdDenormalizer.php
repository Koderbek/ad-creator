<?php

declare(strict_types=1);

namespace App\Serializer\Denormalize;

use App\Entity\Ad;
use App\Entity\Photo;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class AdDenormalizer
 * @package App\Serializer\Denormalize
 */
class AdDenormalizer implements DenormalizerInterface
{
    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     *
     * @return Ad
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): Ad
    {
        $entity = new Ad();

        $entity->setName($data['name']);
        $entity->setPrice($data['price']);
        $entity->setDescription($data['description'] ?? null);

        foreach ($data['photos'] as $value) {
            $photo = new Photo();
            $photo->setLink($value);
            $photo->setAd($entity);

            $entity->addPhoto($photo);
        }

        return $entity;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, string $type, $format = null)
    {
        return new $type() instanceof Ad;
    }
}
