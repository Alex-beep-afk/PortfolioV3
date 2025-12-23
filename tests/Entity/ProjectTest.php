<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Entity\Techno;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testSetGetCustomerName(): void
    {
        $project = new Project();
        $name = 'Client Important';

        $this->assertNull($project->getCustomerName());

        $project->setCustomerName($name);
        $this->assertEquals($name, $project->getCustomerName());
    }

    public function testSetGetDificulties(): void
    {
        $project = new Project();
        $dificulties = 'Complexité élevée';

        $this->assertNull($project->getDificulties());

        $project->setDificulties($dificulties);
        $this->assertEquals($dificulties, $project->getDificulties());
    }

    public function testSetGetDescription(): void
    {
        $project = new Project();
        $description = 'Description du projet';

        $this->assertNull($project->getDescription());

        $project->setDescription($description);
        $this->assertEquals($description, $project->getDescription());
    }

    public function testSetGetLinkToProject(): void
    {
        $project = new Project();
        $link = 'https://example.com';

        $this->assertNull($project->getLinkToProject());

        $project->setLinkToProject($link);
        $this->assertEquals($link, $project->getLinkToProject());
    }

    public function testAddRemoveTechno(): void
    {
        $project = new Project();
        $techno = new Techno();

        $this->assertEmpty($project->getTechnos());

        $project->addTechno($techno);
        $this->assertCount(1, $project->getTechnos());
        $this->assertTrue($project->getTechnos()->contains($techno));

        $project->removeTechno($techno);
        $this->assertEmpty($project->getTechnos());
        $this->assertFalse($project->getTechnos()->contains($techno));
    }

    public function testSetGetUser(): void
    {
        $project = new Project();
        $user = new User();

        $this->assertNull($project->getUser());

        $project->setUser($user);
        $this->assertSame($user, $project->getUser());
    }
}
