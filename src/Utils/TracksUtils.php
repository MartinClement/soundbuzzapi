<?php
/**
 * Created by PhpStorm.
 * track: clement
 * Date: 30/06/18
 * Time: 09:47
 */

namespace App\Utils;


use App\Entity\Track;

class TracksUtils
{

    const QUERY_PARAMS_MAP = array(
        'date' => 'created_at',
        'likes' => 'likes',
        'length' => 'length',
        'playedtimes' => 'played_times'
    );

    public static function getTrackInfos(Track $track)
    {

        return array(
            'track_id' => $track->getId(),
            'track' => $track->getTrackUrl(),
            'cover' => $track->getCoverUrl(),
            'genre' => $track->getGenre(),
            'owner' => $track->getOwner()->getUsername(),
            'owner_id' => $track->getOwner()->getId(),
            'title' => $track->getTitle(),
            'played_times' => $track->getPlayedTimes(),
            'downloaded_times' => $track->getDowloadedTimes(),
            'likes' => $track->getLikes(),
            'length' => $track->getLength(),
            'explicit' => $track->getExplicit(),
            'downlodable' => $track->getDownloadable(),
            'created_at' => $track->getCreatedAt()->format('d-m-Y'),
            'updated' => $track->getUpdatedAt()->format('d-m-Y'),
            'validated' => $track->getValidated(),
        );
    }
}