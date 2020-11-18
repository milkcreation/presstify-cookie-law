<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw;

use Exception;
use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContact;

trait CookieLawAwareTrait
{
    /**
     * Instance de l'application.
     * @var CookieLaw|null
     */
    private $cl;

    /**
     * Récupération de l'instance de l'application.
     *
     * @return CookieLaw|null
     */
    public function cl(): ?CookieLaw
    {
        if (is_null($this->cl)) {
            try {
                $this->cl = CookieLaw::instance();
            } catch (Exception $e) {
                $this->cl;
            }
        }

        return $this->cl;
    }

    /**
     * Définition de l'application.
     *
     * @param CookieLawContact $cl
     *
     * @return static
     */
    public function setCookieLaw(CookieLawContact $cl): self
    {
        $this->cl = $cl;

        return $this;
    }
}