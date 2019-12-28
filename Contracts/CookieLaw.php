<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Contracts;

use tiFy\Contracts\Partial\Modal;
use tiFy\Contracts\View\{PlatesFactory, PlatesEngine};
use tiFy\Contracts\Support\ParamsBag;

interface CookieLaw extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function defaults(): array;

    /**
     * Affichage du bandeau de validation.
     *
     * @return string
     */
    public function display(): string;

    /**
     * Récupération de la modale d'affichage de la politique de confidentialité.
     *
     * @return Modal|null
     */
    public function modal(): ?Modal;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function parse(): CookieLaw;

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return PlatesFactory|PlatesEngine
     */
    public function viewer(?string $view = null, array $data = []);
}
