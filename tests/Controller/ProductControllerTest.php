<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;
    private Category $category;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->category = new Category();
        $this->category->setName('Test Category');
        $this->entityManager->persist($this->category);
        $this->entityManager->flush();
    }

    public function testProductList(): void
    {
        $this->client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
    }

    public function testPaginationAndSorting(): void
    {
        $this->client->request('GET', '/products?page=2&sort=priceExclVat');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.pagination');
    }

    public function testProductDetail(): void
    {
        $product = $this->createTestProduct('Test Product Detail', 50.00);
        $this->entityManager->flush();

        $this->client->request('GET', '/products/' . $product->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testProductCreation(): void
    {
        $crawler = $this->client->request('GET', '/product/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save')->form([
            'product[title]' => 'New Product',
            'product[description]' => 'Product description',
            'product[priceExclVat]' => 99.99,
            'product[category]' => $this->category->getId()
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', 'Product added successfully!');
    }

    private function createTestProduct(string $title, float $price): Product
    {
        $product = new Product();
        $product->setTitle($title);
        $product->setDescription('Description');
        $product->setPriceExclVat($price);
        $product->setCategory($this->category);

        $this->entityManager->persist($product);
        return $product;
    }
}
