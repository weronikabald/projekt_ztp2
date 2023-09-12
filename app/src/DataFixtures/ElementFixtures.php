<?php
/**
 * Element fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Element;

/**
 * Class ElementFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class ElementFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'elements', function (int $i) {
            $element = new Element();
            $element->setTitle($this->faker->unique()->word);
            $element->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $element->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $element->setSlug($this->faker->unique()->word);
            $element->setStock($this->faker->numberBetween(1, 100));
            $element->setCategory($this->getRandomReference('categories'));

            return $element;
        });

        $this->manager->flush();
    }
}
