<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Comment;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 50; $i++) {
            $comment = new Comment();
            $comment->setText('Text '. $i);
            $post = $this->getReference(PostFixtures::POST_REFERENCE . rand(1, 10));
            $comment->setPost($post);
            $comment->setUser($this->getReference(UserFixtures::getUserReferenceKey('ROLE_USER',$i % 5 + 1)));
            $timestamp = rand(strtotime($post->getCreatedAt()->format('Y-m-d H:i:s')), strtotime('Nov 01 2020') );
            $randomDate = date('d.m.Y', $timestamp );
            $comment->setCreatedAt(new \DateTime($randomDate));
            $manager->persist($comment);
            $manager->flush();
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [PostFixtures::class];
    }
}
