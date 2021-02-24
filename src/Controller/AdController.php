<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AdController
 * @package App\Controller
 *
 * @Route("/ad")
 */
class AdController extends AbstractController
{
    /**
     * @param string $content
     *
     * @return Response
     */
    private function createResponse(string $content): Response
    {
        $response = new Response($content, Response::HTTP_OK, []);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function new(SerializerInterface $serializer, Request $request, EntityManagerInterface $em): Response
    {
        $entity = $serializer->deserialize($request->getContent(), Ad::class,'json');
        $em->persist($entity);
        $em->flush();

        $json = $serializer->serialize($entity, "json", ['groups' => ["create"]]);

        return $this->createResponse($json);
    }

    /**
     * @Route("/", methods={"GET"})
     *
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $entities = $em->getRepository(Ad::class)->findAll();
        $json = $serializer->serialize($entities, "json", ['groups' => ["show"]]);

        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function show(int $id, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $entity = $em->getRepository(Ad::class)->find($id);
        $json = $serializer->serialize($entity, "json", ['groups' => ["show"]]);

        return $this->createResponse($json);
    }
}