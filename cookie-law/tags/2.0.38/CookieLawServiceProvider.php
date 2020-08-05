<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\CookieLaw\{Adapter\WordpressCookieLaw, Contracts\CookieLaw as CookieLawContract};
use tiFy\Support\Proxy\View;

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
        'cookie-law',
        'cookie-law.view',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->getContainer()->share('cookie-law', function () {
            return new CookieLaw($this->getContainer());
        });

        $this->getContainer()->share('cookie-law.view', function () {
            return View::register('cookie-law', array_merge($this->manager->get('viewer', []), [
                'cookie-law'   => $this->manager,
                'directory'    => __DIR__ . '/Resources/views',
                'engine'       => 'plates',
                'factory'      => CookieLawView::class,
            ]));
        });

        add_action('after_setup_theme', function () {
            $this->getContainer()->share('cookie-law', function () {
                return (new WordpressCookieLaw($this->getContainer()));
            });

            $this->manager = $this->getContainer()->get('cookie-law');

            $this->manager->set(config('cookie-law', []))->parse();
        });
    }
}