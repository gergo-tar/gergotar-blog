<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Domain\Content\Actions\GetContents;
use Domain\Blog\Actions\GetPublishedPosts;

class GenerateSitemaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemaps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemaps for all locales.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(now()->format('Y-m-d H:i:s'));
        $this->info($this->description);

        $locales = config('localized-routes.supported_locales');
        $contents = GetContents::run(['id', 'title', 'slug', 'updated_at']);
        $pages = [
            'home' => 'intro',
            'about' => 'about-me',
            'blog' => 'blog',
            'contact' => 'contact',
        ];

        $generator = Sitemap::create();

        foreach ($locales as $locale) {
            app()->setLocale($locale);

            foreach ($pages as $route => $slug) {
                $generator->add(
                    Url::create(route($route))
                        ->setLastModificationDate(
                            $contents->where('slug', $slug)->first()?->updated_at ?? Carbon::yesterday()
                        )
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                        ->setPriority(0.1)
                );
            }

            $generator
                ->add(GetPublishedPosts::run(
                    isPaginated: false,
                    limit: -1,
                    select: ['id', 'published_at', 'updated_at'],
                    with: [
                        'translation' => ['post_id', 'locale', 'title', 'slug']
                    ]
                ));
        }

        $generator->writeToFile(public_path("sitemap.xml"));

        $this->info(now()->format('Y-m-d H:i:s'));
        $this->info('Sitemaps generated successfully!');
    }
}
