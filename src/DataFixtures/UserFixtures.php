<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordEncoderInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {


        // $product = new Product();
        // $manager->persist($product);
        $contributor = new User();
        $contributor->setFirstname('Goe');
        $contributor->setLastname('Yown');
        $contributor->setBirthday(DateTime::createFromFormat('d/m/Y','01/12/1996'));
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordHasher->encodePassword(
                         $contributor,
                         'contributorpassword'
                     ));

        $this->addReference('contributor_', $contributor);
        $manager->persist($contributor);

        $admin = new User();
        $admin->setFirstname('Admin');
        $admin->setLastname('First');
        $admin->setBirthday(DateTime::createFromFormat('d/m/Y','01/12/1997'));
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->encodePassword(
            $admin,
            'adminpassword'
        ));

        $this->addReference('admin', $admin);
        $manager->persist($admin);

        $manager->flush();
    }
}
