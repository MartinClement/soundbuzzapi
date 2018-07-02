<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 30/06/18
 * Time: 11:54
 */

namespace App\Utils;


use App\Entity\Playlist;

class PlaylistUtils
{
    public static function getPlaylistInfos(Playlist $pl)
    {

        return array(
            'playlist_id' => $pl->getId(),
            'owner_id' => $pl->getOwner()->getId(),
            'title' => $pl->getTitle(),
            'description' => $pl->getDescription()
        );
    }
}