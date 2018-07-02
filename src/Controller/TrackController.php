<?php

namespace App\Controller;

use App\Entity\Track;
use App\Entity\UserEntity;
use App\Utils\APIResponse;
use App\Utils\TracksUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
                ->setTrack($req->get('track_url'))
                ->setValidated(false);

            $em->persist($track);
            $em->flush();

            $responseContent = json_encode(TracksUtils::getTrackInfos($track));
            return APIResponse::createResponse($responseContent, APIResponse::HTTP_CREATED);

        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    //
    // DELETE
    //

    public function removeTrack(Request $req, $id) {

        if ($req->isMethod(Request::METHOD_DELETE)) {


            $em = $this->getDoctrine()->getManager();

            $track = $em->getRepository(Track::class)->find($id);

            if(!$track) {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                    APIResponse::HTTP_NOT_FOUND);

            }

            $responseContent = json_encode(TracksUtils::getTrackInfos($track));

            $em->remove($track);
            $em->flush();

            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    //
    // PATCH
    //

    public function addLike(Request $req, $id) {

        if ($req->isMethod(Request::METHOD_DELETE)) {


            $em = $this->getDoctrine()->getManager();

            $track = $em->getRepository(Track::class)->find($id);

            if(!$track) {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                    APIResponse::HTTP_NOT_FOUND);

            }

            $track->setLikes($track->getLikes() + 1);

            $responseContent = json_encode(TracksUtils::getTrackInfos($track));

            $em->remove($track);
            $em->flush();

            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    public function removeLike(Request $req, $id) {

        if ($req->isMethod(Request::METHOD_DELETE)) {


            $em = $this->getDoctrine()->getManager();

            $track = $em->getRepository(Track::class)->find($id);

            if(!$track) {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                    APIResponse::HTTP_NOT_FOUND);

            }

            $track->setLikes($track->getLikes() - 1);

            $responseContent = json_encode(TracksUtils::getTrackInfos($track));

            $em->remove($track);
            $em->flush();

            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }
}