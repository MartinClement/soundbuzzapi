<?php

namespace App\Controller;


use App\Entity\Playlist;
use App\Entity\Track;
use App\Entity\UserEntity;
use App\Utils\APIResponse;
use App\Utils\PlaylistUtils;
use App\Utils\TracksUtils;
use App\Utils\UserUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    // GET

    public function getUsers() {

        $users = $this->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findAll();

        $_data = array('users' => array());

        foreach ($users as $user) {

            $_data['users'][] = UserUtils::getUserInfos($user);
        }

        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    public function getUserById($id) {

        $userRep = $this->getDoctrine()->getRepository(UserEntity::class);
        $trackRep = $this->getDoctrine()->getRepository(Track::class);
        $playlistRep = $this->getDoctrine()->getRepository(Playlist::class);

        $user = $userRep->find($id);

        if(!$user) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND);

        }

        $userData = array('user' => UserUtils::getUserInfos($user), 'tracks' => [], 'playlists' => []);

        $userTracks = $trackRep->findBy(array('owner' => $user));
        foreach ( $userTracks as $track) {

            $userData['tracks'][] = TracksUtils::getTrackInfos($track);
        }

        $userPlaylists = $playlistRep->findBy(array('owner' => $user));

        foreach ($userPlaylists as $pl) {

            $userData['playlists'][] = PlaylistUtils::getPlaylistInfos($pl);
        }


        $responseContent = json_encode($userData);
        return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);


    }

    public function validateUser(Request $req) {

        $token = $req->query->get('token');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(UserEntity::class)->findBy(array('validation_token' => $token))[0];

        if (!$user) {

            return new Response('<h1>API ERROR : USER NOT FOUND</h1>');

        }

        $user->setIsValidated(true);

        $em->persist($user);
        $em->flush();

        //return new Response('<h1> Inscription Validée </h1>');
        return $this->redirect('http://localhost:8000/login');
    }

    // POST

    public function addUser(Request $request, \Swift_Mailer $mailer) {

        if ($request->isMethod(Request::METHOD_POST)) {


            $em = $this->getDoctrine()->getManager();

            $validationToken = md5($request->get('email').uniqid());

            $user = new UserEntity();
            $user->setEmail($request->get('email'));
            $user->setFullName($request->get('fullname'));
            $user->setUsername($request->get('username'));
            $user->setRoles(array('ROLE_USER'));
            $user->setIsValidated(false);
            $user->setValidationToken($validationToken);
            $user->setCreatedAt(new \Datetime('now'));
            $user->setUpdatedAt(new \Datetime('now'));

            $userPassword = $this->passwordEncoder->encodePassword($user, $request->get('password'));
            $user->setPassword($userPassword);

            $em->persist($user);
            $em->flush();

            $message = (new \Swift_Message('Soundbuzz bienvenue !'))
                ->setFrom('soundbuzz@signup.com')
                ->setTo($request->get('email'))
                ->setBody(
                    '<h1>SoundBuzz : Bienvenue !</h1>
                          <p>Afin de compléter votre inscription, veuillez cliquer sur le lien suivant :</p>
                          <a href="http://localhost:8001/validate/inscription/?token=' .$validationToken.'">Valider mon inscription</a>'
                );

            $mailer->send($message);

            $responseContent = json_encode(UserUtils::getUserInfos($user));
            return APIResponse::createResponse($responseContent, APIResponse::HTTP_CREATED);



        }


        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    // PUT

    public function updateUser(Request $request, $id) {

        if ($request->isMethod(Request::METHOD_PUT)) {

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(UserEntity::class)->find($id);

            //TODO: Add security for user not found

            $user->setEmail($request->get('email'));
            $user->setFullName($request->get('fullname'));
            $user->setUsername($request->get('username'));
            $user->setUpdatedAt(new \Datetime('now'));

            $em->persist($user);
            $em->flush();

            $responseContent = json_encode(UserUtils::getUserInfos($user));
            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);

        }


        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    public function updateUserPassword(Request $request, $id) {

        if ($request->isMethod(Request::METHOD_PUT)) {

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(UserEntity::class)->find($id);

            //TODO: Add security for missing user...

            $currentPassword = $request->get('current_password');

            if ($this->passwordEncoder->isPasswordValid($user, $currentPassword)) {

                $newPassword = $this->passwordEncoder->encodePassword($user, $request->get('new_password'));

                $user->setPassword($newPassword);
                $user->setUpdatedAt(new \Datetime('now'));

                $em->persist($user);
                $em->flush();

                $responseContent = json_encode(UserUtils::getUserInfos($user));
                return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
            }

            return APIResponse::create(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_FORBIDDEN),
                APIResponse::HTTP_FORBIDDEN);

        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    // DELETE

    public function deleteUser(Request $request, $id) {

        if ($request->isMethod(Request::METHOD_DELETE)) {


            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(UserEntity::class)->find($id);

            if(!$user) {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                    APIResponse::HTTP_NOT_FOUND);

            }

            $responseContent = json_encode(UserUtils::getUserInfos($user));

            $em->remove($user);
            $em->flush();

            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }


}
