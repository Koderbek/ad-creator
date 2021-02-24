<?php

declare(strict_types=1);

namespace App\Serializer\Denormalize;

use App\Entity\Ad;
use App\Entity\Photo;
use Symfony\Component\HttpFoundation\Response;
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

        if (!isset($data['name'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Name cannot be blank');
        }
        $entity->setName($data['name']);

        if (!isset($data['price'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Price cannot be blank');
        }
        $entity->setPrice($data['price']);

        if (!isset($data['photos'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Photos cannot be blank');
        }

        foreach ($data['photos'] as $value) {
            $photo = new Photo();
            $photo->setLink($value);
            $photo->setAd($entity);

            $entity->addPhoto($photo);
        }

        $entity->setDescription($data['description'] ?? null);

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
