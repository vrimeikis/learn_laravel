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
 * Class CategoryByReferenceCommand
 * @package App\Console\Commands\ArticlesApi
 */
class CategoryByReferenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:category-by-id {reference_category_id : Category ID on 3trd party application}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get category by id';
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
     */
    public function handle(): void
    {
        try {
            $client = new Client();

            $response = $client->get($this->getCallUrl());

            $data = json_decode($response->getBody()->getContents());

            if (!$data->success) {
                $this->error($data->message);
                exit();
            }

            $category = Category::updateOrCreate(
                ['slug' => $data->data->slug],
                ['title' => $data->data->title, 'reference_category_id' => $data->data->category_id]
            );

            $this->info('Category ' . $category->title . ' updated or created successfully.');
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    private function getCallUrl()
    {
        return strtr(':url/:version/category/:id', [
            ':url' => $this->url,
            ':version' => $this->version,
            ':id' => $this->argument('reference_category_id'),
        ]);
    }
}
