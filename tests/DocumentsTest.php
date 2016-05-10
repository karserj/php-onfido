<?php

class DocumentsTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        \Onfido\Config::init()->setToken('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');
    }

    public function testUpload()
    {
        \Onfido\Config::init()->paginate(null, 5);

        $applicantId = $this->createApplicant();

        $document = new \Onfido\EndPoints\Document();

        $document->file_name = 'c.jpg';
        $document->file_path = __DIR__ . '/c.jpg';
        $document->file_type = 'image/jpg';
        $document->type = 'passport';
        $document->side = 'front';

        $response = $document->upload_for($applicantId);

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertAttributeNotEmpty('id', $response);
        $this->assertEquals($document->file_name, $response->file_name);
    }

    protected function createApplicant()
    {
        $random = time().rand(0, 999);

        $applicant = new \Onfido\EndPoints\Applicant();
        $applicant->first_name = 'John'.$random;
        $applicant->last_name = 'Smith';
        $applicant->email = 'email'.$random.'@server.com';

        $response = $applicant->create();

        return $response->id;
    }
}

