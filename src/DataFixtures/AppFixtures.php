<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('Fr-fr');

        for ($i = 0; $i <  30; $i++) {

            $title =  $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $address =  $faker->address();

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
                ->setIsLoan(false);

            $manager->persist($property);
        }

        $manager->flush();
    }
}