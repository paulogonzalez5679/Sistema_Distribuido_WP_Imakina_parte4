context('Demo MasterStudy', () => {

	let url = 'https://stylemixthemes.com/masterstudy/plugins/masterstudy-4.1.0.zip';
	let token = 'Z8AX97e761ZuGtn5TOmlHO2CeRhC1apu'
	//let url = 'https://stylemixthemes.com/masterstudy/plugins/wp-zoom-addon.zip';


	before(() => {
		/*Install MS from zip*/
		cy.installTheme(`${url} --force --activate`);
		//cy.exec(`node_modules/.bin/wp-cypress wp "theme install ${url} --activate"`);

	})

	it('install', () => {

		cy.visit('/wp-admin/admin.php?page=stm-admin')

		cy.get('[name="stm_registration[token]"]').type(token)
		cy.get('input[type="submit"]').click()
		cy.get('a').contains('Install Demos').click()
		cy.get('[data-layout="online-light"]').click({force: true})
		cy.get('[data-builder="js_composer"]').click()
		cy.get('.stm_install__demo_start').click()

	})

	after(() => {
		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?demo_template=online-light&builder=js_composer&action=stm_demo_import_content',
		}).as('importDemo')

		cy.wait('@importDemo', {timeout : 300000}).should((xhr) => {
			assert.isNotNull(xhr.response.body.url, 'Import response URL is not null')
		})

	})

})