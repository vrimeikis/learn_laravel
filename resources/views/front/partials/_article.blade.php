<div class="col-md-6">
    @if ($article->cover)
        <img class="pull-left pr-2" src="{{ Storage::url($article->cover) }}" width="150">
    @endif
    <h5>
        <a href="{{ route('front.article.slug', $article->slug) }}">
            {{ $article->title }}
        </a>
    </h5>
    <p>{{ __('Created by:') }} {{ $article->author->first_name }} {{ $article->author->last_name }}</p>
    <p>{{ __('Posted at:') }} {{$article->created_at}}</p>
    <p>{{ str_limit($article->description) }}</p>
</div>