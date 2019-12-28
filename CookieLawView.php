<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Partial\Modal;
use tiFy\Wordpress\Contracts\Query\QueryPost;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getId()
 * @method string getIndex()
 * @method Modal modal()
 * @method false|QueryPost privacyPolicy()
 */
class CookieLawView extends PlatesFactory
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
     * @param string $method Nom de la méthode à appeler.
     * @param array $parameters Liste des variables passées en argument.
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return $this->engine()->params('cookie-law')->$method(...$parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $method));
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}