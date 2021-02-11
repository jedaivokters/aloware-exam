<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Faker;

class CheckinTest extends TestCase {

	private $questionnaire;
	private $faker;
	private $otherForm;
	private $questionnaireMappedIds;
	private $companionOtherForm;
	private $questionnaireMappedSystemName;

	protected function setUp(): void {
		parent::setUp(); // TODO: Change the autogenerated stub

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


	public function testCheckinSuccessful() {

		$faker = Faker\Factory::create();
		$password = 'secret';

		$postData = [
			'visitor_id'   => null,
			'name'         => $this->faker->name,
			'email'        => $this->faker->email,
			'password'     => $password,
			'first_name'   => $this->faker->firstName,
			'last_name'    => $this->faker->lastName,
			'address'      => $this->faker->address,
			'phone_number' => $this->faker->phoneNumber,
			'nationality'  => 'JP',
			'facility_id'  => \App\Facility::first()['id'],
			'timezone'     => 'Asia/Manila',
			'other_form'   => $this->otherForm,
			'companion'    => $this->companionOtherForm,
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

	}


}
