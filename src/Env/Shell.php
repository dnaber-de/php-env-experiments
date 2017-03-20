<?php # -*- coding: utf-8 -*-

namespace EnvExperiment\Env;

interface Shell {

	public function commandExists( $command );

	public function isExecutable( $file );

	public function run( array $command );
}