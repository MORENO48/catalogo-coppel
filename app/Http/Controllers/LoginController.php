<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\Login;
 
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
        // $credentials = $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);

        try {
            $datos = Login::login($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function create(Request $request) {
        try {
            $datos = Login::create($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
        
    }
}