<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Article;
use App\Author;
use GuzzleHttp\Client;

class ArticleByReferenceCommand extends ArticleBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:by-id {reference_article_id : Article reference id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get article by client article ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        try {
            $client = new Client();
            $response = $client->get($this->getCallUrl());

            $result = json_decode($response->getBody()->getContents());

            if (!$result->success) {
                $this->error($result->message);
                exit();
            }

            $article = $this->saveData($result->data);

            $this->info('Article with ID: ' . $article->id . ' saved!');
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    protected function getCallUrl(): string
    {
        return strtr(':url/article/:id', [
            ':url' => parent::getCallUrl(),
            ':id' => $this->argument('reference_article_id'),
        ]);
    }

    private function saveData(\stdClass $article): Article
    {
        return Article::updateOrCreate(
            ['slug' => $article->slug],
            [
                'title' => $article->title,
                'description' => $article->content,
                'reference_article_id' => $article->article_id,
                'author_id' => $this->saveAuthor($article->author)->id,
            ]
        );
    }

    private function saveAuthor(\stdClass $author): Author
    {
        return Author::updateOrCreate(
            ['reference_author_id' => $author->author_id],
            ['first_name' => $author->first_name, 'last_name' => $author->last_name]
        );
    }
}
