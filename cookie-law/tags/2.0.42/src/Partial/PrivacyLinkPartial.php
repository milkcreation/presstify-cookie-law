<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Partial;

class PrivacyLinkPartial extends AbstractPartialDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->has('content')) {
            $this->set('content', __('conditions relatives à la politique des données personnelles', 'tify'));
        }

        if ($modal = $this->cl()->modal()) {
            return $modal->trigger($this->all());
        }

        return parent::render();
    }
}