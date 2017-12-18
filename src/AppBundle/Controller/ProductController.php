<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render(
            'default/index.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * @Route("/product/new", name="product_new")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addNewAction(Request $request)
    {
        if (null === $this->getUser()) {
            $this->addFlash('warning', 'You must be logged in to add products');

            return $this->redirectToRoute('security_login');
        }

        $product = new Product();
        $form = $this->createForm(ProductNewType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $product->getImageFromForm();

            if (null !== $image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->get('kernel')->getRootDir() . '/../web/public/img/uploaded',
                    $imageName
                );

                $product->setImage($imageName);
            }

            $product->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product added');
            return $this->redirectToRoute('homepage');
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'product/add_new.html.twig', array(
                'categories' => $categories,
            'form' => $form->createView()
        ));
    }

    /**
     * @param string $id
     *
     * @Route("/products/view/{id}", name="product_view_one")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function viewOneAction($id)
    {
        if (null === $this->getUser()) {
            return $this->redirectToRoute('security_register');
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'product/view_one.html.twig', array(
            'product' => $product
        ));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("/product/edit/{id}", name="product_edit")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        if ($product->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'You don\'t have rights to edit this product');
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ProductNewType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $product->getImageFromForm();

            if (null !== $image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->get('kernel')->getRootDir() . '/../web/public/img/uploaded',
                    $imageName
                );

                $product->setImage($imageName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product edited');
            return $this->redirectToRoute('homepage');
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'product/edit.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'form' => $form->createView()
        ));
    }

    /**
     *
     * @param $id
     *
     * @Route("/products/delete/{id}", name="product_delete")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        if ($product->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'You don\'t have rights to delete this product');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'product/delete.html.twig', array(
                'product' => $product
        ));
    }

    /**
     *
     * @param $id
     *
     * @Route("/products/delete/{id}", name="product_delete_confirmed")
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteConfirmedAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        if ($product->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'You don\'t have rights to delete this product');
            return $this->redirectToRoute('homepage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Product deleted');
        return $this->redirectToRoute('homepage');
    }
}
