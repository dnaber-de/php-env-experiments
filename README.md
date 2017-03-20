# Env Experiments

Commandline PHP script to determine differences in different operating systems.

## How to run

Clone the repo and then run 
```
$ composer install
```

Then execute the php script as you would do it on your operating system:

E.g. on Linux and similar OS:
```
$ bin/env-experiments check
```

## Example Output

Output on Ubuntu:

```
$ bin/env-experiment check
PHP_OS: Linux
PHP_SAP: cli
PHP_VERSION: 7.0.17-2+deb.sury.org~trusty+1
------
Checking for `php` command …
 -  Run command: command -v php 2>/dev/null 2>&1 || echo "false"
 -  Command Output: /usr/bin/php[LF]
php exists: yes
Found php executable: /usr/bin/php7.0
Found php executable is actually executable: yes
------
Checking for `wp` command …
 -  Run command: command -v wp 2>/dev/null 2>&1 || echo "false"
 -  Command Output: /usr/local/bin/wp[LF]
wp exists: yes
Found wp executable: /usr/local/bin/wp
Found wp executable is actually executable: yes
 -  Run command: /usr/local/bin/wp --version
 -  Command Output: WP-CLI 1.1.0[LF]
------
Checking self …
Self is executable: yes
 -  Run command: /…/env-experiment/bin/env-experiment --version
 -  Command Output: Env Experiments dev-master[LF]
 -  Run command: php /…/env-experiment/bin/env-experiment --version
 -  Command Output: Env Experiments dev-master[LF]
 -  Run command: /usr/bin/php7.0 /…/env-experiment/bin/env-experiment --version
 -  Command Output: Env Experiments dev-master[LF]
------
Checking phar …
Phar is executable: yes
 -  Run command: /…/env-experiment/bin/wp --version
 -  Command Output: WP-CLI 1.1.0[LF]
 -  Run command: php /…/env-experiment/bin/wp --version
 -  Command Output: WP-CLI 1.1.0[LF]
 -  Run command: /usr/bin/php7.0 /…/env-experiment/bin/wp --version
 -  Command Output: WP-CLI 1.1.0[LF]
------
That's all, thanks for contribution :)

```
