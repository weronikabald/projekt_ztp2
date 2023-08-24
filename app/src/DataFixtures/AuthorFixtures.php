<?php
/**
 * Author fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AuthorFixtures.
 */
class AuthorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            20,
            'authors',
            function ($i) {
                $author = new Author();
                $author->setName($this->faker->firstName);
                $author->setSurname($this->faker->lastName);

                return $author;
            }
        );

        $manager->flush();
    }
}
