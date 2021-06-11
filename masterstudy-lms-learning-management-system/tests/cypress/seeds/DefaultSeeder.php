<?php

use WP_Cypress\Seeder\Seeder;

class DefaultSeeder extends Seeder {
	public function run() {
		$this->call([
			'UserSeeder',
		]);
	}
}

