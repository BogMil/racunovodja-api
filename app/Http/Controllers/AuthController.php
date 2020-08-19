<?php

namespace App\Http\Controllers;

use App\Constants\DefaultValues;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User\User;
use App\Constants\ErrorCodes;
use App\Core\Responses\Fail;
use App\Core\Responses\Error;
use App\Core\Responses\Success;
use App\DetaljiKorisnika;
use App\Korisnik;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\DB;
use App\UserDetails\UserDetails;
use App\Lokacija\Lokacija;
use App\LokacijaSkole;
use App\Repositories\KorisnikRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use stdClass;

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
        $validationRules = [
            'naziv' => 'bail|required',
            'ulica_i_broj' => 'bail|required',
            'grad' => 'bail|required',
            'email' => 'bail|required|unique:korisnici|email',
            'password' => 'bail|required|confirmed',
            'telefon' => 'bail|required',
        ];
        return Validator::make($data, $validationRules);
    }

    private function tryRegister($data)
    {
        try {
            $this->_korisnikRepo->register($data);
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

        try {
            $token = $this->getToken($validator->validated());
            return $this->respondWithToken($token);
        } catch (WrongCredentialsException $e) {
            return $this->failWithError("Pogrešni kredencijali");
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json(new Error("Greška!"));
        }
    }

    public function getToken($credentials)
    {
        $token = Auth::attempt($credentials);

        if (!$token) {
            throw new WrongCredentialsException();
        }
    }

    private function respondWithError($errorMessage)
    {
        return response()->json(Fail::withMessage($errorMessage));
    }

    private function getLoginDataValidator($data)
    {
        $validationRules = [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ];
        return Validator::make($data, $validationRules);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
            auth()->logout();
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
        $data = new stdClass();
        $data->jwt = $token;
        $data->user = auth()->user();

        return response()->json(new Success($data));
    }
}

class WrongCredentialsException extends Exception
{
}
