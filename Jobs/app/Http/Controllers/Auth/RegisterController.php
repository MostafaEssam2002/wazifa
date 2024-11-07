<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'status' => ['required', 'in:employer,candidate'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $imagePath = null;
        if (isset($data['image'])) {
            $image = $data['image'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Save the image in the public/profile_images directory
            $imagePath = $image->storeAs('public/profile_images', $imageName);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'password' => Hash::make($data['password']),
            'image' => $imagePath ? basename($imagePath) : null, // Store only the filename
        ]);
    }
}
