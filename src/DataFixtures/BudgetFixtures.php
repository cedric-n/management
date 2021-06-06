<?php


namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Budget;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BudgetFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager) {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 4; $i++) {

            $budget = new Budget();
            $budget->setName($faker->jobTitle);
            $budget->setType($faker->numberBetween(0,1));
            $budget->setSlug($budget->getName());
            $budget->setUserLink($this->getReference('admin'));

            $manager->persist($budget);
            $this->addReference('budget_' . $i, $budget);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [
            UserFixtures::class,
        ];
    }

}