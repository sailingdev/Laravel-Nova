<?php

namespace FbReporting\CreateCampaignsFromTemplateCard;

use Laravel\Nova\Card;

class CreateCampaignsFromTemplateCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'create-campaigns-from-template-card';
    }
}
