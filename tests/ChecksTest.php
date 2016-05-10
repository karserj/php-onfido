<?php

class ChecksTest extends PHPUnit_Framework_TestCase
{
    protected static $checks;
    protected static $applicantId = null;

    public static function setUpBeforeClass()
    {
        self::$checks = null;
        \Onfido\Config::init()->setToken('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');
    }

    public static function tearDownAfterClass()
    {
        self::$checks = null;
    }

    public function testCreate()
    {
        $random = time().rand(0, 999);

        $applicant = new \Onfido\EndPoints\Applicant();
        $applicant->first_name = 'John'.$random;
        $applicant->last_name = 'Smith';
        $applicant->email = 'email'.$random.'@server.com';

        $address1 = new \Onfido\EndPoints\Applicants\Address();
        $address1->postcode = 'abc';
        $address1->town = 'London';
        $address1->country = 'GBR';

        $applicant->addresses = Array($address1);

        $response = $applicant->create();

        self::$applicantId = $response->id;

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('first_name', $response);
        $this->assertEquals($response->first_name, 'John'.$random);

        $check = new \Onfido\EndPoints\Check();
        $check->type = 'standard';

        $report1 = new \Onfido\EndPoints\Check\CheckReport();
        $report1->name = 'identity';

        $check->reports = Array(
            $report1,
        );
        $response = $check->create_for($response->id);

        $this->assertInstanceOf('stdClass', $response);
    }

    public function testListAll()
    {
        \Onfido\Config::init()->paginate(null, 5);

        $checks = (new \Onfido\EndPoints\Check())->get(self::$applicantId);
        $this->assertLessThanOrEqual(5, count($checks));

        self::$checks = $checks;
    }

    public function testGet()
    {
        $check = (new \Onfido\EndPoints\Check())->get(self::$applicantId, self::$checks[0]->id);
        $this->assertInstanceOf('stdClass', $check);
        $this->assertObjectHasAttribute('id', $check);
        $this->assertEquals(self::$checks[0]->id, $check->id);
    }

}
