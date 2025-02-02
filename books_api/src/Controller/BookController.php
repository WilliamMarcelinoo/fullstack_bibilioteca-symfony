<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/books', name: 'api_books_')]
class BookController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();
        return $this->json($books);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data['title'] || !$data['author'] || !isset($data['year'])) {
            return $this->json(['error' => 'Todos os campos são obrigatórios'], 400);
        }

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setYear($data['year']);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->json($book, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update($id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->json(['error' => 'Livro não encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if ($data['title']) $book->setTitle($data['title']);
        if ($data['author']) $book->setAuthor($data['author']);
        if (isset($data['year'])) $book->setYear($data['year']);

        $entityManager->flush();

        return $this->json($book);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete($id, BookRepository $bookRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->json(['error' => 'Livro não encontrado'], 404);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->json(['message' => 'Livro deletado com sucesso']);
    }
}
