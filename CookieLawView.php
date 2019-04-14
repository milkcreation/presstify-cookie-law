<?php

namespace tiFy\Plugins\CookieLaw;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Partial\Modal;
use tiFy\Wordpress\Contracts\QueryPost;
use tiFy\View\ViewController;

/**
 * Class FieldView
 *
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getId()
 * @method string getIndex()
 * @method Modal modal()
 * @method false|QueryPost privacyPolicy()
 */
class CookieLawView extends ViewController
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'after',
        'attrs',
        'before',
        'content',
        'getId',
        'getIndex',
        'modal',
        'privacyPolicy'
    ];

    /**
     * Translation d'appel des méthodes de l'application associée.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) {
            try {
                return $this->engine->get('cookie-law')->$name(...$arguments);
            } catch (Exception $e) {
                throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
            }
        }
        throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
    }
}