<?php

namespace App\Models;

use App\Utils\Arr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property int $pid
 * @property string $guard_name
 * @property string|null $title
 * @property string|null $icon
 * @property int $sort
 * @property int|null $type 0模块,1页面,2操作
 * @property string|null $component
 * @property string|null $path
 * @property string|null $route_name
 * @property bool $hidden
 * @property string|null $redirect
 * @property bool $cache
 * @property bool $breadcrumb
 * @property bool $affix
 * @property string|null $active_menu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SysAdmin[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereActiveMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereBreadcrumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    use LogsActivity;

    protected static $logName = 'permission';

    protected static $logUnguarded = true;

}
