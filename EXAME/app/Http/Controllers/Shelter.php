<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adoption;
use App\Pet;
use App\Petcategorie;
use App\Petlover;
use App\Shelter_model;
use \DB;
use Redirect;

class Shelter extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function pets($id = FALSE)
    {
        if($id)
        {
            $Pets = Pet::where('cat_id', $id)
            ->where('status', '0')
            ->get();
            $Categories = Petcategorie::all();
        }
        else
        {
            $Pets = Pet::where('status', '0')
            ->get();
            $Categories = Petcategorie::all();
        }

        $name = session('name');
        $session_id = session('id');
        if($session_id)
        {
             $placeholders = [
                'MENU_22' => '',
                'MENU_2' => 'Welcome '.$name,
                'MENU_33' => url('/logout'),
                'MENU_3' => 'Logout',
                'Pets' => $Pets,
                'Categories' => $Categories,
                'user_id' => $session_id,
                'id' => $id
             ];
        }
        else
        {
             $placeholders = [
                'MENU_22' => url('/register'),
                'MENU_2' => 'Register',
                'MENU_33' => url('/login'),
                'MENU_3' => 'Login',
                'Pets' => $Pets,
                'Categories' => $Categories,
                'user_id' => '',
                'id' => $id
             ];
        }
        return view('pets', $placeholders);
    }

    public function register()
    {
        return view('register');
    }

    public function registerAction()
    {
        $name = request('name');
        $email = request('email');
        $password = request('password');
        
        $password_final = substr(md5($password),0,32);

        $this->validate(request(), ['password' => 'same:confirm_password', 'email' => 'unique:petlovers']);

        $register_user = new Petlover;

        $register_user->name = $name;
        $register_user->email = $email;
        $register_user->password_digest = $password_final;
        $register_user->save();

        $placeholders = [
            'MESSAGE' => "New user $name registered successfully!"
        ];

        return view('message', $placeholders);
    }

    public function login()
    {
        $placeholders = [
            'MESSAGE' => '',
            'COR' => ''
        ];
        return view('login', $placeholders);
    }

    public function loginAction()
    {
        $email = request('email');
        $password = request('password');
        $password_final = substr(md5($password),0,32);
        $validate_user = Petlover::where('email', $email)
                                ->where('password_digest', $password_final)
                                ->first();
        if($validate_user)
        {
            session(['id' => $validate_user->id, 'name' => $validate_user->name, 'email' => $validate_user->email]);
            $name = session('name');
            $placeholders = [
                'MESSAGE' => "Welcome back $name!"
            ];
            return view('message', $placeholders);
        }
        else
        {
            $placeholders = [
                'MESSAGE' => 'Login failed',
                'COR' => 'alert alert-danger text-center'
            ];
            return view('login', $placeholders);
        }
    }

    public function logout()
    {
        $name = session('name');
        request()->session()->flush();
        $placeholders = [
            'MESSAGE' => "See you back soon $name!"
        ];
        return view('message', $placeholders);
    }

    public function adopt($id)
    {
        $user_id = session('id');
        if($user_id)
        {
            $register_adopt = new Adoption;
            $register_adopt->petlover_id = $user_id;
            $register_adopt->pet_id = $id;
            $register_adopt->save();
    
            $pet_update = Pet::find($id);
            $pet_update->status = '1';
            $pet_update->save();

            $name = session('name');
            $placeholders = [
                'MESSAGE' => "$name Thank you for your adopt!"
            ];
    
            return view('message', $placeholders);
        }
        else
        {
            return Redirect::to('/pets');
        }
    }

    public function myPets()
    {
        $user_id = session('id');
        $name = session('name');
        if($user_id)
        {
            $Categories = Petcategorie::all();
            $myPets = Shelter_model::my_pets($user_id);
            $placeholders = [
                'MENU_22' => '',
                'MENU_2' => 'Welcome '.$name,
                'MENU_33' => url('/logout'),
                'MENU_3' => 'Logout',
                'Categories' => $Categories,
                'user_id' => $user_id,
                'myPets' => $myPets
             ];
        //  $myPets = DB::table('adoptions')
        // ->join('pets', 'adoptions.pet_id', '=', 'pets.id')
        // ->where('pets.petlover_id', $user_id)
        // ->get();
            return view('mypets', $placeholders);
        }
        else
        {
            return Redirect::to('/pets');
        }
    }
}
