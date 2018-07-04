<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Article
 *
 * @mixin \Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $title
 * @property string $decsription
 * @property string $author
 * @property string $slug
 * @method static Builder|Article whereAuthor($value)
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereDecsription($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article whereSlug($value)
 * @method static Builder|Article whereTitle($value)
 * @method static Builder|Article whereUpdatedAt($value)
 */
class Article extends Model
{
    protected $fillable = [
        'title',
        'description',
        'author',
        'slug',
    ];
}
