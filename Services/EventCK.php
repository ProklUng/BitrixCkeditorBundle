<?php

namespace Local\Bundles\CkEditorBundle\Services;

use Bitrix\Main\Page\Asset;

/**
 * Class EventCK
 * @package Prokl\Ckeditor
 */
class EventCK
{
    /**
     * Подключение ассетов.
     *
     * @return void
     */
    public function register() : void
    {
        if (!defined('ADMIN_SECTION')) {
            return;
        }

        Asset::getInstance()->addJs('/local/assets/ck5editor-bundle/build/ckeditor.js');
        Asset::getInstance()->addJs('/local/assets/ck5editor-bundle/assets/script.js');
        Asset::getInstance()->addCss('/local/assets/ck5editor-bundle/assets/style.css');
    }
}
