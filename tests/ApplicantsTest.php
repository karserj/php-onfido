<?php

class ApplicantsTest extends PHPUnit_Framework_TestCase
{
    protected static $applicants;

    public static function setUpBeforeClass()
    {
        self::$applicants = null;
        \Onfido\Config::init()->setToken('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');
    }

    public static function tearDownAfterClass()
    {
        self::$applicants = null;
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

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('first_name', $response);
        $this->assertEquals($response->first_name, 'John'.$random);
    }

    public function testListAll()
    {
        \Onfido\Config::init()->paginate(null, 5);

        $applicants = (new \Onfido\EndPoints\Applicant())->get();
        $this->assertEquals(5, count($applicants));

        self::$applicants = $applicants;
    }

    public function testGet()
    {
        $applicant = (new \Onfido\EndPoints\Applicant())->get(self::$applicants[0]->id);
        $this->assertInstanceOf('stdClass', $applicant);
        $this->assertObjectHasAttribute('email', $applicant);
        $this->assertEquals(self::$applicants[0]->email, $applicant->email);
    }
    
    public function testAddress()
    {
        \Onfido\Config::init()->paginate(null, 10);

        $address = new \Onfido\EndPoints\AddressPicker();
        $address->postcode = 'SW4 6EH';
        $addresses = $address->pick();

        $this->assertGreaterThanOrEqual(1, count($addresses));
        $this->assertInstanceOf('stdClass', $addresses[0]);
        $this->assertObjectHasAttribute('country', $addresses[0]);
        $this->assertEquals($addresses[0]->country, 'GBR');
        $this->assertEquals($addresses[0]->town, 'LONDON');
    }

}

?>
