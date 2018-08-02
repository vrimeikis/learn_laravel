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

use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class ArticleBase
 * @package App\Console\Commands\ArticlesApi
 */
abstract class ArticleBase extends Command
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $url;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $version;
    /**
     * @var Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();

        $this->url = config('article_api.api_url');
        $this->version = config('article_api.api_version');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * @return string
     */
    protected function getCallUrl(): string
    {
        return strtr(':domain/:version', [
            ':domain' => $this->url,
            ':version' => $this->version,
        ]);
    }

    /**
     * @param \stdClass $data
     */
    protected function checkSuccess(\stdClass $data): void
    {
        if (!$data->success) {
            $this->error($data->message);
            exit();
        }
    }
}
