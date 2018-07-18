<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\ChangeCodeType;
use App\Form\ChangeEmailType;
use App\Form\ChangePasswordType;
use App\Service\UserLogger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_USER')")
 */
class UserController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("/panel", name="user_panel")
     * @Method("GET")
     */
    public function userPanel(): Response
    {
        return $this->render('user/panel/index.html.twig');
    }

    /**
     * @Route("/logs", name="user_logs")
     */
    public function logs()
    {
        /** @var $user Account */
        $user = $this->getUser();
        $logs = $user->getLogs();

        return $this->render('user/panel/logs.html.twig', [
            'logs' => $logs
        ]);
    }

    /**
     * @Route("/password", name="user_change_password")
     * @Method({"GET", "POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator, UserLogger $userLogger): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $newPassword = ($form->getData())->getNewPassword();
            $user = $this->getUser();

            $password = $passwordEncoder->encodePassword($user, $newPassword);

            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $userLogger->addLog($user, 'CHANGE_PASSWORD');

            $this->addFlash('success', $translator->trans('change.password.success'));
            return $this->redirectToRoute('user_change_password');
        }

        return $this->render('user/panel/changepassword.html.twig', [
            'change_password_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mail", name="user_change_email")
     * @Method({"GET", "POST"})
     */
    public function changeEmail(Request $request, TranslatorInterface $translator, UserLogger $userLogger): Response
    {
        $form = $this->createForm(ChangeEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $newMail = ($form->getData())->getNewEmail();

            $user = $this->getUser();
            $user->setEmail($newMail);

            $em->persist($user);
            $em->flush();

            $userLogger->addLog($user, 'CHANGE_EMAIL');

            $this->addFlash('success', $translator->trans('change.email.success'));
            return $this->redirectToRoute('user_change_email');
        }

        return $this->render('user/panel/changemail.html.twig', [
            'change_email_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/code", name="user_change_code")
     * @Method({"GET", "POST"})
     */
    public function changeCode(Request $request, TranslatorInterface $translator, UserLogger $userLogger): Response
    {
        $form = $this->createForm(ChangeCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $newCode = ($form->getData())->getNewCode();

            $user = $this->getUser();
            $user->setCode($newCode);

            $em->persist($user);
            $em->flush();

            $userLogger->addLog($user, 'CHANGE_CODE');

            $this->addFlash('success', $translator->trans('change.code.success'));
            return $this->redirectToRoute('user_change_code');
        }

        return $this->render('user/panel/changecode.html.twig', [
            'change_code_form' => $form->createView()
        ]);
    }
}