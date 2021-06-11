Install Dependencies
```
yarn install
```

Open docker and start test WP env
```
yarn run wp-cypress start
```

Run single test
```
npm run cy:run -- --spec "cypress/integration/examples/paginated_quiz.spec.js"
```

Or Run visual interface
```
yarn run cypress open
```