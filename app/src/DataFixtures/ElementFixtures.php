<?php
/**
 * Element fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Element;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ElementFixtures.
 */
class ElementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            50,
            'elements',
            function ($i) {
                $element = new Element();
                $element->setTitle($this->faker->sentence);
                $element->setDescription($this->faker->text);
                $element->setAvailability($this->faker->randomDigit);
                $element->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));

                $element->setCategory($this->getRandomReference('categories'));

                $tags = $this->getRandomReferences(
                    'tags',
                    $this->faker->numberBetween(0, 5)
                );

                foreach ($tags as $tag) {
                    $element->addTag($tag);
                }

                $authors = $this->getRandomReferences(
                    'authors',
                    $this->faker->numberBetween(1, 3)
                );

                foreach ($authors as $author) {
                    $element->addAuthor($author);
                }

                return $element;
            }
        );

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, AuthorFixtures::class];
    }
}
