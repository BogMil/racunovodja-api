<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User\User;
use App\Constants\ErrorCodes;
use App\Core\Responses\Fail;
use App\Core\Responses\Error;
use App\Core\Responses\Success;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\DB;
use App\UserDetails\UserDetails;
use App\Lokacija\Lokacija;
use stdClass;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $modifier = '+6 month';
                $trialPeriod = '6 meseci';
                $user = new User($request->all());
                $user->password = Hash::make($request['password']);
                $user->valid_until = $this->getPromoPeriodEndDate($modifier);
                $user->save();

                $userDetails=new UserDetails();
                $userDetails->poreski_identifikacioni_broj='';
                $userDetails->maticni_broj='';
                $userDetails->user_id=$user->id;
                $userDetails->opstina_id=null;
                $userDetails->telefon='';
                $userDetails->ulica_i_broj='';
                $userDetails->email='';
                $userDetails->naziv_skole='';
                $userDetails->save();

                $lokacija=new Lokacija();
                $lokacija->user_id = $user->id;
                $lokacija->naziv = 'Škola';
                $lokacija->save();

                return response()->json(new Success($trialPeriod));
            } catch (\Exception $e) {
                if ($e->getCode() == ErrorCodes::UNIQUE_INDEX) {
                    Log::info('EMAIL ADRESA ZAUZETA : ' . $e->getMessage());
                    return response()->json(Fail::withMessage('Email adresa je zauzeta!'));
                }

                Log::critical($e->getMessage());
                return response()->json(new Error('Greška prilikom snimanja podataka u bazu'));
            }
        });
    }

    private function getPromoPeriodEndDate($modifier)
    {
        $date = new \DateTime('now');
        $date->modify($modifier);
        $date = $date->format('Y-m-d h:i:s');
        return $date;
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
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(Fail::withMessage("Pogrešni kredencijali"));
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
