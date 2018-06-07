<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/accounts")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AccountController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("/", name="admin_users_index")
     */
    public function index(AccountRepository $accountRepository): Response
    {
        $users = $accountRepository->findAll();

        return $this->render('admin/account/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/{login}/edit", name="admin_users_edit")
     */
    public function edit(Request $request, Account $account, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('user.edit.success', [
                '%admin_users_index%' => $this->generateUrl('admin_users_index')
            ]));

            return $this->redirectToRoute('admin_users_edit', [
                'login' => $account->getLogin()
            ]);
        }

        return $this->render('admin/account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}