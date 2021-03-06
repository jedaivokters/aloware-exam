<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker;
class LogTest extends TestCase
{

	private $questionnaire;
	private $faker;
	private $otherForm;
	private $questionnaireMappedIds;
	private $companionOtherForm;
	private $questionnaireMappedSystemName;

	protected function setUp(): void {
		parent::setUp();

		//Get 1 set of questionnaire only
		$this->questionnaire = \App\FacilityQuestionnaire::
			whereLanguage('en')
			->whereFacilityId(null)
			->get();

		$this->faker = Faker\Factory::create();
		$generatedOtherForm = $this->generateOtherForm();
		$this->otherForm = $generatedOtherForm['form'];
		$this->companionOtherForm = [
			'nationality' => 'JP',
			'other_forms' => $this->otherForm,
		];

		//These mappings are commonly used to simplify/automate assertion of values in arrays
		$this->questionnaireMappedIds = $generatedOtherForm['keys'];
		$this->questionnaireMappedSystemName = $generatedOtherForm['systemNameMap'];
	}


	/**
	 * Generate a complete other form dummy data
	 * @return array
	 */
	private function generateOtherForm() {
		$otherForm = [];
		$keys = [];
		$systemName = [];

		foreach ($this->questionnaire as $question) {
			$otherForm[] = ['value' => $this->faker->words(3, true)];
			$keys[] = $question->id;
			$systemName[] = $question->system_name;

		}
		$values = array_column($otherForm, 'value');

		//create a map of keys of the other_forms so that [0] = [key of questionnaire]
		$keyMap = [];
		foreach($keys as $key){
			$keyMap[] = $key;
		}

		//create a map of keys of the other_forms so that [0] = [questionnaire system name]
		$systemNameMap = [];
		foreach($systemName as $key => $name){
			$systemNameMap[$name] = $keyMap[$key];
		}

		return [
			'form' => array_combine($keys, $values),
			'keys' => $keyMap,
			'systemNameMap' => $systemNameMap,
		];

	}

	/**
	 * Test if visitors logging is being inserted correctly to db table
	 */
	public function testGetFromVisitorsLogTable() {
		$password = 'secret';

		$postData = [
			'visitor_id'  => null,
			'name'        => $this->faker->name,
			'email'       => $this->faker->email,
			'password'    => $password,
			'facility_id' => \App\Facility::first()['id'],
			'timezone'    => 'Asia/Manila',
			'other_form'  => $this->otherForm,
			'companion'   => $this->companionOtherForm,
		];

		$response = $this->call('POST', 'api/checkin', $postData);
		$returnData = json_decode($response->getContent());
		$response->assertStatus(200);

		$this->assertObjectHasAttribute('status', $returnData);
		$this->assertObjectHasAttribute('user', $returnData->status);
		$this->assertObjectHasAttribute('visitor', $returnData->status);
		$this->assertObjectHasAttribute('logFacilityVisitor', $returnData->status);
		$this->assertObjectHasAttribute('facilityVisitorOtherForm', $returnData->status);
		$this->assertObjectHasAttribute('facilityVisitorCompanion', $returnData->status);

		$this->assertTrue($returnData->status->user);
		$this->assertTrue($returnData->status->visitor->success);
		$this->assertTrue($returnData->status->logFacilityVisitor->success);
		$this->assertTrue($returnData->status->facilityVisitorOtherForm);
		$this->assertTrue($returnData->status->facilityVisitorCompanion);

		//Test if last inserted data in VISITORS TABLE is null
		$lastVisitor = \App\Visitor::latest()->first();
		$this->assertNull($lastVisitor->firstName);
		$this->assertNull($lastVisitor->lastName);
		$this->assertNull($lastVisitor->address);
		$this->assertNull($lastVisitor->phoneNumber);
		$this->assertNull($lastVisitor->nationality);

		//Test if last inserted data in VISITORS LOG TABLE is same with post data
		$lastVisitorLog = \App\FacilityVisitorOtherFormLog::whereFacilityVisitorLogId($returnData->status->logFacilityVisitor->id)
			->get();

		//Assert that post data other_form was correctly saved in database
		foreach ($lastVisitorLog as $key => $log){
			$this->assertEquals($log->value, $postData['other_form'][$this->questionnaireMappedIds[$key]] );
		}

	}

