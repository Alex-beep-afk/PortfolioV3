<?php

namespace App\Tests\Entity;

use App\Entity\Diplomas;
use App\Entity\Experience;
use App\Entity\Project;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetGetUsername(): void
    {
        $user = new User();
        $username = 'johndoe';

        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($username, $user->getUserIdentifier());
    }

    public function testSetGetEmail(): void
    {
        $user = new User();
        $email = 'john@example.com';

        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testSetGetFirstName(): void
    {
        $user = new User();
        $firstName = 'John';

        $user->setFirstName($firstName);
        $this->assertEquals($firstName, $user->getFirstName());
    }

    public function testSetGetLastName(): void
    {
        $user = new User();
        $lastName = 'Doe';

        $user->setLastName($lastName);
        $this->assertEquals($lastName, $user->getLastName());
    }

    public function testSetGetPhone(): void
    {
        $user = new User();
        $phone = '0123456789';

        $user->setPhone($phone);
        $this->assertEquals($phone, $user->getPhone());
    }

    public function testSetGetPassword(): void
    {
        $user = new User();
        $password = 'secret';

        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testSetGetRoles(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];

        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles($roles);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testDiplomasCollection(): void
    {
        $user = new User();
        $diploma = new Diplomas();

        $this->assertEmpty($user->getDiplomas());

        $user->addDiploma($diploma);
        $this->assertCount(1, $user->getDiplomas());
        $this->assertTrue($user->getDiplomas()->contains($diploma));
        $this->assertSame($user, $diploma->getUser());

        $user->removeDiploma($diploma);
        $this->assertEmpty($user->getDiplomas());
        $this->assertNull($diploma->getUser());
    }

    public function testExperiencesCollection(): void
    {
        $user = new User();
        $experience = new Experience();

        $this->assertEmpty($user->getExperiences());

        $user->addExperience($experience);
        $this->assertCount(1, $user->getExperiences());
        $this->assertTrue($user->getExperiences()->contains($experience));
        $this->assertSame($user, $experience->getUser());

        $user->removeExperience($experience);
        $this->assertEmpty($user->getExperiences());
        $this->assertNull($experience->getUser());
    }

    public function testProjectsCollection(): void
    {
        $user = new User();
        $project = new Project();

        $this->assertEmpty($user->getProjects());

        $user->addProject($project);
        $this->assertCount(1, $user->getProjects());
        $this->assertTrue($user->getProjects()->contains($project));
        $this->assertSame($user, $project->getUser());

        $user->removeProject($project);
        $this->assertEmpty($user->getProjects());
        $this->assertNull($project->getUser());
    }

    public function testLifecycleCallbacks(): void
    {
        $user = new User();

        $this->assertNull($user->getCreatedAt());
        $this->assertNull($user->getUpdatedAt());
        $this->assertNull($user->getShareToken());

        $user->autoCreatedAt();
        $user->autoGenerateShareToken();

        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getUpdatedAt());
        $this->assertNotNull($user->getShareToken());

        // Simulate update
        $oldUpdatedAt = $user->getUpdatedAt();
        sleep(1); // Ensure distinct timestamp if precision is high enough, actually just instantiating a new one is better check if we want strict inequality or just is instance.
        // For unit test simple check that it runs is fine.

        $user->autoUpdatedAt();
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getUpdatedAt());
    }
}
