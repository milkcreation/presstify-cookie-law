<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Partial\Partial as PartialManagerContract;
use tiFy\Plugins\CookieLaw\Adapter\WordpressAdapter;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\Contracts\PrivacyLinkPartial as PrivacyLinkPartialContract;
use tiFy\Plugins\CookieLaw\Contracts\WordpressAdapter as WordpressAdapterContract;
use tiFy\Plugins\CookieLaw\Partial\PrivacyLinkPartial;
use tiFy\Support\Proxy\View;

class CookieLawServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        CookieLawContract::class,
        PrivacyLinkPartialContract::class,
        WordpressAdapterContract::class,
        'cookie-law.view-engine',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        events()->listen('wp.booted', function () {
            /** @var CookieLawContract $cookieLaw */
            $cookieLaw = $this->getContainer()->get(CookieLawContract::class);

            if ($cookieLaw->containerHas(WordpressAdapterContract::class)) {
                $cookieLaw->setAdapter($cookieLaw->containerGet(WordpressAdapterContract::class));
            }

            return $cookieLaw->boot();
        });
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(CookieLawContract::class, function () {
            return new CookieLaw(config('cookie-law', []), $this->getContainer());
        });

        $this->registerAdapters();
        $this->registerPartials();
        $this->registerViewEngine();
    }

    /**
     * Déclaration des adapteurs.
     *
     * @return void
     */
    public function registerAdapters(): void
    {
        $this->getContainer()->share(WordpressAdapterContract::class, function (): WordpressAdapterContract {
            return new WordpressAdapter($this->getContainer()->get(CookieLawContract::class));
        });
    }

    /**
     * Déclaration des pilotes de portions d'affichage.
     *
     * @return void
     */
    public function registerPartials(): void
    {
        $this->getContainer()->add(PrivacyLinkPartialContract::class, function ():PrivacyLinkPartialContract {
            return new PrivacyLinkPartial(
                $this->getContainer()->get(CookieLawContract::class),
                $this->getContainer()->get(PartialManagerContract::class)
            );
        });
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
            $cookieLaw = $this->getContainer()->get(CookieLawContract::class);

            return View::getPlatesEngine(array_merge([
                'directory'  => $cookieLaw->resources('views'),
                'factory'    => CookieLawView::class,
                'cookie-law' => $cookieLaw,
            ], $cookieLaw->config('viewer', [])));
        });
    }
}