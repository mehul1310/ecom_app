<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function list
    (
        ProductRepository $productRepository,
        Request $request,
        CategoryRepository $categoryRepository
    ): Response {
        $page = $request->query->getInt('page', 1);
        $sortBy = $request->query->get('sort', 'title');
        $category = $request->query->get('category');
        $categories = $categoryRepository->findAll();

        $paginationData = $productRepository->findPaginated($page, $sortBy, $category);

        return $this->render('product/index.html.twig', [
            'products' => $paginationData['items'],
            'totalPages' => $paginationData['totalPages'],
            'currentPage' => $paginationData['currentPage'],
            'sortBy' => $sortBy,
            'selectedCategoryId' => $category,
            'categories' => $categories
        ]);
    }

    #[Route('/products/{id}', name: 'product_detail')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                    $product->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image.');
                }
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product added successfully!');
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}