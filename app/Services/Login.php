<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Exception;

class Login {
    static function login($datos) {
        $credentials = [
            'email' => $datos->email,
            'password' => $datos->password
        ];

        if (Auth::attempt($credentials)) {
            $user = User::where('email',$datos->email)
                ->first();

            $token = $user->createToken('auth_token')->plainTextToken;

            return ['token' => $token];
        }

        return ['estatus' => 1, 'msj' => 'Error al atutenticar'];
    }

    static function create($datos) {
        $user = User::where('email',$datos->email)
            ->first();

        if ($user) {
            if ($user->status == 0) {
                $user->password = Hash::make($datos->password);
                $user->save();
            }
        } else {
            $user = new User;
            $user->name = $datos->nombre;
            $user->email = $datos->email;
        }

        $user->password = Hash::make($datos->password);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ];
    }
}
