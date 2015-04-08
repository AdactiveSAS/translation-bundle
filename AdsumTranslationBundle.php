<?php

namespace Adsum\TranslationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdsumTranslationBundle extends Bundle
{

    public function getParent()
    {
        return 'JMSTranslationBundle';
    }

}
