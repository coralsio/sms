<?php

namespace Tests\Feature;

use Corals\Modules\SMS\Facades\SMS;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PhoneNumbersTest extends TestCase
{
    use DatabaseTransactions;

    protected $phone_number;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    public function test_phone_numbers_store()
    {
        $lists = SMS::getSMSLists();
        $list = array_rand($lists->toArray());
        $phone = fake()->unique()->phoneNumber();

        $response = $this->post('sms/phone-numbers', [
            'phone' => $phone,
            'list_id' => $list,
            'status' => 'active',
        ]);

        $this->phone_number = PhoneNumber::query()->where('list_id', $list)->first();

        $response->assertDontSee('The given data was invalid')
            ->assertRedirect('sms/phone-numbers');

        $this->assertDatabaseHas('sms_phone_numbers', [
            'id' => $this->phone_number->id,
            'list_id' => $this->phone_number->list_id,
        ]);
    }

    public function test_phone_numbers_edit()
    {
        $this->test_phone_numbers_store();
        if ($this->phone_number) {
            $response = $this->get('sms/phone-numbers/' . $this->phone_number->hashed_id . '/edit');

            $response->assertViewIs('SMS::phone_numbers.create_edit')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_phone_numbers_update()
    {
        $this->test_phone_numbers_store();

        if ($this->phone_number) {
            $response = $this->put('sms/phone-numbers/' . $this->phone_number->hashed_id, [
                'phone' => $this->phone_number->phone,
                'list_id' => $this->phone_number->list_id,
                'status' => 'inactive',
            ]);

            $response->assertRedirect('sms/phone-numbers');
            $this->assertDatabaseHas('sms_phone_numbers', [
                'id' => $this->phone_number->id,
                'phone' => $this->phone_number->phone,
                'list_id' => $this->phone_number->list_id,
                'status' => 'inactive',
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_phone_numbers_delete()
    {
        $this->test_phone_numbers_store();

        if ($this->phone_number) {
            $response = $this->delete('sms/phone-numbers/' . $this->phone_number->hashed_id);

            $response->assertStatus(200)->assertSeeText('Phone number has been deleted successfully.');

            $this->isSoftDeletableModel(PhoneNumber::class);
            $this->assertDatabaseMissing('sms_phone_numbers', [
                'id' => $this->phone_number->id,
                'phone' => $this->phone_number->phone,
                'list_id' => $this->phone_number->list_id,
            ]);
        }
        $this->assertTrue(true);
    }
}
