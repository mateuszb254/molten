<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Service\Recaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

class SecurityController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Recaptcha $recaptcha, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($recaptcha->validate($request->get('g-recaptcha-response'))) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $account = $form->getData();

                    $password = $passwordEncoder->encodePassword($account, $account->getPlainPassword());
                    $account->setPassword($password);

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($account);
                    $em->flush();

                    $this->addFlash('success', $translator->trans('registration.success'));
                    return $this->redirectToRoute('register');
                }
            } else {
                $this->addFlash('alert', $translator->trans('recaptcha'));
            }
        }

        return $this->render('user/register.html.twig', [
            'register_form' => $form->createView(),
            'recaptcha' => $recaptcha->createView()
        ]);
    }

    /**
     * @Route("/forgotten", name="forgotten")
     * @Method({"GET", "POST"})
     */
    public function forgotten()
    {
        //TODO
        return $this->render('security/forgotten.html.twig');
    }

    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     * @Method("GET")
     */
    public function logout()
    {
    }
}