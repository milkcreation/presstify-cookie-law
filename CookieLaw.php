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
 * @version 2.0.33
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\CookieLaw\CookieLawServiceProvider à la liste des fournisseurs de services.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\CookieLaw\CookieLawServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          CookieLawServiceProvider::class
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
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->display();
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function defaults(): array
    {
        return [
            'display'        => true,
            'modal'          => true,
            'privacy_policy' => [],
            'viewer'         => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        return (string)$this->viewer('cookie-law', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function modal(): ?Modal
    {
        if (is_null($this->modal) && ($modal = $this->get('modal'))) {
            $this->modal = Partial::get('modal', 'cookieLaw-privacyPolicy', array_merge([
                'attrs'          => [
                    'id' => 'Modal-cookieLaw-privacyPolicy',
                ],
                'content'        => [
                    'header'         => (string)$this->viewer('modal-header', $this->all()),
                    'body'           => (string)$this->viewer('modal-body', $this->all()),
                    'footer'         => (string)$this->viewer('modal-footer', $this->all()),
                ],
                'options'        => ['show' => false, 'backdrop' => true],
                'size'           => 'lg',
                'in_footer'      => false,
            ], is_array($modal) ? $modal : []));
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
            'id' => 'CookieLaw',
            'privacy_policy' => [
                'content'   => (string)$this->viewer('default-txt'),
                'title'     => (string)$this->viewer('default-title'),
            ]
        ]);

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
