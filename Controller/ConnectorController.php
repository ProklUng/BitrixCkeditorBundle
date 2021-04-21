<?php

namespace Local\Bundles\CkEditorBundle\Controller;

use CFile;
use CUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ConnectorController
 * @package Local\Bundles\CkEditorBundle\Controller
 *
 * @since 21.04.2021
 */
class ConnectorController extends AbstractController
{
    /**
     * @return string
     */
    public function action() : string
    {
        /* @global $USER CUser */

        global $USER;

        $result = [
            'uploaded' => 0,
        ];

        if (!$USER->IsAuthorized()) {
            return json_encode([
                'error' => true,
                'message' => 'Authorized users only.'
            ]);
        }

        if (!empty($_FILES['upload'])) {
            $checkErr = CFile::CheckImageFile($_FILES['upload'], 0, 0, 0);

            if (empty($checkErr)) {
                $fileId = CFile::SaveFile($_FILES['upload'], 'ckeditor');
                if (!empty($fileId)) {
                    $fileItem = CFile::GetFileArray($fileId);

                    $result = [
                        'fileName' => $fileItem['ORIGINAL_NAME'],
                        'url'      => $fileItem['SRC'],
                        'uploaded' => 1,
                    ];
                }
            }
        }

        return json_encode($result);
    }
}
