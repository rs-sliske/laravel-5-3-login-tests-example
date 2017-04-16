<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_log_a_user_in_given_correct_credentials(){
    	//get a test user
    	$user = factory(User::class)->create(['password' => bcrypt('password')]);

    	// attempt to log in
    	$this->visit('/login')
    		->type($user->email, 'email')
    		->type('password', 'password')
    		->press('Login')
    		//check we were redirected
    		->seePageIs('/home')
    		//check users name is on the page
    		->see($user->name);

    	// check we are logged in
    	$this->assertTrue(auth()->check());
    	// check the logged in users id matches our test user
    	$this->assertEquals($user->id, auth()->id());
    }

    /** @test */
    public function it_fails_to_log_a_user_in_when_given_invalid_credentials(){
    	//get a test user
    	$user = factory(User::class)->create(['password' => bcrypt('password')]);

    	// attempt to log in
    	$this->visit('/login')
    		->type($user->email, 'email')
    		->type('wrong-password', 'password')
    		->press('Login')
    		//check we were redirected back to login page
    		->seePageIs('/login');

		// check we are logged in
    	$this->assertFalse(auth()->check());
    }


    
}
