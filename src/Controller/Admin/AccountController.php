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

/**
 * @Route("/acp/accounts")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AccountController extends AbstractController
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
    public function edit(Request $request, Account $account): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
//            dump($form->getData());
//            die;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();

            $this->addFlash('success', 'asdas');
            return $this->redirectToRoute('admin_users_edit');
        }

        return $this->render('admin/account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}