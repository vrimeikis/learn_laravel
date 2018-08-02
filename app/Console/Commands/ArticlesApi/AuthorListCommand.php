<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Services\ClientAPI\ClientAuthorService;

/**
 * Class AuthorListCommand
 * @package App\Console\Commands\ArticlesApi
 */
class AuthorListCommand extends ArticleBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:authors-list';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get authors list';
    /**
     * @var ClientAuthorService
     */
    private $clientAuthorService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientAuthorService = app()->make(ClientAuthorService::class);
    }

    /**
     * Execute the console command.
     *
     * @param string|null $url
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(string $url = null): void
    {
        try {
            $result = $this->client->get(($url) ? $url : $this->getCallUrl());

            $data = json_decode($result->getBody()->getContents());

            $this->checkSuccess($data);

            foreach ($data->data->data as $row) {
                $author = $this->clientAuthorService->saveAuthorFromObject($row);

                $this->info('Updated or created author with reference: ' . $author->reference_author_id);
            }

            if ($url = $data->data->next_page_url) {
                $this->handle($url);
            }
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function getCallUrl(): string
    {
        return strtr(':url/author', [
            ':url' => parent::getCallUrl(),
        ]);
    }
}
