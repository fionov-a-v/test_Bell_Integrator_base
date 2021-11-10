<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AuthorController
 * @package App\Controller
 *
 * @Route(path="/author/")
 */
class AuthorController extends AbstractController
{
    private AuthorRepository $authorRepository;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        AuthorRepository    $authorRepository,
        ValidatorInterface  $validator,
        SerializerInterface $serializer
    )
    {
        $this->authorRepository = $authorRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("create", name="author_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {

        if ($request->headers->get('content-type') !== 'application/json') {
            return $this->json(['message' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $requestContent = $request->getContent();

            $author = $this->serializer->deserialize(
                $requestContent,
                Author::class,
                'json',
                [AbstractNormalizer::GROUPS => ['author:create']]
            );

            $errors = $this->validator->validate($author);
            if (count($errors)) {
                throw new BadRequestException((string)$errors);
            }

            $this->authorRepository->save($author);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $serializedLead = $this->serializer->serialize(
            $author,
            'json',
            [AbstractNormalizer::GROUPS => ['author:read']]
        );

        return new JsonResponse($serializedLead, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("{id}", name="author_get", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function get($id): JsonResponse
    {
        $author = $this->authorRepository->findOneBy(['id' => $id]);

        $serializedLead = $this->serializer->serialize(
            $author,
            'json',
            [AbstractNormalizer::GROUPS => ['author:read']]
        );

        return new JsonResponse($serializedLead, Response::HTTP_OK, [], true);
    }

}
