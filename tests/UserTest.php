<?php

use App\Libraries\Encryption;
use App\Modules\Signup\Controllers\SignupController;
use App\Modules\Users\Models\Users;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    /** @test **/
    public function test_signup_form()
    {
        $response = $this->get('/signup/agency-name-availability');

        $response->assertResponseStatus(200);
    }

    // /** @test **/
    public function test_user_duplication()
    {
        $user1 = Users::create([
            'user_full_name' => 'John Doe',
            'user_email' => 'johndoe@gmail.com'
        ]);

        $user2 = Users::create([
            'user_full_name' => 'John Doe',
            'user_email' => 'dary1@gmail.com'
        ]);

        $this->assertTrue($user1->user_full_name != $user2->user_full_name);
    }

    // /** @test **/
    // public function test_it_stores_new_users()
    // {
    //     $response = $this->post('/signup/store', [
    //         'user_full_name' => 'Dary',
    //         'user_email' => 'dary@gmail.com',
    //         'password' => Hash::make('123456a@') 
    //     ]);

    //     $response->assertRedirectedTo('/notExists');
    // }


    


    public function testValidDataIsStored()
    {
        $formData = [
            'user_full_name' => 'ud',
            'user_type' => 'applicant',
            'user_nid' => '123456789012',
            'nationality' => 'Bangladeshi',
            'user_DOB' => '2021-11-08',
            'country' => 'Bangladesh',
            'user_mobile' => '01712312378',
            'designation' => 'Manager',
            'user_email' => 'ud@example.com',
            'current_address' => '123 Main St.',
            'permanent_address' => '456 Elm St.',
        ];

        $response = Users::create($formData);

        $response->assertRedirectedTo('/');

        $this->seeInDatabase('users', [
            'user_full_name' => $formData['user_full_name'],
            'user_type' => $formData['user_type'],
            'user_email' => $formData['user_email'],
            'designation' => $formData['designation'],
            'user_nid' => $formData['user_nid'],
        ]);

    }


    public function test_store_method_with_valid_data()
    {
        $this->withoutMiddleware();

        $userData = [
            'user_full_name' => 'ud',
            'user_email' => 'ud@example.com',
            'user_type' => 'applicant',
            'user_nid' => '31132113131',
            'nationality' => 'Bangladeshi',
            'user_DOB' => '10-Feb-1999',
            'country' => 'Bangladesh',
            'user_mobile' => '+8801712312378',
            'designation' => 'Manager',
            'current_address' => 'Mirpur 2, Dhaka',
            'permanent_address' => 'Mirpur 12, Dhaka',
        ];

        // $this->assertSessionHasErrors([
        //     'user_full_name', 'user_type', 'user_nid', 'country',
        //     'user_mobile', 'designation', 'user_email'
        // ]);

        // $this->assertSessionHasErrors([
        //     'user_full_name' => 'The Name is Required',
        //     'user_nid' => 'The user nid must be a valid.',
        //     'user_mobile' => 'The user mobile must be a valid.',
        //     'user_email' => 'The user email must be a valid email address.'
        // ]);

        $user = Users::create($userData);

        $user->assertRedirectedTo('/signup');
        
        $this->seeInDatabase('users', [
            'user_email' => $userData['user_email']
        ]);
    }

    // $response = $this->call('PATCH', '/signup/store', $formData);

    // $response->assertResponseStatus(302);

}

  

