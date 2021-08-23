<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Login
    public function login(Request $request){
        
        // validação
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // identificando o usuário ao consultar o email no banco
        $user = User::where('email', $request->email)->first();
        
        // se não existir...
        if(! $user){
            return response()->json(['message' => 'Email incorreto']);
        }

        // se existir, mas a senha for inválida
        if(! Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Senha incorreta']);
        }

        // se tiver tudo certo, é gerado o token
        $token = $user->createToken($request->email . strtotime('now'))->plainTextToken;

        // mensagem retornada ao logar
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);

    }

    // Logout
    public function logout(Request $request){

        // deletando o token
        $request->user()->tokens()->delete();

        // mensagem
        return response()->json(['message' => 'logout'], 201);
    }
}
