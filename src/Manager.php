<?php

namespace ShopifyApi;

use BadMethodCallException;
use ShopifyApi\Models\Order;
use ShopifyApi\Models\Product;
use ShopifyApi\Models\Variant;

/**
 * Class Manager
 *
 * @method Client product()
 */
class Manager
{

    /** @var Client $client */
    protected $client;

    /**
     * Constructor.
     *
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the instance with the Facade. Shopify::getInstance();
     *
     * @return $this
     */
    public function getInstance()
    {
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get a product by id or create a new one
     *
     * @param int $id the Product id
     *
     * @return Product
     */
    public function getProduct($id = null)
    {
        return new Product($this->client, $id);
    }

    /**
     * Get all the products from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param array $params
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllProducts(array $params = [])
    {
        $products = (new Product($this->client))->all($params);
        return defined('LARAVEL_START') ? collect($products) : $products;
    }

    /**
     * Get an order by id or create a new one
     *
     * @param int $id the Order id
     *
     * @return Order
     */
    public function getOrder($id = null)
    {
        return new Order($this->client, $id);
    }

    /**
     * Get a variant by id or create a new one
     *
     * @param int $id Variant id
     * @return Variant
     */
    public function getVariant($id = null)
    {
        return new Variant($this->client , $id);
    }

    /**
     * Get all the variants from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param $product_id
     * @param array $params
     * @return array|\Illuminate\Support\Collection
     */
    public function getAllVariants($product_id, array $params = [])
    {
        $variants = (new Variant($this->client, null, $product_id))->all($params);
        return defined('LARAVEL_START') ? collect($variants) : $variants;
    }

    /**
     * @param string $method method name
     * @param array  $args arguments
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (method_exists($this->client, $method)) {
            return call_user_func_array([$this->client, $method], $args);
        } else {
            throw new BadMethodCallException("The Manager __call method fires methods that exist on ".get_class($this->client));
        }
    }

}
