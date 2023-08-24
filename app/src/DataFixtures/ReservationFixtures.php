<?php
/**
 * Reservation fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ReservationFixtures.
 */
class ReservationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            10,
            'reservations',
            function ($i) {
                $reservation = new Reservation();
                $reservation->setRentalDate($this->
                faker->dateTimeBetween('-100 days', '-1 days'));
                $reservation->setReturnDate($this->
                faker->dateTimeBetween('-100 days', '-1 days'));
                $reservation->setElement($this->getRandomReference('elements'));
                $reservation->setUser($this->getRandomReference('users'));
                $reservation->setComment($this->faker->sentence);

                return $reservation;
            }
        );

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
