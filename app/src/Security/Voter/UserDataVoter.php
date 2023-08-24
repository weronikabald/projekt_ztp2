<?php
/**
 * User Data Voter.
 */

namespace App\Security\Voter;

use App\Entity\UsersData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserDataVoter.
 */
class UserDataVoter extends Voter
{
    /**
     * Security helper.
     */
    private Security $security;

    /**
     * OrderVoter constructor.
     *
     * @param \Symfony\Component\Security\Core\Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['INDEX', 'EDIT', 'VIEW'])
            && $subject instanceof UsersData;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string                                                               $attribute
     * @param mixed                                                                $subject
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
            case 'EDIT':
                if ($this->security->isGranted('ROLE_USER')) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                break;
            case 'INDEX':
                if ($this->security->isGranted('ROLE_USER')) {
                    return false;
                }
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                break;
            default:
                return false;
                break;
        }

        return false;
    }
}
