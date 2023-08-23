<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function getDataUser()
    {
        $user = Auth::user();
        $data = User::where('uuid', $user->uuid)->first();
        return response()->json([
            'code' => 200,
            'message' => 'success get data user',
            'data' => $data
        ]);
    }

    public function register(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ],
            [
                'name.required' => 'Form nama tidak boleh kosong',
                'email.required' => 'Form email tidak boleh kosong',
                'email.unique' => 'Email sudah pernah terdaftar sebelumnya',
                'password.required' => 'Form password tidak boleh kosong',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'password_confirmation.required' => 'Form konfirmasi password tidak boleh kosong',
                'password_confirmation.same' => 'Konfirmasi password harus sama dengan password'
            ]
        );


        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'check your valdiation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $data = new User;
            $data->uuid = Uuid::uuid4()->toString();
            $data->name = $request->input('name');
            $data->email = $request->input('email');
            $data->role = $request->input('role', 2);
            $data->password = Hash::make($request->input('password'));
            $data->save();

            $this->sendVerificationEmail($data);

            $token = $data->createToken('auth_token')->plainTextToken;
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success registrasi',
            'data' => $data,
            'token' => $token
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email sudah terdaftar sebelumnya',
                'code' => 400
            ]);
        }

        $user->email_verified_at = now();

        $user->save();

        return redirect('/login')->with([
            'success' => 'Email verifikasi success',
            'data' => $user,
            'code' => 200
        ]);
    }

    private function sendVerificationEmail(User $user)
    {
        $verificationUrl = url('v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/verify-email/' . $user->email);

        Mail::to($user->email)->send(new VerificationMail($verificationUrl));

        return response()->json([
            'message' => 'Success sending verification email',
            'code' => 200
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid email or password'
            ]);
        }

        $user = User::where('email', $request['email'])->first();

        if (!$user || !$user->email_verified_at) {
            Auth::logout();
            return response()->json([
                'message' => 'Email not verified',
                'code' => 422
            ]);
        }

        $token = $user->createToken('auth_token');

        if (!$this->isTokenValid($token)) {
            Auth::logout();
            return response()->json([
                'code' => 400,
                'message' => 'Token expired'
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success login',
            'data' => $user,
            'access_token' => $token->plainTextToken
        ]);
    }

    private function isTokenValid($token)
    {
        $expirationMinutes = config('sanctum.expiration');

        if ($expirationMinutes === null) {
            return true; 
        }
     
        return $token->accessToken->created_at->addMinutes($expirationMinutes)->isFuture();
    }

    public function changePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'password_old' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
    
        if ($validation->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }
    
        try {
            $user = User::find(Auth::id());
    
            if (!Hash::check($request->password_old, $user->password)) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Password lama salah'
                ]);
            }
    
            $user->password = Hash::make($request->input('password'));
            $user->save();
    
            $request->user('web')->tokens()->delete();
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return response()->json([
                'code' => 200,
                'message' => 'success update password and logout',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }
    }
    

    public function logout(Request $request)
    {
        $request->user('web')->tokens()->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'code' => 200,
            'message' => 'sucess logout and delete token access'
        ]);
    }
}
