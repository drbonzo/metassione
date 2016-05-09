help:
	# HELP:
	# test
	# test-watch
	# test-coverage

test:
	phpunit

test-watch:
	watch --color -n 1 phpunit --colors=always

test-coverage:
	phpunit --coverage-html=phpunit-coverage

