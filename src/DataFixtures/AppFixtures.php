<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 05/06/18
 * Time: 15:05
 */

namespace App\DataFixtures;

use App\Entity\Track;
use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new UserEntity();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setCreatedAt(new \Datetime('now'));
            $user->setUpdatedAt(new \Datetime('now'));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);

            for ($i = 0; $i < 10; $i++ ) {

                $track = new Track();
                $track
                    ->setOwner($user)
                    ->setTitle('track-' . $i)
                    ->setPlayedTimes(0)
                    ->setDowloadedTimes(0)
                    ->setLikes(0)
                    ->setLength(3600)
                    ->setExplicit(false)
                    ->setDownloadable(1)
                    ->setCreatedAt(new \DateTime('now'))
                    ->setUpdatedAt(new \DateTime('now'));

                $manager->persist($track);
            }
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
}