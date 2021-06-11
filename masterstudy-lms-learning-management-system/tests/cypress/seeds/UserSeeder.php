<?php

use WP_Cypress\Seeder\Seeder;
use WP_Cypress\Fixtures;

class UserSeeder extends Seeder {
	public function run() {

		$user = new Fixtures\User( [
			'role'         => 'admin',
			'user_login'   => 'exampleuser',
			'user_pass'    => 'password123',
			'display_name' => 'Tim',
			'first_name'   => 'Sab',
		] );

		$user->create( 1 );
	}

}

