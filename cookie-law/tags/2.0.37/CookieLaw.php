<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Partial\Modal;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Support\{ParamsBag, Proxy\Partial};

/**
 * @desc Extension PresstiFy d'affichage des règles de cookie.
 * @author Jordy Manner <jordy@tigreblanc.fr>
 * @package tiFy\Plugins\CookieLaw
 * @version 2.0.37
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php
 * >> ajouter CookieLawServiceProvider à la liste des fournisseurs de services.
 * <?php
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          tiFy\Plugins\CookieLaw\CookieLawServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier cookie-law.php
 * @see /vendor/presstify-plugins/cookie-law/Resources/config/cookie-law.php
 */
class CookieLaw extends ParamsBag implements CookieLawContract
{
    /**
     * Instance de l'extension de gestion de politique de confidentialité du site.
     * @var CookieLawContract|null
     */
    protected static $instance;

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Instance de la fenêtre modal d'affichage de la politique de confidentialité.
     * @var null|false|Modal
     */
    protected $modal;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        if (!static::$instance instanceof CookieLawContract) {
            static::$instance = $this;

            if (!is_null($container)) {
                $this->setContainer($container);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): ?CookieLawContract
    {
        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function defaults(): array
    {
        return [
            'modal'          => true,
            'privacy_policy' => [],
            'viewer'         => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function modal(): ?Modal
    {
        if (is_null($this->modal) && $this->get('modal')) {
            foreach (['header', 'body', 'footer'] as $part) {
                if (!$this->has("modal.content.{$part}")) {
                    $this->set("modal.content.{$part}", $this->viewer("modal/content-{$part}", $this->all()));
                }
            }

            if (!$this->has('modal.viewer')) {
                $this->set('modal.viewer', [
                    'override_dir' => $this->viewer()->getOverrideDir('/modal')
                        ?: $this->viewer()->getDirectory() . '/modal',
                ]);
            }

            $this->modal = Partial::get('modal', 'cookieLaw-privacyPolicy', array_merge([
                'ajax'      => true,
                'attrs'     => [
                    'id' => 'Modal-cookieLaw-privacyPolicy',
                ],
                'options'   => ['show' => false, 'backdrop' => true],
                'size'      => 'xl',
                'in_footer' => false,
            ], $this->get('modal', [])));
        }

        return $this->modal;
    }

    /**
     * @inheritDoc
     */
    public function parse(): CookieLawContract
    {
        parent::parse();

        $this->set([
            'id'             => 'CookieLaw',
            'privacy_policy' => [
                'content' => $this->viewer('default-txt'),
                'title'   => $this->viewer('default-title'),
            ],
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (string)$this->viewer('index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function resources($path = ''): string
    {
        $path = $path ? '/' . ltrim($path, '/') : '';

        return file_exists(__DIR__ . "/Resources{$path}") ? __DIR__ . "/Resources{$path}" : '';
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
    public function viewer(?string $view = null, array $data = [])
    {
        $viewer = $this->container->get('cookie-law.view');

        if (func_num_args() === 0) {
            return $viewer;
        }

        return $viewer->render($view, $data);
    }
}
