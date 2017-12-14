<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserRegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * Responsible for handling the register process
     *
     * @Route("/register", name="security_register")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You\'re already registered!');

            return $this->redirectToRoute(
                'user_profile_view', array(
                'username' => $this->getUser()->getUsername()
            ));
        }

        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (null !== $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneByUsername($user->getUsername())
            ) {
                $this->addFlash('warning', 'Username is taken');

                return $this->render(
                    ':security:register.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            if (null !== $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneByEmail($user->getEmail())
            ) {
                $this->addFlash('warning', 'Email is taken');

                return $this->render(
                    ':security:register.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            $hashedPassword = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getRawPassword());

            $country = Intl::getRegionBundle()->getCountryName($user->getCountry());
            $user->setCountry($country);

            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(array('name' => 'ROLE_USER'));
            $user->addRole($role);

            $user->setPassword($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'security/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Responsible for handling the login process
     *
     * @Route("/login", name="security_login")
     *
     * @param AuthenticationUtils $authUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You\'re already logged!');

            return $this->redirectToRoute(
                'user_profile_view', array(
                'username' => $this->getUser()->getUsername()
            ));
        }

        return $this->render(
            'security/login.html.twig', array(
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError()
        ));
    }

    public function displayLoginFormAction(AuthenticationUtils $authUtils)
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You\'re already logged!');

            return $this->redirectToRoute(
                'user_profile_view', array(
                'username' => $this->getUser()->getUsername()
            ));
        }

        return $this->render(
            'security/login_form.html.twig', array(
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError()
        ));
    }

    public function displayRegisterFormAction()
    {
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        return $this->render(
            'security/register_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Please login again!');
    }
}
