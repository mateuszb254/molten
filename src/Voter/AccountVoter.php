<?php

namespace App\Voter;

use App\Entity\Account;
use App\Entity\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class AccountVoter extends Voter
{
    const EDIT = 'EDIT';
    const CHANGE_ROLE = 'CHANGE_ROLE';

    public function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::CHANGE_ROLE])) return false;

        if (!$subject instanceof Account) return false;

        return true;
    }

    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Account) return false;

        if ($user->getRole() == Account::GLOBAL_ADMIN_ROLE) return true;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($token->getUser(), $subject);
                break;
            case self::CHANGE_ROLE:
                return $this->canChangeRole($token->getUser(), $subject);
                break;
        }

        throw new LogicException('Not known attribute.');
    }

    private function canEdit(Account $editor, Account $account)
    {
        /** @var Role */
        $editorRole = $editor->getRole();
        $accountRole = $account->getRole();

        if ($editorRole->getRole() === Account::ADMIN_ROLE && $accountRole->getRole() !== Account::GLOBAL_ADMIN_ROLE)
            return true;


        return false;
    }

    private function canChangeRole(Account $editor, Account $account)
    {
        /** @var Account */
        $editorRole = $editor->getRole();
        $accountRole = $account->getRole();

        if ($editorRole->getRole() === Account::GLOBAL_ADMIN_ROLE && $accountRole->getRole() !== Account::GLOBAL_ADMIN_ROLE)
            return true;

        return false;
    }
}