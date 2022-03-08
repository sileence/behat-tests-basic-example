<?php

use App\ArrayProductRepository;
use App\Basket;
use App\Product;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private ArrayProductRepository $productRepository;
    private Basket $basket;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->productRepository = new ArrayProductRepository();
        $this->basket = new Basket(vatPercentage: 20);
    }

    /**
     * @Given there is a :productTitle, which costs £:pounds
     */
    public function thereIsAProductWithACostInPounds(string $productTitle, float $pounds)
    {
        $this->productRepository->save(new Product(
            title: $productTitle,
            costInCents: $pounds * 100,
        ));
    }

    /**
     * @When I add the :product to the basket
     */
    public function iAddTheToTheBasket(string $productTitle)
    {
        $product = $this->productRepository->getByTitle($productTitle);

        $this->basket->add($product);
    }

    /**
     * @Then /^I should have (\d+) products? in the basket$/
     */
    public function iShouldHaveProductInTheBasket($numberOfProducts)
    {
        Assert::assertCount($numberOfProducts, $this->basket);
    }

    /**
     * @Then the overall basket price should be £:pounds
     */
    public function theOverallBasketPriceShouldBePs(string $expectedTotalInPounds)
    {
        $actualTotalInPounds = $this->basket->getTotalInCents() / 100;

        Assert::assertEquals($expectedTotalInPounds, $actualTotalInPounds);
    }
}
