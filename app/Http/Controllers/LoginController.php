<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\Login;
use Illuminate\Support\Facades\Validator;
 
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('home');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:80',
            'password' => 'required|max:20'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(["estatus" => -1, "msj" => $errors],400);
        }

        try {
            $datos = Login::login($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function create(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:50',
                'email' => 'required|email|max:80',
                'password' => 'required|max:20'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(["estatus" => -1, "msj" => $errors],400);
            }

            $datos = Login::create($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }
}