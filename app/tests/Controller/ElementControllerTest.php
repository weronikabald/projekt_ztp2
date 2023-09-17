<?php
/**
 * Element Controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Element;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ElementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ElementControllerTest.
 */
class ElementControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/element';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException|\Exception
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value,], 'test_element__admin@example.com');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test show single element.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testShowElement(): void
    {
        // given
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_show_element@example.com');
        $this->httpClient->loginUser($adminUser);

        $expectedElement = new Element();
        $expectedElement->setTitle('Test 2 element');
        $expectedElement->setCategory($this->createCategory('Test Category Show Element'));
        $elementRepository = static::getContainer()->get(ElementRepository::class);
        $elementRepository->save($expectedElement);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE . '/' . $expectedElement->getId());
        $result = $this->httpClient->getResponse();

        // then
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertSelectorTextContains('html h1', $expectedElement->getTitle());
    }

    /**
     * Test create element.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testCreateElement(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_element_create@example.com');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE . '/create');

        // when
        $this->httpClient->submitForm(
            'Utwórz',
            ['element' => ['title' => 'Test Element']]
        );

        // then
        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test edit element.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testEditElement(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_element_edit@example.com');
        $this->httpClient->loginUser($user);

        $elementRepository = static::getContainer()->get(ElementRepository::class);
        $testElement = new Element();
        $testElement->setTitle('edited element');
        $testElement->setCreatedAt(new \DateTimeImmutable('now'));
        $testElement->setUpdatedAt(new \DateTimeImmutable('now'));
        $testElement->setSlug('edited-element');
        $testElement->setCategory($this->createCategory('Test Category 11'));
        $elementRepository->save($testElement);
        $testElementId = $testElement->getId();
        $expectedNewElementTitle = 'test element edit';

        $this->httpClient->request(
            'GET', self::TEST_ROUTE . '/' .
            $testElementId . '/edit'
        );

        // when
        $this->httpClient->submitForm(
            'Edytuj',
            [
                'element' => [
                    'title' => $expectedNewElementTitle,
                    'stock' => '1',
                    'category' => $testElement->getCategory()->getId(),
                ]]
        );

        // then
        $savedElement = $elementRepository->findOneById($testElementId);
        $this->assertEquals($expectedNewElementTitle, $savedElement->getTitle());
    }

    /**
     * Test delete element.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testDeleteElement(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value], 'test_element_delete@example.com');
        $this->httpClient->loginUser($user);

        $elementRepository = static::getContainer()->get(ElementRepository::class);
        $testElement = new Element();
        $testElement->setTitle('TestElementCreated');
        $testElement->setCreatedAt(new \DateTimeImmutable('now'));
        $testElement->setUpdatedAt(new \DateTimeImmutable('now'));
        $testElement->setCategory($this->createCategory('Test Category 111'));
        $elementRepository->save($testElement);
        $testElementId = $testElement->getId();

        $this->httpClient->request('GET', self::TEST_ROUTE . '/' . $testElementId . '/delete');

        // when
        $this->httpClient->submitForm(
            'Usuń'
        );

        // then
        $this->assertNull($elementRepository->findOneByTitle('TestElementCreated'));
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    private function createUser(array $roles, $email): User
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
        $userRepository->save($user, true);

        return $user;
    }

    /**
     * Create Category.
     *
     * @param string $name Category name
     *
     * @return Category Category entity
     */
    private function createCategory($name): Category
    {
        $category = new Category();
        $category->setTitle($name);
        $category->setCreatedAt(new \DateTimeImmutable('now'));
        $category->setUpdatedAt(new \DateTimeImmutable('now'));
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }
}
