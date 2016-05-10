<?php

class ReportsTest extends \PHPUnit_Framework_TestCase
{
    protected static $reports;

    protected static $checkId;

    public static function setUpBeforeClass()
    {
        self::$reports = null;
        \Onfido\Config::init()->setToken('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');
    }

    public static function tearDownAfterClass()
    {
        self::$reports = null;
    }

    public function testListAll()
    {
        \Onfido\Config::init()->paginate(null, 5);

        $this->createCheck();

        $reports = (new \Onfido\EndPoints\Report())->get(self::$checkId);
        $this->assertLessThanOrEqual(5, count($reports));

        self::$reports = $reports;
    }

    public function testGet()
    {
        $this->createCheck();

        $report = (new \Onfido\EndPoints\Report())->get(self::$checkId, self::$reports[0]->id);
        $this->assertInstanceOf('stdClass', $report);
        $this->assertObjectHasAttribute('id', $report);
        $this->assertEquals(self::$reports[0]->id, $report->id);
    }

    public function createCheck()
    {
        if (self::$checkId) {
            return;
        }

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

        $check = new \Onfido\EndPoints\Check();
        $check->type = 'standard';

        $report1 = new \Onfido\EndPoints\Check\CheckReport();
        $report1->name = 'identity';

        $check->reports = Array(
            $report1,
        );
        $response = $check->create_for($response->id);
        self::$checkId = $response->id;
    }
}
