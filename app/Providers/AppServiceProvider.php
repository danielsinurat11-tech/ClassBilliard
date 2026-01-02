<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Permission;
use App\Models\orders;
use App\Models\payments;
use App\Models\Menu;
use App\Models\User;
use App\Models\CategoryMenu;
use App\Models\meja_billiard;
use App\Policies\OrderPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\MenuPolicy;
use App\Policies\UserPolicy;
use App\Policies\CategoryMenuPolicy;
use App\Policies\TablePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        orders::class => OrderPolicy::class,
        payments::class => PaymentPolicy::class,
        Menu::class => MenuPolicy::class,
        User::class => UserPolicy::class,
        CategoryMenu::class => CategoryMenuPolicy::class,
        meja_billiard::class => TablePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Safe Blade directive to avoid throwing PermissionDoesNotExist
        // Use in blade as: @hasPermissionSafe('permission.name') ... @endhasPermissionSafe
        Blade::if('hasPermissionSafe', function ($permission) {
            try {
                // If the permission itself does not exist, return false instead of throwing
                if (! Permission::where('name', $permission)->exists()) {
                    return false;
                }

                $user = auth()->user();
                if (! $user) {
                    return false;
                }

                return $user->hasPermissionTo($permission);
            } catch (\Exception $e) {
                // On any unexpected error, fail safe (do not render protected block)
                return false;
            }
        });
    }
}
