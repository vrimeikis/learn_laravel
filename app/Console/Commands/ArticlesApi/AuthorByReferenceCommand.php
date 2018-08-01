<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Author;
use GuzzleHttp\Client;

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
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        try {
            $client = new Client();

            $result = $client->request('GET', $this->getCallUrl());

            $data = json_decode($result->getBody()->getContents());

            if (!$data->success) {
                $this->error($data->message);
                exit();
            }

            Author::updateOrCreate(
                ['first_name' => $data->data->first_name, 'last_name' => $data->data->last_name],
                ['reference_author_id' => $data->data->author_id]
            );

            $this->info('Row updated or created success with reference author ID: ' . $data->data->author_id);

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
