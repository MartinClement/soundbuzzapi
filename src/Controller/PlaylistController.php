<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\UserEntity;
use App\Utils\APIResponse;
use App\Utils\PlaylistUtils;
use App\Utils\TracksUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlaylistController extends Controller
{
    public function getPlaylists() {

        $playlists = $this->getDoctrine()->getRepository(Playlist::class)->findAll();

        $_data = array('playlists' => array());

        foreach ($playlists as $playlist) {

            $_data['playlists'][] = PlaylistUtils::getPlaylistInfos($playlist);
        }

        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    public function getPlaylistById($id) {

        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);

        if (!$playlist) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

        $_data = PlaylistUtils::getPlaylistInfos($playlist);
        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);
    }

    public function getUserPlaylists($id)
    {
        $em = $this->getDoctrine()->getManager();

        $userRep = $em->getRepository(UserEntity::class);
        $playlistRep = $em->getRepository(Playlist::class);

        $user = $userRep->find($id);

        if (!$user) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

        $playlists = $playlistRep->findBy(array('owner' => $user));

        $_data = array('playlists' => array());

        foreach ($playlists as $playlist) {

            $_data['playlists'][] = PlaylistUtils::getPlaylistInfos($playlist);
        }

        $_data = json_encode($_data);


        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    public function getPlaylistTracks($id) {

        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);

        if (!$playlist) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

        $playlistTracks = $playlist->getTracks();

        $_data = array('playlistTracks' => array());

        foreach ($playlistTracks as $track) {

            $_data['playlistTracks'][] = TracksUtils::getTrackInfos($track);
        }

        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);
    }
}
