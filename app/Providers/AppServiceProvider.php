<?php

namespace App\Providers;

use App\Contracts\TrustmedisServiceInterface;
use App\Models\Clinic;
use App\Services\MockTrustmedisService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TrustmedisServiceInterface::class, MockTrustmedisService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share clinic data to all Blade views for White-Label CMS dynamic theming
        View::composer('*', function ($view) {
            static $cachedClinic = null;

            if ($cachedClinic === null) {
                try {
                    if (Schema::hasTable('clinics')) {
                        $cachedClinic = Clinic::first() ?? new Clinic([
                            'name' => 'Onew Clinic',
                            'clinic_code' => 'ONEW',
                            'primary_color' => '#0284C7',
                            'secondary_color' => '#0EA5E9',
                            'phone' => '0812-9988-7766',
                            'address' => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan',
                        ]);
                    }
                } catch (\Throwable $e) {
                    $cachedClinic = new Clinic([
                        'name' => 'Onew Clinic',
                        'clinic_code' => 'ONEW',
                        'primary_color' => '#0284C7',
                        'secondary_color' => '#0EA5E9',
                    ]);
                }
            }

            $view->with('clinic', $cachedClinic);
        });
    }
}
