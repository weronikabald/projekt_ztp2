<?php
/**
 * Reservation service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Element;
use App\Entity\Reservation;
use App\Repository\CategoryRepository;
use App\Repository\ElementRepository;
use App\Service\ReservationService;
use App\Service\ReservationServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ReservationServiceTest.
 */
class ReservationServiceTest extends KernelTestCase
{
    /**
     * Reservation repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Reservation service.
     */
    private ?ReservationServiceInterface $reservationService;

    /**
     * Set up test.
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->elementService = $container->get(ReservationService::class);
        $this->reservationService = $container->get(ReservationService::class);
    }

    /**
     * Test save.
     * @throws NonUniqueResultException
     */
    public function testSave(): void
    {
        // given
        $expectedReservation = new Reservation();
        $expectedReservation->setComment('test_comment');
        $expectedReservation->setEmail('test_email');
        $expectedReservation->setNickname('test_nickname');
        $expectedReservation->setCreatedAt(new DateTimeImmutable('now'));
        $expectedReservation->setUpdatedAt(new DateTimeImmutable('now'));
        $element = $this->createElement('test_category_s', 'test_element_s');
        $expectedReservation->setStatus('new');
        // when
        $this->reservationService->save($expectedReservation, $element->getId());
        // then
        $expectedReservationId = $expectedReservation->getId();
        $resultReservation = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter('id', $expectedReservationId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedReservation, $resultReservation);
    }

    /**
     * Test accept.
     * @throws NonUniqueResultException
     */
    public function testAccept(): void
    {
        // given
        $expectedReservation = new Reservation();
        $expectedReservation->setComment('test_comment');
        $expectedReservation->setEmail('test_email');
        $expectedReservation->setNickname('test_nickname');
        $expectedReservation->setCreatedAt(new DateTimeImmutable('now'));
        $expectedReservation->setUpdatedAt(new DateTimeImmutable('now'));
        $element = $this->createElement('test_category_a', 'test_element_a');
        $expectedReservation->setElement($element);
        $expectedReservation->setStatus('new');
        // when
        $this->reservationService->accept($expectedReservation);
        // then
        $expectedReservationId = $expectedReservation->getId();
        $resultReservation = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter('id', $expectedReservationId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedReservation, $resultReservation);
        $this->assertEquals('accepted', $resultReservation->getStatus());
    }

    /**
     * Test out of stock.
     * @throws NonUniqueResultException
     */
    public function testOutOfStock(): void
    {
        // given
        $expectedReservation = new Reservation();
        $expectedReservation->setComment('test_comment');
        $expectedReservation->setEmail('test_email');
        $expectedReservation->setNickname('test_nickname');
        $expectedReservation->setCreatedAt(new DateTimeImmutable('now'));
        $expectedReservation->setUpdatedAt(new DateTimeImmutable('now'));
        $element = $this->createElement('test_category_o', 'test_element_o');
        $element->setStock(0);
        $expectedReservation->setElement($element);
        $expectedReservation->setStatus('new');
        // when
        $this->reservationService->accept($expectedReservation);
        // then
        $expectedReservationId = $expectedReservation->getId();
        $resultReservation = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter('id', $expectedReservationId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedReservation, $resultReservation);
        $this->assertEquals('out_of_stock', $resultReservation->getStatus());
    }


    /**
     * Test return.
     * @throws NonUniqueResultException
     */
    public function testReturnReservation(): void
    {
        // given
        $expectedReservation = new Reservation();
        $expectedReservation->setComment('test_comment');
        $expectedReservation->setEmail('test_email');
        $expectedReservation->setNickname('test_nickname');
        $expectedReservation->setCreatedAt(new DateTimeImmutable('now'));
        $expectedReservation->setUpdatedAt(new DateTimeImmutable('now'));
        $element = $this->createElement('test_category_r', 'test_element_r');
        $expectedReservation->setElement($element);
        $expectedReservation->setStatus('new');
        // when
        $this->reservationService->returnReservation($expectedReservation);
        // then
        $expectedReservationId = $expectedReservation->getId();
        $resultReservation = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter('id', $expectedReservationId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedReservation, $resultReservation);
        $this->assertEquals('returned', $resultReservation->getStatus());
    }

    /**
     * Create Element.
     *
     * @return Element Element entity
     */
    private function createElement(string $categoryName, string $elTitle): Element
    {
        $element = new Element();
        $element->setTitle($elTitle);
        $element->setCreatedAt(new DateTimeImmutable('now'));
        $element->setUpdatedAt(new DateTimeImmutable('now'));
        $element->setCategory($this->createCategory($categoryName));
        $element->setStock(2);
        $elementRepository = static::getContainer()->get(ElementRepository::class);
        $elementRepository->save($element);

        return $element;
    }

    /**
     * Create Category.
     *
     *
     * @return Category Category entity
     */
    private function createCategory(string $name): Category
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