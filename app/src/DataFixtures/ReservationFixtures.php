<?php
/**
 * Reservation fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Element;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ReservationFixtures.
 */
class ReservationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 100; ++$i) {
            $reservation = new Reservation();
            $reservation->setComment($this->faker->paragraphs(2, true));
            $reservation->setEmail($this->faker->email);
            $reservation->setNickname($this->faker->userName);
            $reservation->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $reservation->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $reservation->setStatus('new');

            /** @var Element $element */
            $element = $this->getRandomReference('elements');
            $reservation->setElement($element);

            $this->manager->persist($reservation);
        }

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: elementixtures::class}
     */
    public function getDependencies(): array
    {
        return [
            ElementFixtures::class,
        ];
    }
}
