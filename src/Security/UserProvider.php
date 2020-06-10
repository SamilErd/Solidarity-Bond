<?php

namespace App\Security;

use App\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

class UserProvider implements UserProviderInterface
{
    

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($email)
    {
    $criteria = new Criteria();
    $criteria->Where(new Comparison("email", Comparison::EQ, $email));
    $criteria->setMaxResults(1);

    $userData = $this->em->getRepository("NamespaceMyBundle:User")->matching($criteria)->first();

    if ($userData != null) {
        switch ($userData->getActive()) {
            case User::DISABLED:
            throw new DisabledException("Your account is disabled. Please contact the administrator.");
                break;
            case User::WAIT_VALIDATION:
                throw new LockedException("Your account is locked. Check and valid your email account.");
                break;
            case User::ACTIVE:
                return $userData;
                break;
        }
    }
        return $this->user->getFirstName($firstname);

        throw new \Exception('TODO: fill in loadUserByUsername() inside ' . __FILE__);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.


        return $this->user->getFirstName();
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
