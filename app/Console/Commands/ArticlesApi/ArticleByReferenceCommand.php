<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Services\ClientAPI\ClientArticleService;

/**
 * Class ArticleByReferenceCommand
 * @package App\Console\Commands\ArticlesApi
 */
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
     * @var ClientArticleService
     */
    private $clientArticleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientArticleService = app()->make(ClientArticleService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        try {
            $response = $this->client->get($this->getCallUrl());

            $result = json_decode($response->getBody()->getContents());

            $this->checkSuccess($result);

            $article = $this->clientArticleService->saveFromObject($result->data);

            $this->info('Article with ID: ' . $article->id . ' saved!');
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function getCallUrl(): string
    {
        return strtr(':url/article/:id', [
            ':url' => parent::getCallUrl(),
            ':id' => $this->argument('reference_article_id'),
        ]);
    }
}
