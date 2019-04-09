<?php

namespace App\Http\Controllers\Auth;

use App\Mail\RegistrationEmail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

/**
 * Class RegisterController
 *
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

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
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'validation_hash' => Str::random(32),
        ]);
    }

    /**
     * check if email is already in system
     *
     * @param Request $request
     */
    public function checkUserExistance(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users', 'max:255'],
        ]);
    }

    /**
     * return path, after registration
     *
     * @return string
     */
    public function redirectPath()
    {
        return route('thankyou');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response| array
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->SendRegisterEmail($user);

        session()->flash('emailAddress', $user->email);
        if($request->ajax() || $request->wantsJson()) {
            return ['redirect' => $this->redirectPath()];
        }
        return redirect($this->redirectPath());
    }

    /**
     * Send email with validation url
     *
     * @param User $user
     *
     * @return mixed
     */
    public function SendRegisterEmail(User $user)
    {

        return Mail::to($user->email)->send(new RegistrationEmail($user));
    }

    /**
     * Thank you view, after register
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showThankYou()
    {
        return view('auth.register_success');
    }

    public function verifyEmail($token) {
        $result = User::where('validation_hash', $token)->update(['is_validated' => '1', 'validation_hash' => '']);

        return view('auth.verifiedEmail', ['result' => $result]);
    }
}
