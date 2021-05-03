<?php

declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Partial;

use BadMethodCallException;
use Exception;
use Pollen\Partial\Drivers\ModalDriverInterface;
use Pollen\Partial\PartialViewLoader;
use tiFy\Wordpress\Contracts\Query\QueryPost;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getId()
 * @method string getIndex()
 * @method ModalDriverInterface modal()
 * @method false|QueryPost privacyPolicy()
 */
class CookieLawPartialView extends PartialViewLoader
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
}