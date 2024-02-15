<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = null;
        $status = '';
        $req_status = 0;
        $message = '';

        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            DB::commit();
            $data = $user;
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
    public function login(LoginRequest $request)
    {
        $data = null;
        $status = '';
        $req_status = 0;
        $message = '';

        try {
            $data = User::where('email', $request->email)->first();
            if ($data) {
                if (Hash::check($request->password, $data->password)) {
                    $req_status = HttpFoundationResponse::HTTP_OK;
                    $status = 'success';
                    $message = 'Berhasil';
                    $data->token = $data->createToken('token')->plainTextToken;
                }
            } else {
                $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
                $status = 'failed';
                $message = 'Akun tidak ditemukan';
            }
        } catch (Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
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

    public function logout(Request $request)
    {
        $status = '';
        $req_status = 0;
        $message = '';

        try {
            $request->user()->currentAccessToken()->delete();
            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Berhasil Logout.';
        } catch (Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message
            ], $req_status);
        }
    }
}
