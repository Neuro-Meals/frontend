<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use App\Services\Api\LocationApiService;
use Illuminate\Http\Request;
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

    public function locations(Request $request, LocationApiService $locationApi)
    {
        $type = $request->input('type', 'regions');
        $regionCode = $request->input('region_code');

        if ($type === 'regions') {
            $data = $locationApi->regions();
        } elseif ($type === 'cities' && $regionCode) {
            $data = $locationApi->regionCities($regionCode);
        } else {
            $data = $locationApi->list();
        }

        return response()->json([
            'success' => !empty($data) && !isset($data['error']),
            'data' => $data ?? [],
        ]);
    }

    public function register(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100', 'min:2'],
            'last_name'  => ['required', 'string', 'max:100', 'min:2'],
            'email'      => ['required', 'string', 'email', 'max:255'],
            'phone'      => ['required', 'string', 'min:8'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'location'   => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],
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
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => $request->password,
            'location'   => $request->location,
            'address'    => $request->address,
        ]);

        // Store location in session for post-verification redirect check
        session(['registered_location' => $request->location]);

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

        $redirect = route('verify.email', ['email' => urlencode($request->email)]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'requires_verification' => true,
                'message' => __('Account created! Please verify your email to continue.'),
                'redirect' => $redirect,
            ]);
        }

        return redirect($redirect)->with('status', __('Account created! Please verify your email to continue.'));
    }
}
