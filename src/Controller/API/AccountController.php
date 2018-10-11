<?php


namespace App\Controller\API;


use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("api")
 */
class AccountController extends AbstractController implements APIControllerInterface
{
    /**
     * @Route("/accounts/count", name="api_users_count")
     */
    public function count(AccountRepository $accountRepository)
    {
        return $this->json($accountRepository->findCountOfAccounts());
    }
}