<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;

class UserController extends Controller
{


    /**
     * @Route("/{username}/profile/view", name="user_profile_view")
     *
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProfileAction(string $username)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByUsername($username);

        if (null === $user) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'user/profile_view.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/{username}/profile/edit", name="user_profile_edit")
     *
     * @param Request $request
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param string $username
     */
    public function editProfileAction(Request $request, string $username)
    {
        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByUsername($username);

        if (null === $user) {
            return $this->redirectToRoute('homepage');
        }

        if ($user !== $this->getUser() || in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('warning', 'You can\'t edit other profiles');

            $this->redirectToRoute('user_profile_view', array(
                'username' => $this->getUser()->getUsername()
            ));
        }

        $form = $this->createForm(UserEditType::class, $user);
        $user->setRawPassword(uniqid());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $country = Intl::getRegionBundle()->getCountryName($user->getCountry());
            $user->setCountry($country);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile edited!');

            return $this->redirectToRoute('user_profile_view', array(
                'username' => $user->getUsername()
            ));
        }

        return $this->render(
            'user/profile_edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }
}
