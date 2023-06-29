<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Car;
use App\Entity\CarCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $categories = ['Sedan', 'SUV', 'Sports', 'Hatchback', 'Convertible'];

        foreach ($categories as $categoryName) {
            $category = new CarCategory();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();

        $carCategories = $manager->getRepository(CarCategory::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $product = new Car();
            $product->setNbSeats($faker->numberBetween(2, 9));
            $product->setNbDoors($faker->numberBetween(2, 5));
            $product->setName($faker->word($faker->numberBetween(5, 10)));
            $product->setCost($faker->numberBetween(10000, 40000));
            $category = $faker->randomElement($carCategories);
            $product->setCategory($category);            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
