<?php


class XPertCart
{

    /** @var CartStorage */
    private $storage;

    public function __construct()
    {
        $this->storage = new CartStorage();
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {

        $this->storage = $storage;
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }


    public function addItem($item)
    {
        $current_items = $this->storage->get("cart_items", array());
        $current_items[] = $item;
        $this->storage->set('cart_items', $current_items);
    }

    public function getItems()
    {
        $items = $this->storage->get('cart_items', array());
        return $items;
    }

    public function clear()
    {
        $this->storage->set('cart_items', array());
    }

    public function remove($id)
    {
        $current_items = $this->storage->get("cart_items", array());
        $to_remove = null;
        foreach ($current_items as $key => $val) {
            if ($val->ID == $id) {
                $to_remove = $key;
                break;
            }
        }
        unset($current_items[$to_remove]);
        $this->storage->set('cart_items', $current_items);
    }

    public function getItemsForDisplay()
    {
        $items = $this->storage->get('cart_items', array());
        $result = array();
        foreach ($items as $item) {
            if (!array_key_exists($item->ID, $result)) {
                $item->quantity = 1;
                $result[$item->ID] = $item;
            } else {
                $result[$item->ID]->quantity++;
            }

        }
        return $result;
    }

}

class CartStorage
{
    /**
     * @var WP_Session
     */
    private $storage;

    public function __construct()
    {
        $this->storage = WP_Session::get_instance();

    }

    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function get($key, $default = null)
    {
        $storage = $this->storage->toArray();
        if ($storage[$key]) {
            $value = $storage[$key];
            return $value;
        }
        $storage[$key] = $default;
        return $storage[$key];

    }

}

