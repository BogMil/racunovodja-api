<?php

namespace App\Http\Controllers;

use App\Constants\DefaultValues;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Facades\Validator;
use stdClass;

class AuthController extends Controller
{
    private $validationRules = [
        'naziv' => 'bail|required',
        'ulica_i_broj' => 'bail|required',
        'grad' => 'bail|required',
        'email' => 'bail|required|unique:korisnici|email',
        'password' => 'bail|required|confirmed',
        'telefon' => 'bail|required',
    ];

    private $_korisnikRepo;

    public function __construct(KorisnikRepository $korisnikRepo)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->_korisnikRepo = $korisnikRepo;
    }

    public function register(Request $request)
    {
        $validator = $this->getValidatorForRequestedData($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return DB::transaction(function () use ($validator) {
            return $this->try(function () use ($validator) {
                $this->_korisnikRepo->register($validator->validated());
                return $this->successfullResponse();
            });
        });
    }

    private function getValidatorForRequestedData($data)
    {
        return Validator::make($data, $this->validationRules);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        try {
            if (!($token = auth()->attempt($credentials))) {
                return response()->json(
                    Fail::withMessage("Pogrešni kredencijali")
                );
            }
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json(new Error("Greška!"));
        }
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
        // return response()->json(new Success([
        //     'access_token' => $token,
        //     // 'token_type' => 'bearer',
        //     // 'expires_in' => auth()->factory()->getTTL() * 60
        // ]));

        $data = new stdClass();
        $data->jwt = $token;
        $data->user = auth()->user();

        return response()->json(new Success($data));
    }
}

// class AuthenticatedUser
// {
//     public $name;
// }
