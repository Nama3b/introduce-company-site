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
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereDescription($value)
 * @method static Builder|Permission whereDisplayName($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static CachedBuilder|Permission all($columns = [])
 * @method static CachedBuilder|Permission avg($column)
 * @method static CachedBuilder|Permission cache(array $tags = [])
 * @method static CachedBuilder|Permission cachedValue(array $arguments, string $cacheKey)
 * @method static CachedBuilder|Permission count($columns = '*')
 * @method static CachedBuilder|Permission disableCache()
 * @method static CachedBuilder|Permission disableModelCaching()
 * @method static CachedBuilder|Permission flushCache(array $tags = [])
 * @method static CachedBuilder|Permission getModelCacheCooldown(Model $instance)
 * @method static CachedBuilder|Permission inRandomOrder($seed = '')
 * @method static CachedBuilder|Permission insert(array $values)
 * @method static CachedBuilder|Permission isCachable()
 * @method static CachedBuilder|Permission max($column)
 * @method static CachedBuilder|Permission min($column)
 * @method static CachedBuilder|Permission sum($column)
 * @method static CachedBuilder|Permission truncate()
 * @method static CachedBuilder|Permission withCacheCooldownSeconds(?int $seconds = null)
 * @method static CachedBuilder|Permission exists()
 */
class Permission extends Model
{
    use Cachable;

    const CREATE = 'CREATE_PERMISSION';
    const VIEW = 'VIEW_PERMISSION';
    const EDIT = 'EDIT_PERMISSION';
    const DELETE = 'DELETE_PERMISSION';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id',
            'role_id');
    }
}
