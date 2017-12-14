<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * Tries to find a User by $username or $email
     *
     * @param   string $loginCredential Either the username or email to be validated
     * @return  UserInterface | null
     */
    public function loadUserByUsername($loginCredential)
    {
        return $this->createQueryBuilder('user')
            ->where('user.username = :username OR user.email = :email')
            ->setParameter('username', $loginCredential)
            ->setParameter('email', $loginCredential)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $username
     * @return null|object
     * @throws \Exception
     */
    public function findOneByUsername(string $username)
    {
        return $this->findOneBy(array('username' => $username));
    }

    /**
     * @param string $email
     * @return null|object
     * @throws \Exception
     */
    public function findOneByEmail(string $email)
    {
        return $this->findOneBy(array('email' => $email));
    }
}
