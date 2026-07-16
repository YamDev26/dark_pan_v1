<?php
  namespace App\Services;

  use App\Models\User;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;

  class UserConnectService
  {
    
    public function changPassword($password) {
      Auth::user()->update([
        'password' => Hash::make($password),
      ]);
    }

    
  }