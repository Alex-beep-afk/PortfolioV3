<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Entity\Techno;
use PHPUnit\Framework\TestCase;

class TechnoTest extends TestCase
{
    public function testGetName(): void
    {
        $techno = new Techno();
        $name = 'Symfony';

        $techno->setName($name);
        $this->assertEquals($name, $techno->getName());
    }

    public function testAddRemoveProject(): void
    {
        $techno = new Techno();
        $project = new Project();

        $this->assertEmpty($techno->getProjects());

        $techno->addProject($project);
        $this->assertCount(1, $techno->getProjects());
        $this->assertTrue($techno->getProjects()->contains($project));

        $techno->removeProject($project);
        $this->assertEmpty($techno->getProjects());
        $this->assertFalse($techno->getProjects()->contains($project));
    }
}
