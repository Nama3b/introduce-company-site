<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Post
 *
 * @property-read CategoryPost|null $category
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post onlyTrashed()
 * @method static Builder|Post query()
 * @method static Builder|Post withTrashed()
 * @method static Builder|Post withoutTrashed()
 * @property int $id
 * @property int $post_type
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $url
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereDeletedAt($value)
 * @method static Builder|Post whereDescription($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereImage($value)
 * @method static Builder|Post wherePostType($value)
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUrl($value)
 * @mixin Eloquent
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    const CREATE = 'CREATE_CATEGORY_POST';
    const VIEW = 'VIEW_CATEGORY_POST';
    const EDIT = 'EDIT_CATEGORY_POST';
    const DELETE = 'DELETE_CATEGORY_POST';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var string[]
     */
    protected $fillable = [
        'post_type',
        'title',
        'description',
        'image',
        'url',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryPost::class,'post_type','id');
    }
}
