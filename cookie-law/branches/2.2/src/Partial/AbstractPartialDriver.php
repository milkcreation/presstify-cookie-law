<?php

declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Partial;

use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialManagerInterface;
use tiFy\Plugins\CookieLaw\CookieLaw as CookieLawManager;
use tiFy\Plugins\CookieLaw\CookieLawAwareTrait;

abstract class AbstractPartialDriver extends PartialDriver
{
    use CookieLawAwareTrait;

    public function __construct(CookieLawManager $cookieLawManager, PartialManagerInterface $partialManager)
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
                ->setDelegate($this->cl)
                ->setLoader(CookieLawPartialView::class);
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