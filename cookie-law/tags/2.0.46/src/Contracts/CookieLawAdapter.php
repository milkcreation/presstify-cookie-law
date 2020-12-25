<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Contracts;

interface CookieLawAdapter
{
    /**
     * Traitement des attributs de configuration de rendu.
     *
     * @return CookieLaw
     */
    public function parseConfig(): CookieLaw;
}
