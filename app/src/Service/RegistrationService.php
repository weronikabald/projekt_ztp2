<?php
/**
 * Registration service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UsersData;
use App\Repository\UserRepository;
use App\Repository\UsersDataRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationService.
 */
class RegistrationService
{
    /**
     * Password encoder.
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Users Data repository.
     */
    private UsersDataRepository $usersDataRepository;

    /**
     * RegistrationService constructor.
     *
     * @param \App\Repository\UserRepository                                        $userRepository      User repository
     * @param \App\Repository\UsersDataRepository                                   $usersDataRepository Users Data repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder     Password Encoder
     */
    public function __construct(UserRepository $userRepository, UsersDataRepository $usersDataRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->usersDataRepository = $usersDataRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Save user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Register.
     *
     * @param                       $data
     * @param \App\Entity\User      $user      User entity
     * @param \App\Entity\UsersData $usersData Users Data entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function register($data, User $user, UsersData $usersData)
    {
        $user->setEmail($data['email']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $data['password'])
        );
        $user->setRoles(['ROLE_USER']);
        $user->setUsersData($usersData);
        $usersData->setFirstName($data['firstName']);
        $usersData->setLastName($data['lastName']);

        $this->usersDataRepository->save($usersData);
        $this->userRepository->save($user);
    }
}
