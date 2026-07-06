<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationWelcomeMail;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

    // API-based registration — no trait needed

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if ($authApi->check()) {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'first_name'         => ['required', 'string', 'max:100', 'min:2'],
            'last_name'          => ['required', 'string', 'max:100', 'min:2'],
            'email'              => ['required', 'string', 'email', 'max:255'],
            'phone'              => ['required', 'string', 'min:8'],
            'password'           => ['required', 'string', 'min:6', 'confirmed'],
            'location'           => ['required', 'string', 'max:255'],
            'address'            => ['required', 'string', 'max:255'],
            'gender'             => ['required', 'in:male,female,other'],
            'age'                => ['required', 'integer', 'min:10', 'max:120'],
            'height_cm'          => ['required', 'integer', 'min:50', 'max:300'],
            'weight_kg'          => ['required', 'numeric', 'min:20', 'max:500'],
            'fitness_goal'       => ['required', 'string', 'in:weight_loss,muscle_gain,maintenance,general_health'],
            'dietary_preference' => ['required', 'string', 'in:standard,vegetarian,vegan,keto,paleo,gluten_free'],
            'allergies'          => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please fix the errors in the form.'),
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        $response = $authApi->register([
            'first_name'         => $request->first_name,
            'last_name'          => $request->last_name,
            'email'              => $request->email,
            'phone'              => $request->phone,
            'password'           => $request->password,
            'location'           => $request->location,
            'address'            => $request->address,
            'gender'             => $request->gender,
            'age'                => (int) $request->age,
            'height_cm'          => (int) $request->height_cm,
            'weight_kg'          => (float) $request->weight_kg,
            'fitness_goal'       => $request->fitness_goal,
            'dietary_preference' => $request->dietary_preference,
            'allergies'          => $request->allergies ? array_map('trim', explode(',', $request->allergies)) : [],
        ]);

        if (isset($response['success']) && $response['success'] === false) {
            $message = $response['message'] ?? 'Registration failed.';
            $errors = $response['errors'] ?? [];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => is_array($errors) ? $errors : ['general' => [$message]],
                ], 422);
            }

            return back()->withErrors(is_array($errors) && !empty($errors) ? $errors : ['email' => $message])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            Mail::to($request->email)->send(new RegistrationWelcomeMail(
                fullName: trim($request->first_name . ' ' . $request->last_name),
                email: $request->email,
                verificationUrl: route('verify.email', ['email' => urlencode($request->email)]),
            ));
        } catch (\Exception $e) {
            Log::warning('Failed to send registration welcome email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);
        }

        $redirect = route('verify.email', ['email' => urlencode($request->email)]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Account created! Please check your email for the verification OTP.',
                'redirect' => $redirect,
            ]);
        }

        return redirect($redirect)->with('status', 'Account created! Please check your email for the verification OTP.');
    }
}
