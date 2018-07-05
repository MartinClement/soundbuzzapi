<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 04/07/18
 * Time: 11:01
 */

namespace App\Utils;


use App\Entity\TrackComment;

class CommentUtils
{

    public static function getCommentInfos(TrackComment $comment) {

        return array(
            'username' => $comment->getOwner()->getUsername(),
            'date' => $comment->getCreatedAt()->format('d-m-Y'),
            'content' => $comment->getContent(),
        );
    }
}