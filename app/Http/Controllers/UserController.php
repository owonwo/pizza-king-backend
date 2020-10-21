<?php

namespace App\Http\Controllers;

use App\OrderHistory;
use App\Product;

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

        $user = auth('sanctum')->user();
        $data = $validation->getData();

        $order = OrderHistory::create(array_merge(
            $data,
            [
                'user_id' => $user ? $user->id : null,
                'product_ids' => json_encode($data['product_ids']),
            ]
        ));

        return response()->json(['message' => 'Order Placed!']);
    }

    public function orders()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return $this->unauthorized();
        }

        $query = $user->orders()
                ->with('user:id,name,email')
                ->paginate(30);

        return $query->setCollection(
            $query->getCollection()->transform(function ($model) {
                $model->products = Product::find($model->product_ids, ['id', 'name', 'price', 'image']);
                unset($model->product_ids);

                return $model;
            })
           );
    }
}
