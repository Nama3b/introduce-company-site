<?php

namespace App\Models;

use Eloquent;
use GeneaLabs\LaravelModelCaching\CachedBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\PermissionRole
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PermissionRole newModelQuery()
 * @method static Builder|PermissionRole newQuery()
 * @method static Builder|PermissionRole query()
 * @method static Builder|PermissionRole whereCreatedAt($value)
 * @method static Builder|PermissionRole whereId($value)
 * @method static Builder|PermissionRole wherePermissionId($value)
 * @method static Builder|PermissionRole whereRoleId($value)
 * @method static Builder|PermissionRole whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static CachedBuilder|PermissionRole all($columns = [])
 * @method static CachedBuilder|PermissionRole avg($column)
 * @method static CachedBuilder|PermissionRole cache(array $tags = [])
 * @method static CachedBuilder|PermissionRole cachedValue(array $arguments, string $cacheKey)
 * @method static CachedBuilder|PermissionRole count($columns = '*')
 * @method static CachedBuilder|PermissionRole disableCache()
 * @method static CachedBuilder|PermissionRole disableModelCaching()
 * @method static CachedBuilder|PermissionRole flushCache(array $tags = [])
 * @method static CachedBuilder|PermissionRole getModelCacheCooldown(Model $instance)
 * @method static CachedBuilder|PermissionRole inRandomOrder($seed = '')
 * @method static CachedBuilder|PermissionRole insert(array $values)
 * @method static CachedBuilder|PermissionRole isCachable()
 * @method static CachedBuilder|PermissionRole max($column)
 * @method static CachedBuilder|PermissionRole min($column)
 * @method static CachedBuilder|PermissionRole sum($column)
 * @method static CachedBuilder|PermissionRole truncate()
 * @method static CachedBuilder|PermissionRole withCacheCooldownSeconds(?int $seconds = null)
 * @method static CachedBuilder|PermissionRole exists()
 */
class PermissionRole extends Model
{

    use Cachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_role';



}
