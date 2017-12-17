<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/add", name="category_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryFromDb = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findOneByName($category->getName());

            if (null !== $categoryFromDb) {
                $this->addFlash('warning', 'Category with that name already exists');
                return $this->render(
                    'category/add_new.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category added');
            return $this->redirectToRoute('category_view_all');
        }

        return $this->render(
            'category/add_new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/all", name="category_view_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/view_all.html.twig', array(
                'categories' => $categories
        ));
    }

    /**
     * @Route("/category/{name}", name="category_view_by_one")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewBySingleCategoryAction($name)
    {
        $productsByCategory = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findByCategory($name);

        return $this->render(
            'product/view_all_by_category.html.twig', array(
                'products' => $productsByCategory
        ));
    }
}
