<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'category_new')]
    public function addCategory(EntityManagerInterface $em, Request $request):Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
          $em->persist($category);
          $em->flush(); 
          return $this->redirectToRoute('category');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form
            ]);
    }

    #[Route('/category/{id}/update', name: 'category_update')]
    public function update(Category $category, EntityManagerInterface $em, Request $request):Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('category/update.html.twig', [
            'form' => $form
            ]);
    }

    #[Route('/category/{id}/delete', name: 'category_delete')]
    public function delete(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category');
    }
}
