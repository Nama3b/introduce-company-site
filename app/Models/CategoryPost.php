<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


/**
 * App\Models\CategoryPost
 *
 * @property int $id
 * @property string $type
 * @property int $position
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Post> $posts
 * @property-read int|null $posts_count
 * @method static Builder|CategoryPost newModelQuery()
 * @method static Builder|CategoryPost newQuery()
 * @method static Builder|CategoryPost onlyTrashed()
 * @method static Builder|CategoryPost query()
 * @method static Builder|CategoryPost whereCreatedAt($value)
 * @method static Builder|CategoryPost whereDeletedAt($value)
 * @method static Builder|CategoryPost whereId($value)
 * @method static Builder|CategoryPost wherePosition($value)
 * @method static Builder|CategoryPost whereStatus($value)
 * @method static Builder|CategoryPost whereType($value)
 * @method static Builder|CategoryPost whereUpdatedAt($value)
 * @method static Builder|CategoryPost withTrashed()
 * @method static Builder|CategoryPost withoutTrashed()
 * @mixin Eloquent
 */
class CategoryPost extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE = ['ABOUT', 'NEWS', 'PRODUCT'];
    const CREATE = 'CREATE_CATEGORY_POST';
    const VIEW = 'VIEW_CATEGORY_POST';
    const EDIT = 'EDIT_CATEGORY_POST';
    const DELETE = 'DELETE_CATEGORY_POST';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_post';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'type',
        'position',
        'status'
    ];

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_type','id');
    }
}
