<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Partial;

use tiFy\Partial\PartialDriver;
use tiFy\Plugins\CookieLaw\CookieLawAwareTrait;

abstract class AbstractPartialDriver extends PartialDriver
{
    use CookieLawAwareTrait;

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, $data = [])
    {
        if (is_null($this->viewEngine)) {
            $viewEngine = parent::view();
            $viewEngine
                ->setParams(['cookie-law' => $this->cl])
                ->setFactory(CookieLawPartialView::class);
        }

        return parent::view($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->cl()->resources("views/partial/{$this->getAlias()}");
    }
}