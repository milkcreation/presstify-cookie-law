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
     * @inheritDoc
     */
    public function __call($name, $args)
    {
        if (in_array($name, $this->mixins)) {
            try {
                $cookieLaw = $this->engine->params('cookie-law');

                return $cookieLaw->{$name}(...$args);
            } catch (Exception $e) {
                throw new BadMethodCallException(sprintf(
                    __CLASS__ . ' throws an exception during the method call [%s] with message : %s',
                    $name, $e->getMessage()
                ));
            }
        } else {
            return parent::__call($name, $args);
        }
    }
}