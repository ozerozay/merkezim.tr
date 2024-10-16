<?php

namespace App\Support;

use App\Models\Permission;
use App\Models\User;
use App\Peren;
use App\Traits\StrHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class Spotlight
{
    use StrHelper;

    public function search(Request $request)
    {
        return collect()
            ->merge($this->users($request->search))
            ->merge($this->permissions($request->search));
    }

    // Database search
    public function users(string $search = ''): Collection
    {
        return User::query()
            ->select(['id', 'name', 'unique_id', 'branch_id', 'created_at'])
            ->when($search, fn (Builder $q) => $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%'.$this->strUpper($search).'%')
                    ->orWhere('unique_id', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            }))
            ->whereHas('client_branch', function ($q) {
                $q->whereIn('id', auth()->user()->staff_branches);
            })
            ->with('client_branch:id,name')
            ->take(5)
            ->latest()
            ->get()
            ->map(function (User $user) {
                return [
                    'name' => $user->name,
                    'description' => 'Danışan',
                    'link' => route('admin.client.profil.index', ['user' => $user->id]),
                    'avatar' => $user->avatar,
                ];
            });
    }

    public function permissions(string $search = ''): Collection
    {
        return Peren::whereLike(Auth::user()->getAllPermissions()->where('visible', true), 'description', $search)
            ->map(function (Permission $permission) {
                return [
                    'name' => $permission->description,
                    'link' => route($permission->route),
                    'icon' => Blade::render("<x-icon name='o-pencil' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
                ];
            });
    }

    // Static search, but this could be stored on database for easy management
    public function actions(string $search = ''): Collection
    {
        return collect([
            [
                'name' => 'Dashboard',
                'description' => 'Go to dashboard',
                'link' => '/',
                'icon' => Blade::render("<x-icon name='o-chart-pie' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Categories',
                'description' => 'Manage categories',
                'link' => '/categories',
                'icon' => Blade::render("<x-icon name='o-hashtag' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Brands',
                'description' => 'Manage brands',
                'link' => '/brands',
                'icon' => Blade::render("<x-icon name='o-tag' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Products',
                'description' => 'Manage products',
                'link' => '/products',
                'icon' => Blade::render("<x-icon name='o-cube' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Users',
                'description' => 'Manage users & customers',
                'link' => '/users',
                'icon' => Blade::render("<x-icon name='o-user' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Orders',
                'description' => 'Manage orders',
                'link' => '/orders',
                'icon' => Blade::render("<x-icon name='o-gift' class='w-11 h-11 p-2 bg-primary/20 rounded-full' />"),
            ],
            [
                'name' => 'Users',
                'description' => 'Create a new user/customer',
                'link' => '/users/create',
                'icon' => Blade::render("<x-icon name='o-bolt' class='w-11 h-11 p-2 bg-warning/20 rounded-full' />"),
            ],
            [
                'name' => 'Order',
                'description' => 'Create a new order',
                'link' => '/orders/create',
                'icon' => Blade::render("<x-icon name='o-bolt' class='w-11 h-11 p-2 bg-warning/20 rounded-full' />"),
            ],
            [
                'name' => 'Product',
                'description' => 'Create a new product',
                'link' => '/products/create',
                'icon' => Blade::render("<x-icon name='o-bolt' class='w-11 h-11 p-2 bg-warning/20 rounded-full' />"),
            ],
        ])
            ->filter(fn (array $item) => str($item['name'].$item['description'])->contains($search, true))
            ->take(3);
    }
}
