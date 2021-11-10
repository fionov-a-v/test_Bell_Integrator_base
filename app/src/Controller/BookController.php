<?php

namespace App\Controller;

use App\Dto\BookSearch;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route(path="/book/")
 */
class BookController extends AbstractController
{
    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        AuthorRepository    $authorRepository,
        BookRepository      $bookRepository,
        ValidatorInterface  $validator,
        SerializerInterface $serializer
    )
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("create", name="book_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {

        if ($request->headers->get('content-type') !== 'application/json') {
            return $this->json(['message' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $requestContent = $request->getContent();


            /** @var Book $book */
            $book = $this->serializer->deserialize(
                $requestContent,
                Book::class,
                'json',
                [AbstractNormalizer::GROUPS => ['book:create']]
            );

            $errors = $this->validator->validate($book);

            if (count($errors)) {
                throw new BadRequestException((string)$errors);
            }

//            TODO: либо form либо свой сериализатор, но можно и в лоб
            foreach ($book->getAuthors() as $authorRequest) {
                $author = $this->authorRepository->findOneBy(['id' => $authorRequest->getId()]);
                if (empty($author)) {
                    throw new BadRequestException(sprintf('Author %d not found', $authorRequest->getId()));
                }
                $book->changeAuthor($authorRequest, $author);
            }

            $this->bookRepository->save($book);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $serializedLead = $this->serializer->serialize(
            $book,
            'json',
            [AbstractNormalizer::GROUPS => ['book:read', 'author:read']]
        );

        return new JsonResponse($serializedLead, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("{id}", name="book_get", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function get($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        if (!$book) {
            throw new NotFoundHttpException(sprintf('Book "%s" does not exist', $id));
        }

        $serializedLead = $this->serializer->serialize(
            $book,
            'json',
            [AbstractNormalizer::GROUPS => ['book:read']]
        );

        return new JsonResponse($serializedLead, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("search", name="book_search")
     */
    public function search(Request $request): JsonResponse
    {
        $requestContent = $request->getContent();

        /** @var BookSearch $book */
        $book = $this->serializer->deserialize(
            $requestContent,
            BookSearch::class,
            'json',
            []
        );

        $books = $this->bookRepository->searchByTitle($book->title);

        $serializedLead = $this->serializer->serialize(
            $books,
            'json',
            [AbstractNormalizer::GROUPS => ['book:read']]
        );

        return new JsonResponse($serializedLead, Response::HTTP_OK, [], true);
    }

}