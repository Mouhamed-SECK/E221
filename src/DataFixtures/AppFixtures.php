<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Property;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $enocder)
    {
        $this->encoder = $enocder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('Fr-fr');
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Momo')
            ->setName('Seck')
            ->setEmail('momoSeck@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'admin'))
            ->addUserRole($adminRole);

        $manager->persist($adminUser);


        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName())
                ->setName($faker->name())
                ->setEmail($faker->email())
                ->setHash($hash);

            $manager->persist($user);
            $users[] = $user;
        }


        for ($i = 0; $i <  30; $i++) {

            $title =  $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $address =  $faker->address();

            $user = $users[mt_rand(0, count($users) - 1)];

            $property = new Property();
            $property->setTitle($title)
                ->setDescription($content)
                ->setCity("Dakar")
                ->setAddress($address)
                ->setPostalCode("20000")
                ->setUsageType(0, 2)
                ->setBadrooms(mt_rand(1, 7))
                ->setRooms(mt_rand(1, 10))
                ->setSurface(mt_rand(50, 300))
                ->setPrice(mt_rand(100000, 20000000))
                ->setCoverImage($coverImage)
                ->setStatus(mt_rand(0, 3))
                ->setPropertyOwner($user);

            for ($j = 0; $j < mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setProperty($property);

                $manager->persist($image);
            }

            $manager->persist($property);
        }

        $manager->flush();
    }
}