<?php
define('XPERT_PATH', dirname(__FILE__).'/..');
require_once(XPERT_PATH. '/cart.php');


class CartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return stdClass
     */
    private function createCartItem()
    {
        $item = new stdClass();
        $item->title = "Item";
        $item->ID = "some_id";
        return $item;
    }

    public function testAddToCart()
    {
        $cart = new XPertCart();
        $item = $this->createCartItem();
        $cart->addItem($item);
        $items = $cart->getItems();
        $this->assertEquals($item, $items[0]);
    }




}
