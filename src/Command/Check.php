<?php # -*- coding: utf-8 -*-

namespace EnvExperiment\Command;

use EnvExperiment\Env\EnvironmentFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Class Check
 *
 * @package EnvExperiment\Command
 */
class Check extends Command {

	const COMMAND_NAME = 'check';

	private $base_dir;

	/**
	 * @param null|string $base_dir
	 */
	public function __construct( $base_dir ) {

		parent::__construct();

		$dir = realpath( (string) $base_dir );
		if ( ! $dir || ! is_dir( $dir ) ) {
			$dir or $dir = 'false';
			throw new \InvalidArgumentException( "Directory not found: {$base_dir}" );
		}

		$this->base_dir = $base_dir;
	}

	/**
	 * Configures the current command.
	 */
	protected function configure() {

		parent::configure();
		$this
			->setName( self::COMMAND_NAME )
			->setDescription( 'Just testing some command-running stuff' );


	}

	/**
	 * Runs the command.
	 *
	 * The code to execute is either defined directly with the
	 * setCode() method or by overriding the execute() method
	 * in a sub-class.
	 *
	 * @param InputInterface $input An InputInterface instance
	 * @param OutputInterface $output An OutputInterface instance
	 *
	 * @return int The command exit code
	 *
	 * @see setCode()
	 * @see execute()
	 */
	public function run( InputInterface $input, OutputInterface $output ) {

		$env = EnvironmentFactory::getEnv( $output );

		$output->writeln( 'PHP_OS: ' . PHP_OS );
		$output->writeln( 'PHP_SAP: ' . PHP_SAPI );
		$output->writeln( 'PHP_VERSION: ' . PHP_VERSION );
		$output->writeln( 'PHP_EOL: ' . str_replace( [ "\r", "\n" ], [ 'CR', 'LF' ] , PHP_EOL ) );

		$output->writeln( '------' );
		$output->writeln( '<info>Checking `php` command ...</info>' );
		$php_exists = $env->commandExists( 'php' )
			? 'yes' : 'no';
		$output->writeln( "php exists: {$php_exists}" );

		$php_finder = new PhpExecutableFinder();
		$found_php = $php_finder->find();
		$output->writeln( "Found php executable: {$found_php}" );
		$php_is_executable = $env->isExecutable( $found_php )
			? 'yes' : 'no';
		$output->writeln( "Found php executable is actually executable: {$php_is_executable}" );

		$output->writeln( '------' );
		$output->writeln( '<info>Checking `wp` command ...</info>' );
		$wp_exists = $env->commandExists( 'wp' )
			? 'yes' : 'no';
		$output->writeln( "wp exists: {$wp_exists}" );
		$finder = new ExecutableFinder();
		$found_wp = $finder->find( 'wp' );
		$found_wp or $found_wp = 'null';
		$output->writeln( "Found wp executable: {$found_wp}" );
		$wp_is_executable = $env->isExecutable( $found_wp )
			? 'yes' : 'no';
		$output->writeln( "Found wp executable is actually executable: {$wp_is_executable}" );

		$local_wp_version = '';
		if ( 'null' !== $found_wp ) {
			$local_wp_version = trim( $env->run( [ $found_wp, '--version' ] ) );
			$local_wp_version = str_replace( 'WP-CLI ', '', $local_wp_version );
		}

		$output->writeln( '------' );
		$output->writeln( '<info>Checking `composer` command ...</info>' );
		$composer_exists = $env->commandExists( 'composer' )
			? 'yes' : 'no';
		$output->writeln( "composer exists: {$composer_exists}" );
		$found_composer = $finder->find( 'composer' );
		$found_composer or $found_composer = 'null';
		$output->writeln( "Found composer executable: {$found_composer}" );
		$composer_is_executable = $env->isExecutable( $found_composer )
			? 'yes' : 'no';
		$output->writeln( "Found composer executable is actually executable: {$composer_is_executable}" );
		if ( 'null' !== $found_composer ) {
			$env->run( [ $found_composer, '--version' ] );
		}

		$output->writeln( '------' );
		$output->writeln( '<info>Checking self ...</info>' );
		$self = "{$this->base_dir}/bin/env-experiment";
		$self_is_executable = $env->isExecutable( $self )
			? 'yes' : 'no';
		$output->writeln( "Self is executable: {$self_is_executable}" );
		if ( 'yes' === $self_is_executable ) {
			$env->run( [ $self, '--version' ] );
		}
		if ( 'yes' === $php_exists ) {
			$env->run( [ 'php', $self, '--version' ] );
		}
		if ( 'null' !== $found_php ) {
			$env->run( [ $found_php, $self, '--version' ] );
		}

		$output->writeln( '------' );
		$output->writeln( '<info>Checking phar ...</info>' );
		$phar = "{$this->base_dir}/bin/wp";
		$phar_is_executable = $env->isExecutable( $phar )
			? 'yes' : 'no';
		$output->writeln( "Phar is executable: {$phar_is_executable}" );
		$bundled_wp_version = '';
		if ( 'yes' === $phar_is_executable ) {
			$bundled_wp_version = $env->run( [ $phar, '--version' ] );
		}
		if ( 'yes' === $php_exists ) {
			$bundled_wp_version = $env->run( [ 'php', $phar, '--version' ] );
		}
		if ( 'null' !== $found_php ) {
			$bundled_wp_version = $env->run( [ $found_php, $phar, '--version' ] );
		}
		$bundled_wp_version = trim( $bundled_wp_version );
		$bundled_wp_version = str_replace( 'WP-CLI ', '', $bundled_wp_version );

		$output->writeln( '------' );
		$output->writeln( "<info>That's all, thanks for contribution :)</info>" );
		if ( $local_wp_version && version_compare( $local_wp_version, $bundled_wp_version, '<' ) ) {
			$output->writeln( "<comment>You might want to update your local WP-CLI version ;)</comment>" );
		}

		return 0;
	}

}