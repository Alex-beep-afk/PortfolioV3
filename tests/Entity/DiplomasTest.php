<?php

namespace App\Tests\Entity;

use App\Entity\Diplomas;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class DiplomasTest extends TestCase
{
    public function testSetGetDateStart(): void
    {
        $diploma = new Diplomas();
        $date = new \DateTimeImmutable();

        $diploma->setDateStart($date);
        $this->assertSame($date, $diploma->getDateStart());
    }

    public function testSetGetDateEnd(): void
    {
        $diploma = new Diplomas();
        $date = new \DateTimeImmutable();

        $this->assertNull($diploma->getDateEnd());

        $diploma->setDateEnd($date);
        $this->assertSame($date, $diploma->getDateEnd());
    }

    public function testSetGetTitle(): void
    {
        $diploma = new Diplomas();
        $title = 'Master Développeur Web';

        $diploma->setTitle($title);
        $this->assertSame($title, $diploma->getTitle());
    }

    public function testSetGetSchool(): void
    {
        $diploma = new Diplomas();
        $school = 'Harvard';

        $diploma->setSchool($school);
        $this->assertSame($school, $diploma->getSchool());
    }

    public function testSetGetCity(): void
    {
        $diploma = new Diplomas();
        $city = 'Paris';

        $this->assertNull($diploma->getCity());

        $diploma->setCity($city);
        $this->assertSame($city, $diploma->getCity());
    }

    public function testSetGetUser(): void
    {
        $diploma = new Diplomas();
        $user = new User();

        $this->assertNull($diploma->getUser());

        $diploma->setUser($user);
        $this->assertSame($user, $diploma->getUser());
    }
}
