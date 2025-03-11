<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();
            $product->setTitle($faker->sentence(3));
            $product->setDescription($faker->paragraph());
            $product->setPriceExclVat($faker->randomFloat(2, 10, 500));

            $randomCategoryIndex = array_rand(CategoryFixtures::CATEGORIES);
            $product->setCategory($this->getReference('category_' . $randomCategoryIndex, Category::class));

            $product->setImage('default.jpg');

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }
}