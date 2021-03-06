<?php

declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use Pollen\Partial\Drivers\ModalDriverInterface;
use Pollen\Support\Proxy\PartialProxy;
use RuntimeException;
use Pollen\Partial\Drivers\ModalDriver;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\Contracts\CookieLawAdapter;
use tiFy\Plugins\CookieLaw\Contracts\PrivacyLinkPartial as PrivacyLinkPartialContract;
use tiFy\Plugins\CookieLaw\Partial\PrivacyLinkPartial;
use tiFy\Support\Concerns\BootableTrait;
use tiFy\Support\Concerns\ContainerAwareTrait;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Request;
use tiFy\Support\Proxy\Router;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\Proxy\View;

class CookieLaw implements CookieLawContract
{
    use BootableTrait;
    use ContainerAwareTrait;
    use PartialProxy;

    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Liste des services par défaut fournis par conteneur d'injection de dépendances.
     * @var array
     */
    private $defaultProviders = [];

    /**
     * Instance du gestionnaire des ressources
     * @var LocalFilesystem|null
     */
    private $resources;

    /**
     * Instance de l'adapteur associé.
     * @var CookieLawAdapter
     */
    protected $adapter;

    /**
     * Instance du gestionnaire de configuration.
     * @var ParamsBag
     */
    protected $config;

    /**
     * Instance de la fenêtre modal d'affichage de la politique de confidentialité.
     * @var ModalDriver|false|null
     */
    protected $modal;

    /**
     * Moteur des gabarits d'affichage.
     * @var ViewEngine|null
     */
    protected $viewEngine;

    /**
     * Url de requête HTTP XHR.
     * @var string
     */
    protected $xhrModalUrl;

    /**
     * @param array $config
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(array $config = [], Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): CookieLawContract
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable %s instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function adapter(): ?CookieLawAdapter
    {
        return $this->adapter;
    }

    /**
     * @inheritDoc
     */
    public function boot(): CookieLawContract
    {
        if (!$this->isBooted()) {
            $this->xhrModalUrl = Router::xhr(md5('CookieLaw'), [$this, 'xhrModal'])->getUrl();

            $this->partial()->register(
                'privacy-link',
                $this->containerHas(PrivacyLinkPartialContract::class)
                ? PrivacyLinkPartialContract::class : new PrivacyLinkPartial($this, $this->partial())
            );

            $this->parseConfig();

            $this->setBooted();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        if (!isset($this->config) || is_null($this->config)) {
            $this->config = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->config->get($key, $default);
        }
        if (is_array($key)) {
            return $this->config->set($key);
        }

        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getProvider(string $name)
    {
        return $this->config("providers.{$name}", $this->defaultProviders[$name] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function modal(): ?ModalDriverInterface
    {
        if (is_null($this->modal) && ($this->config('modal') !== false)) {
            $this->modal = $this->partialManager->get('modal', $this->config('modal', []));
        }

        return $this->modal;
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): CookieLawContract
    {
        $this->config([
            'id'             => 'CookieLaw',
            'privacy_policy' => [
                'content' => $this->view('partial/cookie-notice/default-txt'),
                'title'   => $this->view('partial/cookie-notice/default-title'),
            ],
        ]);

        $modal = $this->config('modal', true);
        if ($this->config('modal') !== false) {
            if (!is_array($modal)) {
                $this->config([
                    'modal' => [
                        'ajax'      => [
                            'url' => $this->xhrModalUrl,
                        ],
                        'attrs'     => [
                            'id' => 'Modal-cookieLaw-privacyPolicy',
                        ],
                        'options'   => ['show' => false, 'backdrop' => true],
                        'size'      => 'xl',
                        'in_footer' => false,
                    ],
                ]);
            }

            foreach (['header', 'body', 'footer'] as $part) {
                if (!$this->config()->has("modal.content.{$part}")) {
                    $this->config([
                        "modal.content.{$part}" => $this->view(
                            "partial/modal/content-{$part}",
                            $this->config()->all()
                        ),
                    ]);
                }
            }

            if (!$this->config()->has('modal.viewer.override_dir')) {
                $this->config(['modal.viewer.override_dir' => $this->resources('views/partial/modal')]);
            }
        }

        return $this->adapter() ? $this->adapter()->parseConfig() : $this;
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) || is_null($this->resources)) {
            $this->resources = Storage::local(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources');
        }

        return is_null($path) ? $this->resources : $this->resources->path($path);
    }

    /**
     * @inheritDoc
     */
    public function setAdapter(CookieLawAdapter $adapter): CookieLawContract
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $attrs): CookieLawContract
    {
        $this->config($attrs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view('partial/cookie-notice/index', $this->config()->all());
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $this->viewEngine = $this->containerHas('cookie-law.view-engine')
                ? $this->containerGet('cookie-law.view-engine') : View::getPlatesEngine();
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($name, $data);
    }

    /**
     * @inheritDoc
     */
    public function xhrModal(): array
    {
        $modal = $this->parseConfig()->modal();

        $viewer = Request::input('viewer', []);
        foreach ($viewer as $key => $value) {
            $modal->view()->params([$key => $value]);
        }

        return [
            'success' => true,
            'data'    => $modal->view('ajax-content', [
                'title'   => $this->config('privacy_policy.title'),
                'content' => $this->config('privacy_policy.content'),
            ]),
        ];
    }
}
