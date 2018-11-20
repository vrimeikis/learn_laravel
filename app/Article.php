<?php

declare(strict_types = 1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * App\Article
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $title
 * @property string|null $cover
 * @property string $description
 * @property string $slug
 * @property int|null $author_id
 * @property-read Author|null $author
 * @property int|null $reference_article_id
 * @property string $article_type
 * @property-read Collection|Category[] $categories
 * @method static Builder|Article whereArticleType($value)
 * @method static Builder|Article whereAuthorId($value)
 * @method static Builder|Article whereCover($value)
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereDescription($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article whereReferenceArticleId($value)
 * @method static Builder|Article whereSlug($value)
 * @method static Builder|Article whereTitle($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'cover',
        'description',
        'slug',
        'author_id',
        'reference_article_id',
        'article_type',
    ];


    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
