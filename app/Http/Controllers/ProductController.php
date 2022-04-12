<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,make-up,cigarette',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
    ]);
    if ($validator->fails()) {
        return response()->json($validator->messages())->setStatusCode(422);
    }
    $playload = $validator->validated();
    Product::create([
        'product_name' => $playload['product_name'],
        'product_type' => $playload['product_type'],
        'product_price' => $playload['product_price'],
        'expired_at' => $playload['expired_at']
    ]);
    //mengirim response
    return response()->json([
        'msg' => 'Data Produk Berhasil disimpan'
    ],201);
    }

    public function show()
    {
        $products = Product::all();
        return response()->json([
            'msg' => 'Data produk Keseluruhan',
            'data' => $products
        ], 200);
    }

    public function showById($id)
    {
        $products = Product::findOrFail($id);
        $response = [
            'message' => 'Detail Produk',
            'data' => $products
        ];
        return response()->json($response, Response::HTTP_OK);
        }

        public function update(Request $request, $id)
        {
            $products = Product::findOrFail($id);
        $validate = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,make-up,cigarette',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $products->update($request->all());
            $response = [
                'message' => 'Data Produk Berhasi Diubah',
                'data' => $products
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Data Produk gagal disimpan',
                'data' => "Gagal" . $e->errorInfo
            ]);
        }
        }


        public function delete($id)
        {
            {
                $products = Product::findOrFail($id);

                try {
                    $products->delete();
                    $response = [
                        'message' => 'Data Produk Berhasi Dihapus',
                    ];
                    return response()->json($response, Response::HTTP_OK);
                } catch (QueryException $e) {
                    return response()->json([
                        'message' => 'Data Produk gagal dihapus',
                        'data' => "Gagal" . $e->errorInfo
                    ]);
                }
            }
        }
}



