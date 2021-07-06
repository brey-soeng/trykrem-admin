<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\SysAdmin
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string|null $nickname
 * @property string|null $avatar
 * @property int $create_user Creator id
 * @property int $status
 * @property string $password
 * @property string|null $api_token
 * @property int $token_expire_time
 * @property string|null $remember_token
 * @property string|null $last_login_time Last Login Time
 * @property string|null $last_login_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereCreateUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereLastLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereTokenExpireTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysAdmin whereUsername($value)
 * @mixin \Eloquent
 *
 */
class SysAdmin extends Authenticatable implements JWTSubject
{
    use Notifiable,HasRoles,LogsActivity;

    /**
     * @var array
     *
     */
    protected static $logName = 'admin';

    protected static $logFillable = true;

    protected static $logUnguarded = true;

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'nickname',
        'phone',
        'avatar',
        'create_user',
        'status',
        'password',
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'token_expire_time',
    ];

    /**
     * @var array
     */
    protected $casts = [

    ];

    /**
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() {
        return ['role' => 'admin'];
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
