<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\WithSeo;
use App\Models\User;
use App\Services\DemoSeederService;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class DemoController extends Controller
{
    use WithSeo;

    protected DemoSeederService $seeder;

    public function __construct(DemoSeederService $seeder)
    {
        $this->seeder = $seeder;
    }

    /**
     * Show demo landing page.
     */
    public function index()
    {
        $this->seo(
            SeoData::make('Try Demo')
                ->description('Try Laravilt admin panel demo instantly. No registration required. Experience 30+ form fields, advanced tables, AI integration, and more.')
                ->keywords('laravilt demo, laravel admin demo, try admin panel, vue admin preview')
                ->image('/screenshots/16-dashboard-light-mode.png')
                ->url('/demo')
        );

        return Inertia::render('Demo/Index');
    }

    /**
     * Register a demo user with email.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_demo' => true,
            'demo_expires_at' => now()->addHours(24), // 24-hour demo
            'email_verified_at' => now(), // Auto-verify for demo
        ]);

        // Seed demo data
        $this->seeder->seedForUser($user);

        Auth::login($user);

        return redirect('/admin');
    }

    /**
     * Start instant demo without registration.
     */
    public function instant(Request $request)
    {
        $randomId = Str::random(8);

        $user = User::create([
            'name' => 'Demo User',
            'email' => "demo-{$randomId}@laravilt.demo",
            'password' => Hash::make(Str::random(32)),
            'is_demo' => true,
            'demo_expires_at' => now()->addHours(2), // 2-hour instant demo
            'email_verified_at' => now(),
        ]);

        // Seed demo data
        $this->seeder->seedForUser($user);

        Auth::login($user);

        return redirect('/admin');
    }

    /**
     * Reset demo data.
     */
    public function reset(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->isDemo()) {
            return back()->with('error', 'This action is only available for demo users.');
        }

        $this->seeder->resetForUser($user);

        // Extend demo time by 1 hour
        $user->update([
            'demo_expires_at' => now()->addHour(),
        ]);

        return back()->with('success', 'Demo data has been reset successfully.');
    }

    /**
     * End demo session and cleanup.
     */
    public function end(Request $request)
    {
        $user = $request->user();

        if ($user && $user->isDemo()) {
            // Clear demo data
            $this->seeder->clearForUser($user);

            // Delete demo user
            $user->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/demo')->with('success', 'Thank you for trying Laravilt!');
    }
}
