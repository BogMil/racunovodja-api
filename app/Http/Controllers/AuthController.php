<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Core\Responses\Fail;
use App\Core\Responses\Error;
use App\Core\Responses\Success;
use Illuminate\Http\Request;
use App\Repositories\KorisnikRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $_korisnikRepo;

    public function __construct(KorisnikRepository $korisnikRepo)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->_korisnikRepo = $korisnikRepo;
    }

    public function register(Request $request)
    {
        $validator = $this->getRegisterDataValidator($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return $this->tryRegister($validator->validated());
    }

    private function getRegisterDataValidator($data)
    {
        return Validator::make($data, [
            'naziv' => 'bail|required',
            'ulica_i_broj' => 'bail|required',
            'grad' => 'bail|required',
            'email' => 'bail|required|unique:korisnici|email',
            'password' => 'bail|required|confirmed',
            'telefon' => 'bail|required',
        ]);
    }

    private function tryRegister($validData)
    {
        try {
            $this->_korisnikRepo->register($validData);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function login(Request $request)
    {
        $validator = $this->getLoginDataValidator($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return $this->tryLogin($validator->validated());
    }

    private function tryLogin($validData)
    {
        try {
            $token = $this->getToken($validData);
            return $this->respondWithToken($token);
        } catch (WrongCredentialsException $e) {
            return $this->failWithError("Pogrešni kredencijali");
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function getToken($credentials)
    {
        $token = Auth::attempt($credentials);
        if (!$token) {
            throw new WrongCredentialsException();
        }

        return $token;
    }

    private function getLoginDataValidator($data)
    {
        $validationRules = [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ];
        return Validator::make($data, $validationRules);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            Auth::logout();
            return response()->json(new Success('Successfully logged out'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json(new Error("Greška!"));
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'jwt' => $token,
        ]);
    }
}

class WrongCredentialsException extends Exception
{
}
