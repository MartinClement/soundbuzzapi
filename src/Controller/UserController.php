<?php

namespace App\Controller;


use App\Entity\UserEntity as User;
use App\Utils\APIResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    // GET

    public function getUsers() {

        $users = $this->getDoctrine()
            ->getRepository(User::class)
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
            ->getRepository(User::class)
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

            if ($content = $request->getContent()) {

                $content = json_decode($content, true);
                $em = $this->getDoctrine()->getManager();

                $user = new UserEntity();
                $user->setEmail($content['email']);
                $user->setFullName($content['fullname']);
                $user->setUsername($content['username']);
                $user->setPassword($content['password']);

                $em->persist($user);
                $em->flush();


                $responseContent = json_encode($this->_getUserInfos($user));
                return APIResponse::createResponse($responseContent, APIResponse::HTTP_CREATED);

            } else {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NO_CONTENT), APIResponse::HTTP_NO_CONTENT);
            }
        }


        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    // PUT

    public function updateUser(Request $request, $id) {

        if ($request->isMethod(Request::METHOD_PUT)) {

            if ($content = $request->getContent()) {

                $content = json_decode($content, true);
                $em = $this->getDoctrine()->getManager();

                $user = $em->getRepository(User::class)->find($id);
                $user->setEmail($content['email']);
                $user->setFullName($content['fullname']);
                $user->setUsername($content['username']);
                $user->setRoles($content['roles']);

                $em->persist($user);
                $em->flush();

                $responseContent = json_encode($this->_getUserInfos($user));
                return APIResponse::createResponse($responseContent, APIResponse::HTTP_OK);

            } else {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NO_CONTENT), APIResponse::HTTP_NO_CONTENT);
            }
        }


        return APIResponse::create(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST);
    }

    // DELETE

    public function deleteUser(Request $request, $id) {

        if ($request->isMethod(Request::METHOD_DELETE)) {


            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->find($id);

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

    private function _getUserInfos(User $user) {

        return array(
            'id' => $user->getID(),
            'email' => $user->getEmail(),
            'fullname' => $user->getFullName(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        );
    }
}
