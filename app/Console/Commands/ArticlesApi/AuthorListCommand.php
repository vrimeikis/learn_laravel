<?php

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Author;
use GuzzleHttp\Client;

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
     * @param string|null $url
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(string $url = null): void
    {
        try {
            $client = new Client();

            $result = $client->get(($url) ? $url : $this->getCallUrl());

            $data = json_decode($result->getBody()->getContents());

            if (!$data->success) {
                logger($data->message, $data);

                exit();
            }

            foreach ($data->data->data as $row) {
                $author = $this->saveData($row);
                $this->info('Updated or created author with refernce: ' . $author->reference_author_id);
            }

            if ($url = $data->data->next_page_url) {
                $this->handle($url);
            }
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @param \stdClass $data
     * @return Author
     */
    private function saveData(\stdClass $data): Author
    {
        return Author::updateOrCreate(
            ['first_name' => $data->first_name, 'last_name' => $data->last_name],
            ['reference_author_id' => $data->author_id]
        );
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
