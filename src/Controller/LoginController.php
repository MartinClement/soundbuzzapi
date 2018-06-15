<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Utils\APIResponse;
use App\Utils\UserUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends Controller
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    // LOGIN

    public function login(Request $req)
    {

        if ($req->getMethod() === Request::METHOD_POST) {
            $em = $this->getDoctrine()->getManager();

            $users = $em->getRepository(UserEntity::class)->findByUsernameOrEmail($req->get('username'));

            if (!$users) {

                return APIResponse::createResponse(
                    APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                    APIResponse::HTTP_NOT_FOUND
                );
            }

            $user = $users[0];
            $password = $req->get('password');

            // check password validity
            if ($this->passwordEncoder->isPasswordValid($user, $password)) {

                $userInfos = json_encode(UserUtils::getUserInfos($user));
                return APIResponse::createResponse($userInfos, APIResponse::HTTP_OK);

            }

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_FORBIDDEN),
                APIResponse::HTTP_FORBIDDEN
            );
        }

        return APIResponse::createResponse(
            APIResponse::getErrorResponseContent(APIResponse::HTTP_BAD_REQUEST),
            APIResponse::HTTP_BAD_REQUEST
        );

    }
}