	/**
	 * Test if data being generated by spreadsheet is valid
	 */
	public function testGeneratedCheckinLogsValid() {

		//Insert a record so that it'll be tested below
		$password = 'secret';
		$facilityId = \App\Facility::first()['id'];
		$postData = [
			'visitor_id'  => null,
			'name'        => $this->faker->name,
			'email'       => $this->faker->email,
			'password'    => $password,
			'facility_id' => $facilityId,
			'timezone'    => 'Asia/Manila',
			'other_form'  => $this->otherForm,
			'companion'   => $this->companionOtherForm,
		];

		$response = $this->call('POST', 'api/checkin', $postData);
		$response->assertStatus(200);

		//Call logs endpoint to check its output
		$response = $this->call('POST', 'api/logs/checkin', [
			'facility_id' => $facilityId,
			'get_data_only' => 1
		]);
		$response->assertStatus(200);
		$returnData = json_decode($response->getContent(), true);

		//Validate output based on post data above
		$latestLog = $returnData['data'][0];

		$this->assertEquals($latestLog['first_name'], $this->otherForm[$this->questionnaireMappedSystemName['first_name']]);
		$this->assertEquals($latestLog['last_name'], $this->otherForm[$this->questionnaireMappedSystemName['last_name']]);
		$this->assertEquals($latestLog['address'], $this->otherForm[$this->questionnaireMappedSystemName['address']]);
		$this->assertEquals($latestLog['phone'], $this->otherForm[$this->questionnaireMappedSystemName['phone']]);
		$this->assertEquals($latestLog['nationality'], $this->otherForm[$this->questionnaireMappedSystemName['nationality']]);
		$this->assertEquals($latestLog['email'], $postData['email']);
		$this->assertEquals($latestLog['age'], $this->otherForm[$this->questionnaireMappedSystemName['age']]);
		$this->assertEquals($latestLog['dob'], $this->otherForm[$this->questionnaireMappedSystemName['dob']]);
		$this->assertEquals($latestLog['gender'], $this->otherForm[$this->questionnaireMappedSystemName['gender']]);
		$this->assertEquals($latestLog['occupancy'], $this->otherForm[$this->questionnaireMappedSystemName['occupancy']]);
		$this->assertEquals($latestLog['gov_id'], $this->otherForm[$this->questionnaireMappedSystemName['gov_id']]);
		$this->assertEquals($latestLog['gov_id_number'], $this->otherForm[$this->questionnaireMappedSystemName['gov_id_number']]);
		$this->assertEquals($latestLog['body_temperature'], $this->otherForm[$this->questionnaireMappedSystemName['body_temperature']]);
		$this->assertEquals($latestLog['coughing'], $this->otherForm[$this->questionnaireMappedSystemName['coughing']]);
		$this->assertEquals($latestLog['doctor_certificate'], $this->otherForm[$this->questionnaireMappedSystemName['doctor_certificate']]);
		$this->assertEquals($latestLog['pass_affection'], $this->otherForm[$this->questionnaireMappedSystemName['pass_affection']]);
		$this->assertEquals($latestLog['prev_destination'], $this->otherForm[$this->questionnaireMappedSystemName['prev_destination']]);
		$this->assertEquals($latestLog['next_destination'], $this->otherForm[$this->questionnaireMappedSystemName['next_destination']]);
		$this->assertEquals($latestLog['transportation'], $this->otherForm[$this->questionnaireMappedSystemName['transportation']]);
		$this->assertEquals($latestLog['internation_travel'], $this->otherForm[$this->questionnaireMappedSystemName['internation_travel']]);
		$this->assertEquals($latestLog['domestic_travel'], $this->otherForm[$this->questionnaireMappedSystemName['domestic_travel']]);
		$this->assertEquals($latestLog['foreign_travel'], $this->otherForm[$this->questionnaireMappedSystemName['foreign_travel']]);
		$this->assertEquals($latestLog['close_contact_corona_patient'], $this->otherForm[$this->questionnaireMappedSystemName['close_contact_corona_patient']]);
		$this->assertEquals($latestLog['relationship_with_main_visitor'], $this->otherForm[$this->questionnaireMappedSystemName['relationship_with_main_visitor']]);
		$this->assertEquals($latestLog['reservation_no'], $this->otherForm[$this->questionnaireMappedSystemName['reservation_no']]);

	}

