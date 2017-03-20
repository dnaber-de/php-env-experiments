<?php # -*- coding: utf-8 -*-

namespace EnvExperiment\Env;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;



final class Windows implements Shell {

	/**
	 * @var ProcessBuilder
	 */
	private $process_builder;

	/**
	 * @var OutputInterface
	 */
	private $output;

	/**
	 * @param ProcessBuilder $process_builder
	 * @param OutputInterface $output
	 */
	public function __construct( ProcessBuilder $process_builder, OutputInterface $output ) {

		$this->process_builder = $process_builder;
		$this->output = $output;
	}

	public function commandExists( $command ) {

		$args = [
			'where',
			$command
		];
		$process = $this
			->process_builder
			->setArguments(
				$args
			)
			->getProcess();

		$this->output->writeln( "\tRun command: " . implode( ' ', $args ) );
		$output = $process
			->mustRun()
			->getOutput();
		$this->output->writeln( " -  Command Output: " . str_replace( PHP_EOL, '[LF]', $output ) );

		$output = explode( PHP_EOL, trim( $output ) );

		return ! empty( $output ) && is_readable( trim( $output[ 0 ] ) );
	}

	public function isExecutable( $file ) {

		$executable_extensions = [ 'exe', 'bat', 'cmd', 'com' ];

		$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

		return in_array( $extension, $executable_extensions );
	}

	public function run( array $command ) {

		$process = $this->process_builder
			->setArguments( $command )
			->getProcess();

		$this->output->writeln( " -  Run command: " . implode( ' ', $command ) );
		$output = $process
			->mustRun()
			->getOutput();
		$this->output->writeln( " -  Command Output: " . str_replace( PHP_EOL, '[LF]', $output ) );

		return $output;
	}

}