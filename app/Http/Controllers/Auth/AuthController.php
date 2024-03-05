<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('json-validation', ['except' => ['logout', 'refresh']]);
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    // REGISTER
    public function register(Request $request) {
        // REQUEST VALIDATION
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // 'password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $validator = Validator::make($request->json()->all(), $rules);

        if($validator->fails()){
            $res = [
                'status' => 'fail',
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ];

            return response()->json($res,Response::HTTP_BAD_REQUEST);
        }

        $validated = $validator->validated();

        try {

            // STORE USER DATA TO DATABASE
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => Role::IS_CUSTOMER,
            ]);

            // RESULT AFTER LOGIN SUCCESS
            $res = [
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'data' => [
                    'user' => $user
                ],
            ];
            return response()->json($res, Response::HTTP_CREATED);
        } catch (\PDOException $e) {
            $res = [
                'status' => 'error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ];

            return response()->json($res,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        $rules = [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $validator = Validator::make($request->json()->all(), $rules);

        if($validator->fails()){
            $res = [
                'status' => 'fail',
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ];

            return response()->json($res,Response::HTTP_BAD_REQUEST);
        }

        $validated = $validator->validated();

        // LOGIN
        $cred = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        $token = auth()->attempt($cred);

        if(!$token) {
            $res = [
                'status' => 'fail',
                'code' => Response::HTTP_UNAUTHORIZED,
                'data' => [
                    'errors' => 'Unauthorized',
                ],
            ];

            return response()->json($res,Response::HTTP_UNAUTHORIZED);
        }

        // RESULT AFTER LOGIN SUCCESS
        $res = [
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => [
                'credentials' => $this->respondWithToken($token),
            ],
        ];

        return response()->json($res, Response::HTTP_OK);
    }

    public function logout(){
        auth()->logout();

        $res = [
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => []
        ];

        return response()->json($res, Response::HTTP_OK);
    }

    // REFRESH TOKEN
    public function refresh(Request $request){
        $token = $request->bearerToken();
        
        $res = [
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => [
                'credentials' => $this->respondWithToken(auth()->refresh()),
            ],
        ];

        return response()->json($res, Response::HTTP_OK);
    }

    protected function respondWithToken($token){
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
