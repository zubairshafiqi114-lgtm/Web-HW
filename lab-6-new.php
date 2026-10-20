// ============================================================
// FILE 1: routes/web.php
// ============================================================

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect the home page to login
Route::redirect('/', '/login');

// Routes for guests only
Route::middleware('guest')->group(function () {

    // Registration routes
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    // Login routes
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Routes for authenticated users only
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


// ============================================================
// FILE 2: app/Http/Controllers/AuthController.php
// ============================================================

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please log in.');
    }

    // Log in an existing user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ])
            ->onlyInput('email');
    }

    // Log out the authenticated user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}


// ============================================================
// FILE 3: resources/views/auth/register.blade.php
// ============================================================

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<body>

    <h1>IT Student Portal - Register</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div>
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display success message --}}
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Full Name:</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="email">Email Address:</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Password:</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >
        </div>

        <br>

        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                required
            >
        </div>

        <br>

        <button type="submit">Register</button>
    </form>

    <p>
        Already have an account?
        <a href="{{ route('login') }}">Login here</a>
    </p>

</body>
</html>


// ============================================================
// FILE 4: resources/views/auth/login.blade.php
// ============================================================

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
</head>
<body>

    <h1>IT Student Portal - Login</h1>

    {{-- Display success message --}}
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    {{-- Display validation and authentication errors --}}
    @if ($errors->any())
        <div>
            <strong>Please check the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <div>
            <label for="email">Email Address:</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Password:</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <br>

        <button type="submit">Login</button>
    </form>

    <p>
        Don't have an account?
        <a href="{{ route('register') }}">Register here</a>
    </p>

</body>
</html>


// ============================================================
// FILE 5: resources/views/dashboard.blade.php
// ============================================================

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>

    <h1>IT Student Portal - Dashboard</h1>

    <h2>Welcome, {{ Auth::user()->name }}!</h2>

    <p>You have successfully logged in.</p>

    <h3>Student Information</h3>

    <p>
        <strong>Name:</strong>
        {{ Auth::user()->name }}
    </p>

    <p>
        <strong>Email:</strong>
        {{ Auth::user()->email }}
    </p>

    {{-- Logout form --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button type="submit">Logout</button>
    </form>

</body>
</html>


// ============================================================
// TASK 9: TESTING CHECKLIST
// ============================================================

/*
1. Open the registration page:
   Visit /register
   Expected: Registration form appears.

2. Register a new student:
   Enter name, email, password, and confirm password.
   Expected: User is saved and redirected to login.

3. Test validation:
   Submit an empty form or a password shorter than 8 characters.
   Expected: Validation errors appear.

4. Test duplicate email:
   Register using an email that already exists.
   Expected: An email validation error appears.

5. Test login with correct credentials:
   Enter the registered email and password.
   Expected: User is redirected to the dashboard.

6. Test login with incorrect credentials:
   Enter an incorrect email or password.
   Expected: An error message appears.

7. Test dashboard protection:
   Log out and try to visit /dashboard.
   Expected: Guest is redirected to login.

8. Test logout:
   Click the Logout button.
   Expected: User is logged out and redirected to login.
*/


// ============================================================
// ORAL QUESTIONS AND ANSWERS
// ============================================================

/*
Q1: Why do we use Hash::make()?

Answer:
Hash::make() securely hashes a password before storing it
in the database. We should never store plain-text passwords.


Q2: What is the purpose of auth middleware?

Answer:
Auth middleware protects routes so that only authenticated
users can access them.


Q3: Why do we use @csrf in Blade forms?

Answer:
@csrf adds a CSRF token to the form. It helps protect the
application against Cross-Site Request Forgery attacks.
*/
