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
use App\Entity\TrackComment;
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

        $fictureUser = new UserEntity();
        $fictureUser->setFullName('John Fixture');
        $fictureUser->setUsername('J_Fixture');
        $fictureUser->setPassword($this->passwordEncoder->encodePassword($fictureUser, "kitten"));
        $fictureUser->setEmail('johnfixtures@symfony.com');
        $fictureUser->setCreatedAt(new \Datetime('now'));
        $fictureUser->setUpdatedAt(new \Datetime('now'));
        $fictureUser->setRoles(array('ROLE_USER'));

        $manager->persist($fictureUser);

        echo 'fixture user loaded';

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

            echo $username . ' loaded';


            $playlist = new Playlist();
            $playlist
                ->setOwner($user)
                ->setTitle('wonderfull playlist')
                ->setDescription('Hi, I\'m ' . $fullname . ' and this is ma wonderfull playlist');

            $path = 'http://localhost:8000/track/Recording%201.mp3';

            //$trackFile = new UploadedFile($path . 'Rone_Nakt.mp3', 'Rone_Nakt');

            for ($i = 0; $i < 10; $i++ ) {

                $track = new Track();
                $track
                    ->setOwner($user)
                    ->setTitle('track-' . $i)
                    ->setTrackUrl($path)
                    ->setCoverUrl('http://bobjames.com/wp-content/themes/soundcheck/images/default-album-artwork.png')
                    ->setGenre($this->getGenre())
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

                echo 'track_' .$i. ' loaded';


                // SET COMMENT FOR TRACK
                for ($j=0; $j < random_int(0, 4); $j++) {

                    $comment = new TrackComment();
                    $comment
                        ->setOwner($fictureUser)
                        ->setTrack($track)
                        ->setContent($this->getFixtureUserComment())
                        ->setCreatedAt(new \Datetime('now'));

                    $manager->persist($comment);
                    echo 'comment loaded';

                }

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

    private function getGenre(){

        $genres = array(
            'Musique Classique',
            'Electro',
            'Jazz',
            'R&B'
        );

        return $genres[random_int(0, sizeof($genres) - 1)];

    }

    private function getFixtureUserComment() {

        $comments = array(
            'Wow ! This song rocks !!',
            'To much bass for me :O Well played',
            'Can\'t stop listening this track !!!',
            'God Damn ... This is the worst song ever :/',
            'Sounds Good ! Thanks !',
            'Could you upload more of this ? I\'m serious ....',
            'What a good melodie, really nice !',
            'Sooooooo adictive !'
        );

        $random = random_int(0, sizeof($comments) - 1);

        return $comments[$random];
    }
}