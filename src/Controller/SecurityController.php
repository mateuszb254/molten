<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\ForgottenPasswordType;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Repository\AccountRepository;
use App\Service\Mailer;
use App\Service\Recaptcha;
use App\Service\TokenGenerator;
use App\Service\UserLogger;
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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

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

        return $this->render('user/register.html.twig', [
            'register_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgotten", name="forgotten")
     * @Method({"GET", "POST"})
     */
    public function forgotten(Request $request, AccountRepository $accountRepository, TokenGenerator $tokenGenerator, Mailer $mailer, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $accountRepository->findAccountByEmail($form->get('email')->getData());
            $user->setResetPasswordToken($tokenGenerator->generateToken());
            $user->setResetPasswordTokenCreatedAt(new \DateTime());

            $em->flush();

            $mailer->sendResettingMessage($user);

            $this->addFlash('success', $translator->trans('reset-password.message_send.success'));
            return $this->redirectToRoute('forgotten');
        }

        return $this->render('security/forgotten.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function resetPassword(Request $request, AccountRepository $accountRepository, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator, UserLogger $logger): Response
    {
        $em = $this->getDoctrine()->getManager();

        if (!$request->get('token') || !$user = $accountRepository->findAccountByResetPasswordToken($request->get('token'))) {
            return $this->redirectToRoute('forgotten');
        }

        if (($user->getResetPasswordTokenCreatedAt())->add(new \DateInterval('PT' . Account::PASSWORD_TOKEN_EXPIRES_HOURS . 'H')) < new \DateTime()) {
            $user->setResetPasswordToken(null);
            $user->setResetPasswordTokenCreatedAt(null);

            $em->flush();

            $this->addFlash('alert', $translator->trans('reset-password.token.expired'));
            return $this->redirectToRoute('forgotten');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $user->setResetPasswordToken(null);
            $user->setResetPasswordTokenCreatedAt(null);

            $logger->addLog($user, 'RESET_PASSWORD');
            $em->flush();

            $this->addFlash('success', $translator->trans('reset-password.success'));
            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView()
        ]);
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