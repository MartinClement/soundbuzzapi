<?php

namespace App\Controller;


use App\Entity\UserEntity;
use App\Utils\APIResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

            $_data['users'][] = $this->_getUserInfos($user);
        }

        $_data = json_encode($_data);

        return APIResponse::createResponse($_data, APIResponse::HTTP_OK);

    }

    public function getUserById($id) {

        $user = $this->getDoctrine()
            ->getRepository(UserEntity::class)
            ->find($id);

        if(!$user) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND);

        }

        $responseContent = json_encode($this->_getUserInfos($user));
        return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);


    }

    // POST

    public function addUser(Request $request) {

        if ($request->isMethod(Request::METHOD_POST)) {


            $em = $this->getDoctrine()->getManager();

            $user = new UserEntity();
            $user->setEmail($request->get('email'));
            $user->setFullName($request->get('fullname'));
            $user->setUsername($request->get('username'));
            $user->setRoles(array('ROLE_USER'));
            $user->setCreatedAt(new \Datetime('now'));
            $user->setUpdatedAt(new \Datetime('now'));

            $userPassword = $this->passwordEncoder->encodePassword($user, $request->get('password'));
            $user->setPassword($userPassword);

            $em->persist($user);
            $em->flush();


            $responseContent = json_encode($this->_getUserInfos($user));
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

            $responseContent = json_encode($this->_getUserInfos($user));
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

                $responseContent = json_encode($this->_getUserInfos($user));
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

            $responseContent = json_encode($this->_getUserInfos($user));

            $em->remove($user);
            $em->flush();

            return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);
        }

        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

//
// PRIVATE FUNCTIONS
//

    private function _getUserInfos(UserEntity $user) {

        return array(
            'id' => $user->getID(),
            'email' => $user->getEmail(),
            'fullname' => $user->getFullName(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        );
    }
}
