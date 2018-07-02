<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 05/06/18
 * Time: 15:05
 */

namespace App\DataFixtures;

use App\Entity\Playlist;
use App\Entity\Track;
use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

            $playlist = new Playlist();
            $playlist
                ->setOwner($user)
                ->setTitle('wonderfull playlist')
                ->setDescription('Hi, I\'m ' . $fullname . ' and this is ma wonderfull playlist');

            $path = '/home/clement/www/symfony/soundbuzzAPI/src/DataFixtures/';

            $trackFile = new UploadedFile($path . 'Rone_Nakt.mp3', 'Rone_Nakt');

            for ($i = 0; $i < 10; $i++ ) {

                $track = new Track();
                $track
                    ->setOwner($user)
                    ->setTitle('track-' . $i)
                    ->setTrack($trackFile)
                    ->setPlayedTimes(random_int(0, 1000))
                    ->setDowloadedTimes(random_int(0, 1000))
                    ->setLikes(random_int(0, 500))
                    ->setLength(3600)
                    ->setExplicit(false)
                    ->setDownloadable(1)
                    ->setCreatedAt(new \DateTime('now'))
                    ->setUpdatedAt(new \DateTime('now'))
                    ->setValidated(false);

                $manager->persist($track);

                // Add track to playlist

                $playlist->addTrack($track);
            }

            $manager->persist($playlist);
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