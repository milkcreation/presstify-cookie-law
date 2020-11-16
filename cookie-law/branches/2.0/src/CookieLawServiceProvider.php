<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\CookieLaw\Adapter\WordpressAdapter;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
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
        'cookie-law.view-engine',
        'cookie-law.wp-adapter',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (($wp = $this->getContainer()->get('wp')) && $wp->is()) {
            add_action('after_setup_theme', function () {
                /** @var CookieLawContract $cookieLaw */
                $cookieLaw = $this->getContainer()->get('cookie-law');

                if ($adapter = $cookieLaw->resolve('wp-adapter')) {
                    $cookieLaw->setAdapter($adapter);
                }

                return $cookieLaw->boot();
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('cookie-law', function () {
            return new CookieLaw(config('cookie-law', []), $this->getContainer());
        });

        $this->registerViewEngine();

        $this->registerWpAdapter();
    }

    /**
     * Déclaration du service de moteur de gabarits d'affichage.
     *
     * @return void
     */
    public function registerViewEngine(): void
    {
        $this->getContainer()->share('cookie-law.view-engine', function () {
            /** @var CookieLawContract $cookieLaw */
            $cookieLaw = $this->getContainer()->get('cookie-law');

            return View::getPlatesEngine(array_merge([
                'cookie-law' => $this->manager,
                'directory'  => __DIR__ . '/Resources/views',
                'engine'     => 'plates',
                'factory'    => CookieLawView::class,
            ], $cookieLaw->config('viewer', [])));
        });
    }

    /**
     * Déclaration du service d'adapteur Wordpress.
     *
     * @return void
     */
    public function registerWpAdapter(): void
    {
        $this->getContainer()->share('cookie-law.wp-adapter', function () {
            return new WordpressAdapter($this->getContainer()->get('cookie-law'));
        });
    }
}