<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Partial;

use tiFy\Plugins\CookieLaw\CookieLaw as CookieLawManager;
use tiFy\Contracts\Partial\Partial as PartialManager;
use tiFy\Partial\PartialDriver;
use tiFy\Plugins\CookieLaw\CookieLawAwareTrait;

abstract class AbstractPartialDriver extends PartialDriver
{
    use CookieLawAwareTrait;

    public function __construct(CookieLawManager $cookieLawManager, PartialManager $partialManager)
    {
        $this->setCookieLaw($cookieLawManager);

        parent::__construct($partialManager);
    }

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