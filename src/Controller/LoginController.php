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
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(UserEntity::class)->findByUsernameOrEmail($req->get('username'))[0];

        if (!$user) {

            return APIResponse::createResponse(
                APIResponse::getErrorResponseContent(APIResponse::HTTP_NOT_FOUND),
                APIResponse::HTTP_NOT_FOUND
            );
        }

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
}
