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
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class CategoryListCommand
 * @package App\Console\Commands\ArticlesApi
 */
class CategoryListCommand extends Command
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
    protected $description = 'Get catgeories paginator list';
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $url;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $version;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = config('article_api.api_url');
        $this->version = config('article_api.api_version');
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

            $response = $client->request('GET', $this->getCallUrl());

            $result = json_decode($response->getBody()->getContents());

            if (!$result->success) {
                $this->error($result->message);
                exit();
            }

            foreach ($result->data->data as $row) {
                $category = $this->saveData($row);
                $this->info('Category with slug: ' . $category->slug . ', updated or created successfully.');
            }

        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    private function getCallUrl(): string
    {
        return strtr(':domain/:version/category', [
            ':domain' => $this->url,
            ':version' => $this->version,
        ]);
    }

    /**
     * @param \stdClass $row
     * @return Category
     */
    private function saveData(\stdClass $row): Category
    {
        return Category::updateOrCreate(
            ['slug' => $row->slug],
            ['title' => $row->title, 'reference_category_id' => $row->category_id]
        );
    }
}
