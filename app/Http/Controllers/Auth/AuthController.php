<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * Login register
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|string|max:120',
        'email' => 'required|email',
        'password' => 'required|min:8|max:12',
    ];

    /**
     * Método que realiza o registro do usuário
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $userData = $request->only(['name', 'email', 'password']);

        $validator = $this->validaInformacoes($request);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'verifique os dados informados',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->isUserCreated($userData)) {
            return response()->json([
                'message' => 'usuario ja cadastrado.',
            ], Response::HTTP_CONFLICT);
        }

        $user = User::create($userData);

        if (! $user) {
            return response()->json([
                'message' => 'Usuário não criado, verifique os dados informados.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Auth::login($user);

        return response()->json([
            'message'   =>  'Sucesso ao criar usuário.',
            'user'      =>  UserResource::make($user),
        ], Response::HTTP_CREATED);
    }

    /**
     * Método que realiza o login do usuário
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();


        return response()->json([
            'message'   => 'Login efetuado com sucesso.',
            'user'      => UserResource::make($user),
        ], Response::HTTP_OK);
    }

    /**
     * Método que realiza o logout do usuário
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout efetuado com sucesso.',
        ], Response::HTTP_OK);
    }

    /**
     * Valida informações do usuário
     *
     * @param \Illuminate\Http\Request $request
     * @param array $userData
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validaInformacoes($request)
    {
        $validator = Validator::make([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ], $this->rules);

        return $validator;
    }

    /**
     * Verifica se usuario já existe na model de User
     *
     * @param array $userData
     * @return bool
     */
    public function isUserCreated($userData)
    {
        $user = User::where('email', $userData['email'])->first();

        if ($user) {
            return true;
        }

        return false;
    }
}
