<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Review;
use AppBundle\Form\ReviewAddType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     *
     * @Route("/product/{id}/leave-message", name="review_add")
     * @return RedirectResponse | Response
     */
    public function addReviewAction(Request $request, $id)
    {
        if (null === $this->getUser()) {
            $this->addFlash('warning', 'You must be logged in to leave reviews');
            return $this->redirectToRoute('homepage');
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        if ($this->getUser() === $product->getUser()) {
            $this->addFlash('warning', 'You can\'t leave reviews for your own products');
            return $this->redirectToRoute('product_view_one', array(
                'id' => $id
            ));
        }

        $review = new Review();
        $form = $this->createForm(ReviewAddType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review->setProduct($product);
            $review->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Review added');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'product/review_add.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @param $productId
     * @param $reviewId
     * @Route("/product/{productId}/review/edit/{reviewId}", name="review_edit")
     * @return RedirectResponse | Response
     */
    public function editAction(Request $request, $productId, $reviewId)
    {
        if (null === $this->getUser()) {
            $this->addFlash('warning', 'You must be logged in');
            return $this->redirectToRoute('homepage');
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        $review = $this->getDoctrine()
            ->getRepository(Review::class)
            ->find($reviewId);

        if (null === $review) {
            $this->addFlash('warning', 'No such review exists');
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ReviewAddType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Message edited');
            return $this->redirectToRoute('product_view_one', array(
                'id' => $productId
            ));
        }

        return $this->render(
            'product/review_edit.html.twig', array(
                'product' => $product,
                'review' => $review,
                'form' => $form->createView()
        ));
    }

    /**
     *
     * @param $productId
     * @param $reviewId
     *
     * @Route("/product/{productId}/review/delete/{reviewId}", name="review_delete")
     * @Method("POST")
     * @return RedirectResponse | Response
     */
    public function deleteReviewAction($productId, $reviewId)
    {
        if (null === $this->getUser()) {
            $this->addFlash('warning', 'You must be logged in');
            return $this->redirectToRoute('homepage');
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (null === $product) {
            $this->addFlash('warning', 'No such product exists');
            return $this->redirectToRoute('homepage');
        }

        $review = $this->getDoctrine()
            ->getRepository(Review::class)
            ->find($reviewId);

        if (null === $review) {
            $this->addFlash('warning', 'No such review exists');
            return $this->redirectToRoute('homepage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($review);
        $entityManager->flush();

        $this->addFlash('success', 'Message deleted');
        return $this->redirectToRoute('product_view_one', array(
           'id' => $productId
        ));
    }
}
