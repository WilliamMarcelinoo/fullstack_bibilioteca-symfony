<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/books')]
class BookWebController extends AbstractController
{
    // Página de listagem de livros
    #[Route('', name: 'book_list', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('books/list.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    // Página para adicionar um livro
    #[Route('/add', name: 'book_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $author = $request->request->get('author');
            $year = (int) $request->request->get('year');
            $description = $request->request->get('description');

            if (!$title || !$author || $year <= 0) {
                $this->addFlash('error', 'Todos os campos são obrigatórios.');
                return $this->redirectToRoute('book_add');
            }

            $book = new Book();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setYear($year);
            $book->setDescription($description);

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livro adicionado com sucesso!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('books/add.html.twig');
    }

    // Página para editar um livro existente
    #[Route('/edit/{id}', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livro não encontrado');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $author = $request->request->get('author');
            $year = (int) $request->request->get('year');
            $description = $request->request->get('description');

            if (!$title || !$author || $year <= 0) {
                $this->addFlash('error', 'Todos os campos são obrigatórios.');
                return $this->redirectToRoute('book_edit', ['id' => $id]);
            }

            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setYear($year);
            $book->setDescription($description);

            $entityManager->flush();

            $this->addFlash('success', 'Livro atualizado com sucesso!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
        ]);
    }

    // Função para remover um livro
    #[Route('/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete($id, BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livro não encontrado');
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Livro removido com sucesso!');
        return $this->redirectToRoute('book_list');
    }
}
