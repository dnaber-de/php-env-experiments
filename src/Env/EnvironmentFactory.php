<?php # -*- coding: utf-8 -*-

namespace EnvExperiment\Env;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

class EnvironmentFactory {

	public static function getEnv( OutputInterface $output ) {

		if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
			return new Windows( new ProcessBuilder(), $output );
		}

		return new Bash( new ProcessBuilder(), $output );
	}
}