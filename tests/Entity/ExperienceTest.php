<?php

namespace App\Tests\Entity;

use App\Entity\Experience;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ExperienceTest extends TestCase
{
    public function testSetGetTitle(): void
    {
        $experience = new Experience();
        $title = 'Développeur Full Stack';

        $experience->setTitle($title);
        $this->assertEquals($title, $experience->getTitle());
    }

    public function testSetGetDateStart(): void
    {
        $experience = new Experience();
        $date = new \DateTimeImmutable();

        $experience->setDateStart($date);
        $this->assertSame($date, $experience->getDateStart());
    }

    public function testSetGetDateEnd(): void
    {
        $experience = new Experience();
        $date = new \DateTimeImmutable();

        $this->assertNull($experience->getDateEnd());

        $experience->setDateEnd($date);
        $this->assertSame($date, $experience->getDateEnd());
    }

    public function testSetGetBusiness(): void
    {
        $experience = new Experience();
        $business = 'Google';

        $this->assertNull($experience->getBusiness());

        $experience->setBusiness($business);
        $this->assertEquals($business, $experience->getBusiness());
    }

    public function testSetGetCity(): void
    {
        $experience = new Experience();
        $city = 'Mountain View';

        $this->assertNull($experience->getCity());

        $experience->setCity($city);
        $this->assertEquals($city, $experience->getCity());
    }

    public function testSetGetDescription(): void
    {
        $experience = new Experience();
        $description = 'Développement de nouvelles fonctionnalités.';

        $this->assertNull($experience->getDescription());

        $experience->setDescription($description);
        $this->assertEquals($description, $experience->getDescription());
    }

    public function testSetGetUser(): void
    {
        $experience = new Experience();
        $user = new User();

        $this->assertNull($experience->getUser());

        $experience->setUser($user);
        $this->assertSame($user, $experience->getUser());
    }
}
