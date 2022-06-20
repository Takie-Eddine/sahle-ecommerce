<?php

namespace App\Basket;

use App\Exceptions\QuantityExceededException;
use App\Models\Product;
//use App\Support\Storage\Contracts\StorageInterface;
use App\Support\Storage\SessionStorage;
class Basket
{

	protected $storage;


	protected $product;


	public function __construct(SessionStorage $storage, Product $product)
	{
		$this->storage = $storage;
		$this->product = $product;
		//$this->coupon = $coupon;
	}


	public function add(Product $product, $quantity)
	{
		if ($this->has($product)) {
			$quantity = $this->get($product)['quantity'] + $quantity;
		}

		$this->update($product, $quantity);
	}

	public function addCoupon(Coupon $coupon)
	{
		if (!$this->checkCoupon($coupon)) {
			return false;
		}

		$product = $coupon->product;
		$p = $this->get($product);

		$this->storage->set($product->id, [
			'product_id' => (int) $product->id,
			'quantity' => $p['quantity'],
			'coupon' => $coupon->id,
		]);

		return true;
	}

	public function checkCoupon(Coupon $coupon)
	{
		if (!$this->has($coupon->product)) {
			return false;
		}

		if (!$coupon->active || !$coupon->isValid() || ($this->get($coupon->product)['quantity'] < $coupon->products_count)) {
			return false;
		}

		if ($coupon->members()->find(auth()->guard('site')->id())) {
			return false;
		}

		return true;
	}

	public function checkCouponById($id)
	{
		$coupon = Coupon::find($id);

		if(!$coupon){
			return false;
		}

		return $this->checkCoupon($coupon);
	}


	public function update(Product $product, $quantity)
	{
		if (! $this->product->find($product->id)->hasStock($quantity)) {
			throw new QuantityExceededException;
		}

		if ($quantity == 0) {
			$this->remove($product);

			return;
		}

		if ($this->has($product)) {
			$coupon = $this->get($product)['coupon'];
		}else{
			$coupon = null;
		}

		$this->storage->set($product->id, [
			'product_id' => (int) $product->id,
			'quantity' => (int) $quantity,
			'coupon' => $coupon,
		]);
	}


	public function remove(Product $product)
	{
		$this->storage->remove($product->id);
	}


	public function has(Product $product)
	{
		return $this->storage->exists($product->id);
	}


	public function get(Product $product)
	{
		return $this->storage->get($product->id);
	}

	public function clear()
	{
		return $this->storage->clear();
	}


	public function all()
	{
		$ids = [];
		$items = [];

		foreach ($this->storage->all() as $product) {
			$ids[] = $product['product_id'];
		}

		$products = $this->product->find($ids);

		foreach ($products as $product) {
			$product->quantity = $this->get($product)['quantity'];
			$product->coupon = $this->get($product)['coupon'];
			$items[] = $product;
		}

		return $items;
	}


	public function itemCount()
	{
		return count($this->storage->all());
	}




	public function subTotal()
	{
		$total = 0;

		foreach ($this->all() as $item) {
			if ($item->outOfStock()) {
				continue;
			}

			$total += $item->getTotal(true);
		}

		return $total;
	}



	public function refresh()
	{
		foreach ($this->all() as $item) {
			if (! $item->hasStock($item->quantity)) {
				$this->update($item, $item->stock);
			}
		}
	}


    public function set($var,$value){

    }

    public function exists($var){

    }
}
