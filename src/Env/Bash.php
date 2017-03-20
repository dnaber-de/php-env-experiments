<?php # -*- coding: utf-8 -*-

namespace EnvExperiment\Env;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

final class Bash implements Shell {

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
			'command',
			'-v',
			$command,
			'2>/dev/null 2>&1 || echo "false"'
		];
		$process = $this
			->process_builder
			->setArguments(
				$args
			)
			->getProcess();

		$this->output->writeln( " -  Run command: " . implode( ' ', $args ) );
		$output = $process
			->mustRun()
			->getOutput();
		$this->output->writeln( " -  Command Output: " . str_replace( PHP_EOL, '[LF]', $output ) );

		return "false" !== trim( $output );
	}

	public function isExecutable( $file ) {

		return is_executable( $file );
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