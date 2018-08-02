<?php
/**
 * @copyright C VR Solutions 2018
 *
 * This software is the property of VR Solutions
 * and is protected by copyright law – it is NOT freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact VR Solutions:
 * E-mail: vytautas.rimeikis@gmail.com
 * http://www.vrwebdeveloper.lt
 */

declare(strict_types = 1);

namespace App\Console\Commands\ArticlesApi;

use App\Category;
use App\Services\ClientAPI\ClientCategoryService;
use GuzzleHttp\Client;

/**
 * Class CategoryListCommand
 * @package App\Console\Commands\ArticlesApi
 */
class CategoryListCommand extends ArticleBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:categories-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get categories paginator list';
    /**
     * @var ClientCategoryService
     */
    private $clientCategoryService;

    /**
     * CategoryListCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientCategoryService = app()->make(ClientCategoryService::class);
    }

    /**
     * Execute the console command.
     *
     * @param string|null $url
     */
    public function handle(string $url = null): void
    {
        try {
            $response = $this->client->get(($url) ? $url : $this->getCallUrl());

            $result = json_decode($response->getBody()->getContents());

            $this->checkSuccess($result);

            foreach ($result->data->data as $row) {
                $category = $this->clientCategoryService->saveFromObject($row);
                $this->info('Category with slug: ' . $category->slug . ', updated or created successfully.');
            }

            if ($url = $result->data->next_page_url) {
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
        return strtr(':url/category', [
            ':url' => parent::getCallUrl(),
        ]);
    }
}
