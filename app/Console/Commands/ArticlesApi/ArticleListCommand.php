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

use App\Article;
use App\Author;
use App\Category;
use GuzzleHttp\Client;

/**
 * Class ArticleListCommand
 * @package App\Console\Commands\ArticlesApi
 */
class ArticleListCommand extends ArticleBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list articles';

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
     */
    public function handle(string $url = null): void
    {
        try {
            $client = new Client();

            $response = $client->get(($url) ? $url : $this->getCallUrl());

            $context = json_decode($response->getBody()->getContents());

            if (!$context->success) {
                $this->error($context->message);
                exit();
            }

            foreach ($context->data->data as $item) {
                $article = $this->saveData($item);
                $this->info('Article saved with ID: ' . $article->id);
            }

            if ($url = $context->data->next_page_url) {
                $this->handle($url);
            }

        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @param \stdClass $data
     * @return Article
     */
    private function saveData(\stdClass $data): Article
    {
        /** @var Article $article */
        $article = Article::updateOrCreate(
            ['slug' => $data->slug],
            [
                'title' => $data->title,
                'description' => $data->content,
                'reference_article_id' => $data->article_id,
                'author_id' => $this->saveAuthor($data->author)->id,
            ]
        );

        $categoriesIds = $this->saveCategories($data->categories);

        $article->categories()->sync($categoriesIds);

        return $article;
    }

    /**
     * @return string
     */
    protected function getCallUrl(): string
    {
        return strtr(':url/article', [
            ':url' => parent::getCallUrl(),
        ]);
    }

    /**
     * @param \stdClass $data
     * @return Author
     */
    private function saveAuthor(\stdClass $data): Author
    {
        return Author::updateOrCreate(
            ['reference_author_id' => $data->author_id],
            [
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
            ]
        );
    }

    /**
     * @param array $categories
     * @return array
     */
    private function saveCategories(array $categories): array
    {
        $ids = [];

        foreach ($categories as $category) {
            $ids[] = Category::updateOrCreate(
                ['reference_category_id' => $category->category_id],
                [
                    'title' => $category->title,
                    'slug' => $category->slug,
                ]
            )->id;
        }

        return $ids;
    }
}
