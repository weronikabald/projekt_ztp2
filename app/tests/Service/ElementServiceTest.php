<?php
/**
 * Element service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Element;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Service\ElementService;
use App\Service\ElementServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ElementServiceTest.
 */
class ElementServiceTest extends KernelTestCase
{
    /**
     * Element repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Element service.
     */
    private ?ElementServiceInterface $elementService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->elementService = $container->get(ElementService::class);
    }

    /**
     * Test save.
     *
     * @throws OptimisticLockException|ORMException|NotFoundExceptionInterface|ContainerExceptionInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function testSave(): void
    {
        // given
        $expectedElement = new Element();
        $author = $this->createUser([UserRole::ROLE_ADMIN->value], 'element_save@example.com');
        $expectedElement->setTitle('Test Element Save');
        $expectedElement->setCreatedAt(new \DateTimeImmutable('now'));
        $expectedElement->setUpdatedAt(new \DateTimeImmutable('now'));
        $expectedElement->setCategory($this->createCategory('save', $author));

        // when
        $this->elementService->save($expectedElement);

        // then
        $expectedElementId = $expectedElement->getId();
        $resultElement = $this->entityManager->createQueryBuilder()
            ->select('element')
            ->from(Element::class, 'element')
            ->where('element.id = :id')
            ->setParameter(':id', $expectedElementId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedElement, $resultElement);
    }

    /**
     * Test delete.
     *
     * @throws OptimisticLockException|ORMException|NotFoundExceptionInterface|ContainerExceptionInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function testDelete(): void
    {
        // given
        $elementToDelete = new Element();
        $author = $this->createUser([ UserRole::ROLE_ADMIN->value], 'element_delete@example.com');
        $elementToDelete->setTitle('Test Element Delete');
        $elementToDelete->setCreatedAt(new \DateTimeImmutable('now'));
        $elementToDelete->setUpdatedAt(new \DateTimeImmutable('now'));
        $elementToDelete->setCategory($this->createCategory('delete', $author));

        $this->entityManager->persist($elementToDelete);
        $this->entityManager->flush();
        $deletedElementId = $elementToDelete->getId();

        // when
        $this->elementService->delete($elementToDelete);

        // when
        $resultElement = $this->entityManager->createQueryBuilder()
            ->select('element')
            ->from(Element::class, 'element')
            ->where('element.id = :id')
            ->setParameter('id', $deletedElementId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultElement);
    }

    /**
     * Create Category.
     */
    private function createCategory($categoryTitle, User $user): Category
    {
        $category = new Category();
        $category->setTitle($categoryTitle);
        $category->setCreatedAt(new \DateTimeImmutable('now'));
        $category->setCreatedAt(new \DateTimeImmutable('now'));
        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|\Doctrine\ORM\ORMException|OptimisticLockException
     */
    protected function createUser(array $roles, string $email): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
