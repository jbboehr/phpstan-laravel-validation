.PHONY: docs docs-check docs-serve

docs:
	mdbook build docs

docs-check: docs
	php scripts/check-mdbook-links.php build/docs

docs-serve:
	mdbook serve docs --hostname 127.0.0.1
