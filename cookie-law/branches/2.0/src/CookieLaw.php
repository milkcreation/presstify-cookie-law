<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use Exception;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\Partial\Modal;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\Contracts\CookieLawAdapter;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Partial;
use tiFy\Support\Proxy\Request;
use tiFy\Support\Proxy\Router;
use tiFy\Support\Proxy\Storage;

class CookieLaw implements CookieLawContract
{
    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $booted = false;

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
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance de la fenêtre modal d'affichage de la politique de confidentialité.
     * @var Modal|false|null
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

        throw new Exception(sprintf('Unavailable %s instance', __CLASS__));
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
        if (!$this->booted) {
            $this->xhrModalUrl = Router::xhr(md5('CookieLaw'), [$this, 'xhrModal'])->getUrl();

            $this->booted = true;
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
        } elseif (is_array($key)) {
            return $this->config->set($key);
        } else {
            return $this->config;
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
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
    public function resolve(string $alias)
    {
        return ($container = $this->getContainer()) ? $container->get("cookie-law.{$alias}") : null;
    }

    /**
     * @inheritDoc
     */
    public function resolvable(string $alias): bool
    {
        return ($container = $this->getContainer()) && $container->has("cookie-law.{$alias}");
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) ||is_null($this->resources)) {
            $this->resources = Storage::local(dirname(__DIR__));
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
    public function setContainer(Container $container): CookieLawContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modal(): ?Modal
    {
        if (is_null($this->modal) && $this->config('modal')) {
            foreach (['header', 'body', 'footer'] as $part) {
                if (!$this->config()->has("modal.content.{$part}")) {
                    $this->config([
                        "modal.content.{$part}" => $this->view("modal/content-{$part}", $this->config()->all())
                    ]);
                }
            }

            /*if (!$this->config()->has('modal.viewer')) {
                $this->config([
                    'modal.viewer' => [
                        'override_dir' => $this->view()->getOverrideDir('/modal')
                        ?: $this->view()->getDirectory() . '/modal',
                    ]
                ]);
            }*/

            $this->modal = Partial::get('modal', 'cookieLaw-privacyPolicy', array_merge([
                'ajax'      => [
                    'url' => $this->xhrModalUrl,
                ],
                'attrs'     => [
                    'id' => 'Modal-cookieLaw-privacyPolicy',
                ],
                'options'   => ['show' => false, 'backdrop' => true],
                'size'      => 'xl',
                'in_footer' => false,
            ], $this->config('modal', [])));
        }

        /**
         *  'id'             => 'CookieLaw',
        'privacy_policy' => [
        'content' => $this->view('default-txt'),
        'title'   => $this->view('default-title'),
        ]
         */


        return $this->modal;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view('index', $this->config()->all());
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $this->viewEngine = $this->resources('view-engine');
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function xhrModal(): array
    {
        $modal = $this->modal();

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
