<?php
define('XPERT_PATH', dirname(__FILE__).'/..');
require_once(XPERT_PATH. '/cart.php');
require_once(XPERT_PATH. '/../wp-session-manager/class-recursive-arrayaccess.php');
require_once(XPERT_PATH. '/../wp-session-manager/class-wp-session.php');



class CartStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testAddItem()
    {
        $storage = new CartStorage();
        $item = new stdClass();
        $item->title = "he he";
        $storage->set('some_key', $item);
        $this->assertEquals($storage->get('some_key'), $item);
    }


}