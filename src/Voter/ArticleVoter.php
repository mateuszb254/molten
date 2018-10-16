<?php


namespace App\Voter;


use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    public function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) return false;

        if (!$subject instanceof Article) return false;

        return true;
    }

    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Account) return false;

        if ($user->getRole() == Account::GLOBAL_ADMIN_ROLE) return true;

        /** @var Role */
        $authorRole = $subject->getAuthor()->getRole();
        $editorRole = $user->getRole();

        if ($editorRole->getRole() === Account::ADMIN_ROLE && $authorRole->getRole() !== Account::GLOBAL_ADMIN_ROLE)
            return true;

        if ($editorRole->getRole() === Account::MODERATOR_ROLE && $authorRole->getRole() === Account::MODERATOR_ROLE)
            return true;


        return false;
    }
}