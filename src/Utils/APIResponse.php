<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 06/06/18
 * Time: 10:20
 */

namespace App\Utils;



use Symfony\Component\HttpFoundation\Response;

class APIResponse extends Response
{

    const DEFAULT_CONTENT_TYPE = 'application/json';

    public static function createResponse($content, $statusCode, $contentType = self::DEFAULT_CONTENT_TYPE) {


        return new Response(
            $content,
            $statusCode,
            array('content-type' => $contentType)
        );
    }

    public static function getErrorResponseContent($code) {

        return json_encode(array(
            'status' => 'error',
            'status_code' => $code
        ));
    }

}