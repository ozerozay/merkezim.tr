<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $route
 * @property string $description
 * @property bool $visible
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutRole($roles, $guard = null)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @mixin \Eloquent
 */
class Permission extends SpatiePermission
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
        ];
    }
}
