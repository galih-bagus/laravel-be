<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductMainCategoryRequest;
use App\Models\ProductMainCategory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ProductMainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = null;
        $status = '';
        $req_status = 0;
        $message = '';

        DB::beginTransaction();
        try {
            $data = ProductMainCategory::with('productType')->where('name', 'like', '%' . $request->key . '%')->paginate($request->limit ?? 10);
            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Berhasil';
        } catch (Exception $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'data' => $data,
                'status' => $status,
                'message' => $message
            ], $req_status);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductMainCategoryRequest $request)
    {
        $data = null;
        $status = '';
        $req_status = 0;
        $message = '';

        DB::beginTransaction();
        try {
            $productMainCategory = new ProductMainCategory();
            $productMainCategory->name = $request->name;
            $productMainCategory->product_type_id = $request->product_type_id;
            $productMainCategory->save();
            DB::commit();
            $data = $productMainCategory;
            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Berhasil';
        } catch (Exception $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'data' => $data,
                'status' => $status,
                'message' => $message
            ], $req_status);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductMainCategory $productMainCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductMainCategory $productMainCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductMainCategory $productMainCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductMainCategory $productMainCategory)
    {
        //
    }
}
