<?php

namespace App\Traits\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

trait TranslatableEntity
{
    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}