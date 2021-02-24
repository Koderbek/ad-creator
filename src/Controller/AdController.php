<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Ad;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AdController
 * @package App\Controller
 *
 * @Route("/ad")
 */
class AdController extends AbstractController
{
    /**
     * @var ArrayCollection
     */
    private ArrayCollection $errors;

    public function __construct()
    {
        $this->errors = new ArrayCollection();
    }

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
     * @param ConstraintViolationListInterface $errorList
     * @return false|string
     */
    private function serializeErrorData(ConstraintViolationListInterface $errorList)
    {
        for ($i = 0; $i < $errorList->count(); $i++) {
            if ($errorList->has($i)) {
                $this->errors->add([
                    'code' => $errorList->get($i)->getCode(),
                    'message' => $errorList->get($i)->getMessage()
                ]);
            }
        }

        return json_encode(['errors' => $this->errors->toArray()]);
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function new(
        SerializerInterface $serializer,
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): Response {
        try {
            $entity = $serializer->deserialize($request->getContent(), Ad::class,'json');

            $errors = $validator->validate($entity);
            if ($errors->count() > 0) {
                return $this->createResponse($this->serializeErrorData($errors));
            }

            $em->persist($entity);
            $em->flush();

            $json = $serializer->serialize($entity, "json", ['groups' => ["create"]]);

            return $this->createResponse($json);
        } catch (\Throwable $exception) {
            $this->errors->add([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);

            return $this->createResponse(json_encode(['errors' => [$this->errors->toArray()]]));
        }
    }

    /**
     * @Route("/list", methods={"GET"})
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $entities = $em->getRepository(Ad::class)->findByFilter($request->query->all());
        $json = $serializer->serialize($entities, "json", ['groups' => ["list"]]);

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