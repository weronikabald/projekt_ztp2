<?php
/**
 * Users Data fixture.
 */

namespace App\DataFixtures;

use App\Entity\UsersData;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UsersDataFixtures.
 */
class UsersDataFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            13,
            'user_data',
            function ($i) {
                $usersData = new UsersData();
                $usersData->setFirstName($this->faker->firstName);
                $usersData->setLastName($this->faker->lastName);

                return $usersData;
            }
        );

        $manager->flush();
    }
}
