<?php

namespace App\Models;

use Eloquent;
use GeneaLabs\LaravelModelCaching\CachedBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereCode($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDeletedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static CachedBuilder|Role all($columns = [])
 * @method static CachedBuilder|Role avg($column)
 * @method static CachedBuilder|Role cache(array $tags = [])
 * @method static CachedBuilder|Role cachedValue(array $arguments, string $cacheKey)
 * @method static CachedBuilder|Role count($columns = '*')
 * @method static CachedBuilder|Role disableCache()
 * @method static CachedBuilder|Role disableModelCaching()
 * @method static CachedBuilder|Role flushCache(array $tags = [])
 * @method static CachedBuilder|Role getModelCacheCooldown(Model $instance)
 * @method static CachedBuilder|Role inRandomOrder($seed = '')
 * @method static CachedBuilder|Role insert(array $values)
 * @method static CachedBuilder|Role isCachable()
 * @method static CachedBuilder|Role max($column)
 * @method static CachedBuilder|Role min($column)
 * @method static CachedBuilder|Role sum($column)
 * @method static CachedBuilder|Role truncate()
 * @method static CachedBuilder|Role withCacheCooldownSeconds(?int $seconds = null)
 * @method static CachedBuilder|Role exists()
 */
class Role extends Model
{
    use Cachable;

    const CREATE = 'CREATE_ROLE';
    const VIEW = 'VIEW_ROLE';
    const EDIT = 'EDIT_ROLE';
    const DELETE = 'DELETE_ROLE';
    const ROLE_SUPER_ADMIN_ID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
    ];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';


    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id',
            'permission_id');
    }
}
