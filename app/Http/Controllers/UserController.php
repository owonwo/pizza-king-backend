<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProducts;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    public function getAuthUser()
    {
        return response()->json(
            auth()->user()->toArray(),
            200
        );
    }

    public function placeOrder()
    {
        $validation = $this->validator([
            'currency' => ['required', Rule::in(['USD', 'EUR'])],
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|min:7',
            'product_ids' => 'required|array',
            'zipcode' => 'required|min:6|max:6',
            'delivery_address' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            return $this->validationError($validation->errors(), 'Required fields are missing');
        }

        $data = $validation->getData();
        $user = auth('sanctum')->user();
        $data['user_id'] = $user ? $user->id : null;
        unset($data['product_ids']);

        $order = Order::create($data);

        // add products to the order
        foreach ($validation->getData()['product_ids'] as $key => $value) {
            [$product_id, $quantity] = is_array($value) ? $value : json_decode($value);

            OrderProducts::create([
                'quantity' => $quantity,
                'order_id' => $order->id,
                'product_id' => $product_id,
            ]);
        }

        return response()->json(['message' => 'Order Placed!']);
    }

    public function orders()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return $this->unauthorized();
        }

        return $user->orders()
                ->with('user:id,name,email')
                ->with('products.product')
                ->latest()
                ->paginate(30);
    }
}
