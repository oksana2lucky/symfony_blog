<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const EDITOR_USER_REFERENCE = 'editor-user';
    public const USER_REFERENCE = 'user';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
       $this->passwordEncoder = $passwordEncoder;
    }

    public static function getUserReferenceKey($role, $i)
    {
        $reference = null;
        if ($role == 'ROLE_EDITOR') {
            $reference = self::EDITOR_USER_REFERENCE. $i;
        } elseif($role == 'ROLE_USER') {
            $reference = self::USER_REFERENCE. $i;
        }
        return $reference;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@symfony-blog.loc');
        $userAdmin->setPassword($this->passwordEncoder->encodePassword($userAdmin, 'q2w3e4r11'));
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setFirstName('Admin');
        $manager->persist($userAdmin);
        $manager->flush();
        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);

        $users = [
            ['role' => 'ROLE_EDITOR', 'username' => 'rwalker', 'email' => 'rwalker@symfony-blog.loc', 'password' => 'q2w3e4r11', 'first_name' => 'Ryan', 'last_name' => 'Walker'],
            ['role' => 'ROLE_EDITOR', 'username' => 'ssimpson', 'email' => 'ssimpson@symfony-blog.loc', 'password' => 'q2w3e4r11', 'first_name' => 'Sarah', 'last_name' => 'Simpson'],
            ['role' => 'ROLE_EDITOR', 'username' => 'jstew', 'email' => 'johnstew@symfony-blog.loc', 'password' => 'q2w3e4r11', 'first_name' => 'John', 'last_name' => 'Stew'],
            ['role' => 'ROLE_USER', 'username' => 'jander', 'email' => 'jander@smth.com', 'password' => 'q2w3e4r11', 'first_name' => 'John', 'last_name' => 'Anderson'],
            ['role' => 'ROLE_USER', 'username' => 'mich', 'email' => 'mich@smth.com', 'password' => 'q2w3e4r11', 'first_name' => 'Michelle', 'last_name' => 'Johnson'],
            ['role' => 'ROLE_USER', 'username' => 'sam', 'email' => 'sam@smth.com', 'password' => 'q2w3e4r11', 'first_name' => 'Samanta', 'last_name' => 'Thompson'],
            ['role' => 'ROLE_USER', 'username' => 'nick', 'email' => 'nick@smth.com', 'password' => 'q2w3e4r11', 'first_name' => 'Nick', 'last_name' => ''],
            ['role' => 'ROLE_USER', 'username' => 'edward', 'email' => 'edward@smth.com', 'password' => 'q2w3e4r11', 'first_name' => 'Edward', 'last_name' => ''],
        ];

        $counter = ['ROLE_EDITOR' => 0, 'ROLE_USER' => 0];
        foreach($users as $i => $dataUser) {
            $user = new User();
            $user->setUsername($dataUser['username']);
            $user->setEmail($dataUser['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $dataUser['password']));
            $user->setRoles([$dataUser['role']]);
            $user->setFirstName($dataUser['first_name']);
            $user->setLastName($dataUser['last_name']);
            $manager->persist($user);
            $manager->flush();
            $counter[$dataUser['role']]++;
            $this->addReference(self::getUserReferenceKey($dataUser['role'], $counter[$dataUser['role']]), $user);
        }
    }
}
