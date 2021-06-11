context('Paginated quiz check', () => {

	let quiz_id = 0;
	let ms_url = 'https://stylemixthemes.com/masterstudy/plugins/masterstudy-lms-learning-management-system-quiz_paginated_2.zip';

	before(() => {
		cy.exec(`node_modules/.bin/wp-cypress wp "rewrite structure '/%postname%/' --hard"`);
		cy.exec(`node_modules/.bin/wp-cypress wp "db query 'TRUNCATE wp_stm_lms_user_quizzes'"`, {failOnNonZeroExit: false});
		cy.exec(`node_modules/.bin/wp-cypress wp "db query 'TRUNCATE wp_stm_lms_user_answers'"`, {failOnNonZeroExit: false});
	})

	it('Install Plugins', () => {

		cy.exec(`node_modules/.bin/wp-cypress wp "plugin delete masterstudy-lms-learning-management-system"`, {failOnNonZeroExit : false});
		cy.exec(`node_modules/.bin/wp-cypress wp "plugin install classic-editor --activate"`);
		cy.exec(`node_modules/.bin/wp-cypress wp "plugin install ${ms_url} --force --activate"`, {
			timeout : 300000
		});

		cy.visit('/wp-admin/plugins.php')

		cy.get('.notice-lms-update-db a.button-primary').click()

		cy.wait(4000)

	})

	it('Create Test Course and Quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuiz')

		cy.visit('/wp-admin/post-new.php?post_type=stm-courses')

		cy.get('[name="post_title"]').clear().type('Test Course')
		cy.get('.add_item_nav_title.quizzes').click()
		cy.get('.add_item.stm-quizzes .add_item_input input').clear().type('Paginated quiz{enter}')

		cy.wait('@createQuiz').should((xhr) => {
			quiz_id = xhr.response.body.id;
			assert.isNotNull(quiz_id, 'Create quiz response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create quiz response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create quiz response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-quizzes', 'Create quiz response Edit link is stm-quizzes')
		})

		cy.get('#publish').click()


	})

	it('Navigate to quiz', () => {
		cy.visit(`/wp-admin/post.php?post=${quiz_id}&action=edit`)

		cy.get('#section_quiz_settings-quiz_style label').contains('Paginated').click();
	})

	it('Add simple question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')

		/*Creating simple question*/
		cy.get('.stm_lms_add_new_question .question_types_input input').type('Single choice Question{enter}')
		cy.wait('@createQuestion').should((xhr) => {
			assert.isNotNull(xhr.response.body.id, 'Create question response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create question response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create question response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-questions', 'Create question response Edit link is stm-questions')
		})

		cy.wait(2000)
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Correct Answer{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Incorrect Answer 1{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Incorrect Answer 2{enter}')

		cy.wait(2000)
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').first().find('label').click()


	})

	it('Add multiply question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')

		/*Creating multi question*/
		cy.get('.add_items .question_type').contains('Multi choice').click({force: true})
		cy.get('.stm_lms_add_new_question .question_types_input input').type('Multi choice Question{enter}')
		cy.wait('@createQuestion').should((xhr) => {
			assert.isNotNull(xhr.response.body.id, 'Create question response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create question response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create question response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-questions', 'Create question response Edit link is stm-questions')
		})
		cy.wait(2000)

		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Correct Answer 1{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Correct Answer 2{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Incorrect Answer 1{enter}')

		cy.wait(2000)
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(0).find('label').click()
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(1).find('label').click()


	})

	it('Add true/false question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')


		/*Creating multi question*/
		cy.get('.add_items .question_type').contains('True or false').click({force: true})
		cy.get('.stm_lms_add_new_question .question_types_input input').type('True/False Question{enter}')

		cy.wait(2000)
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(1).find('label').click()


	})

	it('Add item match question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')

		/*Creating multi question*/
		cy.get('.add_items .question_type').contains('Item Match').click({force: true})
		cy.get('.stm_lms_add_new_question .question_types_input input').type('Item match choice Question{enter}')
		cy.wait('@createQuestion').should((xhr) => {
			assert.isNotNull(xhr.response.body.id, 'Create question response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create question response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create question response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-questions', 'Create question response Edit link is stm-questions')
		})
		cy.wait(2000)

		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Item match 1{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Item match 2{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Item match 3{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Item match 4{enter}')

		cy.wait(2000)
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(0).find('.column-match input').type('Match 1')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(1).find('.column-match input').type('Match 2')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(2).find('.column-match input').type('Match 3')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm-lms-questions-single_answer').eq(3).find('.column-match input').type('Match 4')


	})

	it('Add keywords question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')

		/*Creating multi question*/
		cy.get('.add_items .question_type').contains('Keywords').click({force: true})
		cy.get('.stm_lms_add_new_question .question_types_input input').type('Keywords Question{enter}')
		cy.wait('@createQuestion').should((xhr) => {
			assert.isNotNull(xhr.response.body.id, 'Create question response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create question response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create question response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-questions', 'Create question response Edit link is stm-questions')
		})
		cy.wait(2000)

		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Keyword 1{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Keyword 2{enter}')
		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type('Keyword 3{enter}')


	})

	it('Add Fill the gap question to quiz', () => {

		cy.server()
		cy.route({
			method: 'GET',
			url: '/wp-admin/admin-ajax.php?action=stm_curriculum_create_item**',
		}).as('createQuestion')

		/*Creating multi question*/
		cy.get('.add_items .question_type').contains('Fill the Gap').click({force: true})
		cy.get('.stm_lms_add_new_question .question_types_input input').type('Keywords Question{enter}')
		cy.wait('@createQuestion').should((xhr) => {
			assert.isNotNull(xhr.response.body.id, 'Create question response ID is not null')
			assert.isNotNull(xhr.response.body.title, 'Create question response Title is not null')
			assert.isNotNull(xhr.response.body.edit_link, 'Create question response Edit link is not null')
			assert.equal(xhr.response.body.post_type, 'stm-questions', 'Create question response Edit link is stm-questions')
		})
		cy.wait(2000)

		let text = `Deborah was angry at her son. Her son didn't |listen| to her. Her son was 16 years old. Her son |thought| he knew everything. Her son |yelled| at Deborah.`

		cy.get('.stm_lms_questions_v2 .section').last().find('.stm_lms_answers_container__input input').type(`${text}{enter}`)


	})

	it('Save quiz', () => {
		cy.get('#publish').click()
	})

	it('Navigate to course', () => {
		cy.switchUser('exampleuser');
		cy.visit('/courses/test-course')
		cy.get('.stm-lms-buy-buttons .btn').click()
	})

	it('Start quiz', () => {
		cy.get('.stm_lms_start_quiz').click()
	})

	it('Answer questions', () => {
		cy.get('.stm_lms_paginated_quiz_question.active').find('.stm-lms-single-answer').eq(0).click()

		cy.get('.single-pager').eq(1).click()
		cy.wait(1000)
		cy.get('.stm_lms_paginated_quiz_question.active').find('.stm-lms-single-answer').eq(0).click()
		cy.get('.stm_lms_paginated_quiz_question.active').find('.stm-lms-single-answer').eq(1).click()

		cy.get('.single-pager').eq(2).click()
		cy.wait(1000)
		cy.get('.stm_lms_paginated_quiz_question.active').find('.stm-lms-single-answer').eq(1).click()


		cy.get('.single-pager').eq(3).click()
		cy.wait(2000)
		let dropPosition;
		cy.get('.stm_lms_question_item_match__answer').eq(0).then(($button) => {
			dropPosition = $button.offset()

			cy.get('.stm_lms_question_item_match__match').eq(0)
				.trigger('mousedown', {which : 1})
				.trigger('mousemove', { pageX: dropPosition['left'] + 50, pageY: dropPosition['top'] + 30 })
				.trigger('mouseup', {force: true})
		})

		cy.get('.single-pager').eq(4).click()
		cy.wait(1000)
		cy.get('.stm_lms_paginated_quiz_question.active').find('.enter_keyword_to_fill').type('Keyword 1 Keyword 2 Keyword 3')

		cy.get('.single-pager').eq(5).click()
		cy.wait(1000)
		cy.get('.stm_lms_paginated_quiz_question.active').find('input').eq(0).type('listen')
		cy.get('.stm_lms_paginated_quiz_question.active').find('input').eq(1).type('thought')
		cy.get('.stm_lms_paginated_quiz_question.active').find('input').eq(2).type('yelled')

	})

	it('Submit quiz', () => {
		cy.server()
		cy.route({
			method: 'POST',
			url: '/wp-admin/admin-ajax.php',
		}).as('submitQuiz')

		cy.get('.stm_lms_complete_lesson').click()

		cy.wait('@submitQuiz').should((xhr) => {
			assert.equal(xhr.response.body.passed, true, 'Quiz has passed value')
			assert.equal(xhr.response.body.progress, 83.3, 'Quiz has passed value')
			assert.equal(xhr.response.body.status, 'passed', 'Quiz has passed value')
		})


		cy.get('.btn-close-quiz-modal-results').click()

	})

	it('Check quiz challenged!', () => {
		cy.get('.stm_lms_result__round').contains('83%');

	})


})