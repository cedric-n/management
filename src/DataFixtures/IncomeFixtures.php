<?php


namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\Income;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IncomeFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');
        // TODO: Implement load() method.

        for ($i = 0; $i < 4; $i++) {

            $income = new Income();
            $income->setName($faker->text(12));
            $income->setPrice($faker->numberBetween(0,1000));
            $income->setFrequency($faker->numberBetween(0,4));
            $income->setBudget($this->getReference('budget_' . $faker->numberBetween(0,3) ));
            $income->setSlug($income->getName());
            $income->setOwner($this->getReference('admin'));

            $manager->persist($income);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [
            BudgetFixtures::class,
            UserFixtures::class,
        ];
    }
}