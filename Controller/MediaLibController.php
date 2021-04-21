<?php

namespace Local\Bundles\CkEditorBundle\Controller;

use Bitrix\Main\DB\SqlQueryException;
use CMain;
use CUser;
use Local\Bundles\CkEditorBundle\Services\MediaLib;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MediaLibController
 * @package Local\Bundles\CkEditorBundle\Controller
 *
 * @since 21.04.2021
 */
class MediaLibController extends AbstractController
{
    /**
     * @return string
     * @throws SqlQueryException Когда обломался SQL запрос.
     */
    public function action() : string
    {
        /* @global $USER CUser */

        global $USER;

        if (!$USER->IsAuthorized()) {
            return json_encode([
                'error' => true,
                'message' => 'Authorized users only.'
            ]);
        }

        $pageNum = !empty($_REQUEST['page_num']) ? (int)$_REQUEST['page_num'] : 1;
        $collectionId = !empty($_REQUEST['collection_id']) ? (int)$_REQUEST['collection_id'] : 0;

        $collections = MediaLib::getCollections();
        array_unshift(
            $collections,
            [
                'ID'   => 0,
                'NAME' => 'нет',
            ]
        );

        $result = [
            'collections'   => $collections,
            'collection_id' => 0,
            'items'         => [],
            'page_count'    => 0,
            'page_num'      => 1,
        ];

        if ($collectionId > 0) {
            $elements = Medialib::getElements(
                [
                    'collection_id' => $collectionId,
                ],
                [
                    'page_size' => 15,
                    'page_num'  => $pageNum,
                ],
                [
                    'width'  => 50,
                    'height' => 50,
                    'exact'  => 1,
                ],
                [
                    'width'  => 1024,
                    'height' => 768,
                    'exact'  => 0,
                ]
            );

            $result['collection_id'] = $collectionId;
            $result['items'] = $elements['items'];
            $result['page_num'] = $elements['page_num'];
            $result['page_count'] = $elements['page_count'];
        }


        return json_encode($result);
    }
}
