<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Services\ClientAPI\ClientAuthorService;

/**
 * Class AuthorByReferenceCommand
 * @package App\Console\Commands\ArticlesApi
 */
class AuthorByReferenceCommand extends ArticleBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:author-by-id {reference_author_id : Reference author id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get author info by reference ID';
    /**
     * @var ClientAuthorService
     */
    private $authorService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorService = app()->make(ClientAuthorService::class);
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        try {
            $result = $this->client->request('GET', $this->getCallUrl());

            $data = json_decode($result->getBody()->getContents());

            $this->checkSuccess($data);

            $author = $this->authorService->saveAuthorFromObject($data->data);

            $this->info('Row updated or created success with reference author ID: ' . $author->id);

        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function getCallUrl(): string
    {
        return strtr(':url/author/:id', [
            ':url' => parent::getCallUrl(),
            ':id' => $this->argument('reference_author_id'),
        ]);
    }
}
