<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserConnectService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserConnectController extends Controller
{
    public $servie;

    public function __construct(UserConnectService $servie)
    {
    $this->servie = $servie;
    }
    
    public function index()
    {
        try {
            return view('pages.profils.index',[
                'data' => null
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function create()
    {
        try {
            return view('pages.profils.change-pwd');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required', 'string', 'min:6', 'max:255'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            // Vérifier que le mot de passe actuel est correct
            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors([
                    'current_password' => 'Le mot de passe actuel est incorrect.'
                ]);
            }

            $this->servie->changPassword(
                $request->password
            );
            return back()->with([
                'str' => 'info',
                'msg' => 'Votre mot de passe a été modifié avec succès. !'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

}