	public function testGetFacilityVisitorLogFromApi() {

		$postData = [
			'visitor_id'  => null,
			'name'        => $this->faker->name,
			'email'       => $this->faker->email,
			'password'    => 'secret',
			'facility_id' => \App\Facility::first()['id'],
			'timezone'    => 'Asia/Manila',
			'other_form'  => $this->otherForm,
			'companion'   => $this->companionOtherForm,
		];

		$response = $this->call('POST', 'api/checkin', $postData);
		$response->assertStatus(200);

		//Get a user
		$user = \App\User::first();

		//Login
		$response = $this->call('POST', 'api/auth/login', [
			'email' => $user->email,
			'password' => 'secret'
		]);
		$response->assertStatus(200);

		$returnData = json_decode($response->getContent(), true);
		$token = $returnData['token'];

		//Get logs
		$response = $this->call('POST', 'api/log/get/facility-visitor?token=' . $token,[
			'paginate' => true,
			'facility_id' => 1,
			'per_page' => 10,
			'page' => 1
		]);
		$response->assertStatus(200);

		$returnData = json_decode($response->getContent(), true);

		$latestLog = $returnData['data']['data'][0];

		$this->assertArrayHasKey('first_page_url', $returnData['data']);
		$this->assertArrayHasKey('from', $returnData['data']);
		$this->assertArrayHasKey('last_page', $returnData['data']);
		$this->assertArrayHasKey('last_page_url', $returnData['data']);
		$this->assertArrayHasKey('next_page_url', $returnData['data']);
		$this->assertArrayHasKey('path', $returnData['data']);
		$this->assertArrayHasKey('per_page', $returnData['data']);
		$this->assertArrayHasKey('to', $returnData['data']);

		//Assert that results are valid
		$this->assertArrayHasKey('visitor', $latestLog);
		$this->assertArrayHasKey('visitor_id', $latestLog);
		$this->assertArrayHasKey('facility_id', $latestLog);
		$this->assertArrayHasKey('timezone', $latestLog);
		$this->assertArrayHasKey('created_at', $latestLog);
		$this->assertArrayHasKey('updated_at', $latestLog);
		$this->assertArrayHasKey('user', $latestLog['visitor']);

		//Assert that latest added data to logs (that is also in form data) is equals to the output of facility visitor log api
		$this->assertEquals($latestLog['visitor']['first_name'],
			$this->otherForm[$this->questionnaireMappedSystemName['first_name']]);
		$this->assertEquals($latestLog['visitor']['last_name'],
			$this->otherForm[$this->questionnaireMappedSystemName['last_name']]);
		$this->assertEquals($latestLog['visitor']['address'],
			$this->otherForm[$this->questionnaireMappedSystemName['address']]);
		$this->assertEquals($latestLog['visitor']['phone_number'],
			$this->otherForm[$this->questionnaireMappedSystemName['phone']]);
		$this->assertEquals($latestLog['visitor']['nationality'],
			$this->otherForm[$this->questionnaireMappedSystemName['nationality']]);

	}
}
