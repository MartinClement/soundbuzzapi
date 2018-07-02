<?php

namespace App\Controller;

use App\Entity\Track;
use App\Entity\UserEntity;
use App\Utils\APIResponse;
use App\Utils\FileUtils;
use App\Utils\TracksUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrackController extends Controller
{

    //
    // GET
    //

    public function getTracks() {

        $tracks = $this->getDoctrine()->getRepository(Track::class)->findAll();

        $_data = array('tracks' => array());

        foreach ($tracks as $track) {

            $_data['tracks'][] = TracksUtils::getTrackInfos($track);
        }

        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    public function getTrackById($id) {

        $rep = $this->getDoctrine()->getRepository(Track::class);

        $track = $rep->find($id);

        if (!$track) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

        $_data = TracksUtils::getTrackInfos($track);
        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);
    }

    public function getUserTracks(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $userRep = $em->getRepository(UserEntity::class);
        $trackRep = $em->getRepository(Track::class);

        $user = $userRep->find($id);

        if (!$user) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

        $tracks = $trackRep->findBy(array('owner' => $user));

        $_data = array('tracks' => array());

        foreach ($tracks as $track) {

            $_data['tracks'][] = TracksUtils::getTrackInfos($track);
        }

        $_data = json_encode($_data);


        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    //
    // POST
    //

    // POST

    public function addTrack(Request $req) {

        if ($req->isMethod(Request::METHOD_POST)) {


            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(UserEntity::class)->find($req->get('user_id'));

            if (!$user) {

                return APIResponse::createResponse(APIResponse::getErrorResponseContent(
                    APIResponse::HTTP_BAD_REQUEST),
                    APIResponse::HTTP_BAD_REQUEST);
            }

            $date = new \DateTime('now');

            var_dump($req->getContent());

            $trackFile = $req->get('track_file');

            $trackFileName = FileUtils::getUniqueFileName() . '.' . $trackFile->guessExtension();

            $track = new Track();
            $track
                ->setOwner($user)
                ->setTitle($req->get('title'))
                ->setPlayedTimes(0)
                ->setDowloadedTimes(0)
                ->setLikes(0)
                ->setlength(145)
                ->setExplicit($req->get('explicit'))
                ->setDownloadable($req->get('downloadable'))
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ->setTrack($trackFileName)
                ->setValidated(false);

            $em->persist($track);
            $em->flush();

            // upload the file on se API storage

            $trackFile->move(
                $this->getParameter('tracks_directory'),
                $trackFileName
            );


            $responseContent = json_encode(UserUtils::getUserInfos($user));
            return APIResponse::createResponse($responseContent, APIResponse::HTTP_CREATED);

        }


        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }
}


git add composer.json composer.lock config/routes.yaml config/services.yaml src/DataFixtures/AppFixtures.php src/Entity/Playlist.php src/Entity/Track.php src/Utils/UserUtils.php symfony.lock src/Utils/TracksUtils.php src/Utils/PlaylistUtils.php src/Utils/FileUtils.php src/Repository/TracKCommentaryRepository.php src/Repository/PlaylistCommentaryRepository.php src/Entity/TracKCommentary.php src/Entity/PlaylistCommentary.php src/DataFixtures/Rone_Nakt.mp3 src/Controller/TrackController.php src/Controller/PlaylistController.php public/tracks/ public/covers/ config/packages/translation.yaml