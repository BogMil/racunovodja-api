<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\KorisnikRepository;
use App\Services\KorisnikService;
use App\Validators\AuthValidator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    private $_korisnikService;
    private $_authValidator;

    public function __construct(
        KorisnikService $korisnikService,
        AuthValidator $authValidator
    ) {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->_korisnikService = $korisnikService;
        $this->_authValidator = $authValidator;
    }

    public function register(Request $request)
    {
        $validator = $this->_authValidator->zaRegister($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return $this->tryRegister($validator->validated());
    }

    private function tryRegister($validData)
    {
        try {
            $this->_korisnikService->register($validData);
            return $this->successfullResponse([
                "probni_period" => "2 meseca",
            ]);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function login(Request $request)
    {
        $validator = $this->_authValidator->zaLogin($request->all());
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
            return $this->failWithErrors(["greska" => "Pogrešni kredencijali"]);
        } catch (TokenExpiredException $e) {
            return $this->failWithErrors(["greska" => "Licenca je istekla"]);
        } catch (\Exception $e) {
            dd($e);
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

    public function me()
    {
        $user = $this->_korisnikService->trenutnoLogovani();

        return $this->successfullResponse(["korisnik" => $user]);
    }

    public function logout()
    {
        try {
            Auth::logout();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

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
