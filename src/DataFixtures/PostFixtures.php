<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Post;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_REFERENCE = 'post';

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++) {
            $post = new Post();
            $post->setTitle('Title '. $i);
            $post->setDescription('Description '. $i);
            $post->setBody('Body '. $i);
            $timestamp = rand( strtotime('Sep 01 2020'), strtotime('Nov 01 2020') );
            $randomDate = date('d.m.Y', $timestamp );
            $post->setCreatedAt(new \DateTime($randomDate));
            $post->setUser($this->getReference(UserFixtures::getUserReferenceKey('ROLE_EDITOR',$i % 3 + 1)));
            $manager->persist($post);
            $manager->flush();
            $this->addReference(self::POST_REFERENCE . $i, $post);
        }
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
