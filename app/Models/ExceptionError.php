<?php

namespace App\Models;

use Carbon\Carbon;
use GuzzleHttp\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * App\Models\ExceptionError
 *
 * @property string $id
 * @property string|null $message
 * @property string $code
 * @property string $file
 * @property int $line
 * @property string $trace
 * @property string $trace_as_string
 * @property bool $is_solve Is it resolved 0 not resolved 1 resolved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereIsSolve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereTraceAsString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExceptionError whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExceptionError extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'message', 'code', 'file', 'line', 'trace', 'trace_as_string', 'is_solve'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'line' => 'integer',
        'is_solve' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        ExceptionError::creating(function ($model) {
            $model->setId();
        });
    }

    public function setId()
    {
        $this->attributes['id'] = Str::orderedUuid();
    }

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function getTraceAttribute($value)
    {
        return Utils::jsonDecode($value, true);
    }

    public function setTraceAttribute($value): void
    {
        $this->attributes['trace'] = Utils::jsonEncode($value);
    }

    public function setTraceAsStringAttribute($value): void
    {
        $this->attributes['trace_as_string'] =
            '[' . Carbon::now()->format('Y-m-d H:i:s') . '] ' . App::environment() . '.ERROR: '
            . $this->attributes['message']
            . ' at ' . $this->attributes['file'] . ':' . $this->attributes['line']
            . "\n"
            . $value;
    }

    /**
     * @param array $validated
     * @return array
     */
    public static function getList(array $validated): array
    {
        $where = [];
        if (isset($validated['is_solve'])) {
            $where[] = ['is_solve', '=', $validated['is_solve']];
        }

        $model = ExceptionError::query()->where($where)
            ->when($validated['id'] ?? null, function ($query) use ($validated) {
                return $query->where('id', 'like', '%' . $validated['id'] . '%');
            })
            ->when($validated['message'] ?? null, function ($query) use ($validated) {
                return $query->where('message', 'like', '%' . $validated['message'] . '%');
            })
            ->when($validated['start_at'] ?? null, function ($query) use ($validated) {
                return $query->whereBetween('created_at', [$validated['start_at'], $validated['end_at']]);
            });


        $total = $model->count('id');

        $logs = $model->select(
            [
                'id',
                'message',
                'code',
                'file',
                'line',
                'trace',
                'trace_as_string',
                'is_solve',
                'created_at',
                'updated_at'
            ]
        )
            ->orderBy($validated['sort'] ?? 'updated_at', $validated['order'] === 'ascending' ? 'asc' : 'desc')
            ->offset(($validated['offset'] - 1) * $validated['limit'])
            ->limit($validated['limit'])
            ->get();

        return [
            'logs' => $logs,
            'total' => $total
        ];
    }

    public function solve(): void
    {
        $this->is_solve = 1;
        $this->save();
        activity()
            ->useLog('exception')
            ->performedOn($this)
            ->causedBy(Auth::guard('admin')->user())
            ->log('The :subject.id exception amended by :causer.name');
    }
}
