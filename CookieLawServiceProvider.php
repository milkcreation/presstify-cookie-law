<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\Adapter\WordpressCookieLaw;

class CookieLawServiceProvider extends ServiceProvider
{
    /**
     * Instance du gestionnaire de plugin.
     * @var CookieLawContract
     */
    protected $manager;

    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        CookieLawContract::class,
        'cookie-law.viewer'
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->getContainer()->share(CookieLawContract::class, function() {
            return (new CookieLaw($this->getContainer()));
        });

        $this->getContainer()->share('cookie-law.viewer', function() {
            $default_dir = __DIR__ . '/Resources/views';

            return view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setController(CookieLawView::class)
                ->setOverrideDir((($override_dir = $this->manager->get('viewer.override_dir')) && is_dir($override_dir))
                    ? $override_dir
                    : (is_dir($default_dir) ? $default_dir : __DIR__))
                ->setParam('cookie-law', $this->manager);
        });

        add_action('after_setup_theme', function() {
            $this->getContainer()->share(CookieLawContract::class, function() {
                return (new WordpressCookieLaw($this->getContainer()));
            });

            $this->manager = $this->getContainer()->get(CookieLawContract::class);

            $this->manager->set(config('cookie-law', []))->parse();
        });
    }
}