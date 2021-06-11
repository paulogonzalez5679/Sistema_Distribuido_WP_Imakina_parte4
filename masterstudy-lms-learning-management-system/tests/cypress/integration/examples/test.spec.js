context('Setup', () => {

	it('stuff', () => {

		let phrase = 'TEST!TEST!TEST!'

		cy.exec(`node_modules/.bin/wp-cypress wp "plugin install classic-editor"`);
		cy.exec(`node_modules/.bin/wp-cypress wp "plugin activate classic-editor"`);

		cy.visit('/wp-admin/post.php?post=1&action=edit')

		cy.get('[name="post_title"]').clear().type(phrase)

		cy.get('#publish').click();

		cy.get('#sample-permalink').click()

		cy.get('h1.entry-title').contains(phrase)

	})

})