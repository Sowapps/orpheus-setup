<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ProcessContext;

use Exception;
use InvalidArgumentException;

class ConsoleContext extends AbstractContext {
	
	private string $path;
	private array $parameters;
	private bool $verbose;
	private bool $dryRun;
	
	/**
	 * ConsoleContext constructor
	 */
	public function __construct(string $path, array $parameters) {
		$this->path = $path;
		$this->parameters = $parameters;
		$this->setVerbose(isset($parameters['verbose']));
		$this->setDryRun(isset($parameters['dry-run']));
	}
	
	public function validateExclusiveParameters(array $parameters): void {
		if( count($parameters) < 2 ) {
			throw new Exception('Require two parameters at least');
		}
		$hasOne = false;
		foreach( $parameters as $key ) {
			if( $this->hasParameter($key) ) {
				if( $hasOne ) {
					throw new InvalidArgumentException(sprintf('Can not mix %s options', implode(', ', $parameters)));
				} else {
					$hasOne = true;
				}
			}
		}
	}
	
	public function getParameter(string $key, mixed $default = null): mixed {
		return $this->parameters[$key] ?? $default;
	}
	
	public function hasParameter(string $key): bool {
		return isset($this->parameters[$key]);
	}
	
	public function getScriptName(): string {
		return basename($this->path);
	}
	
	public function getPath(): string {
		return $this->path;
	}
	
	public function getParameters(): array {
		return $this->parameters;
	}
	
	public function isVerbose(): bool {
		return $this->verbose;
	}
	
	public function setVerbose(bool $verbose): void {
		$this->verbose = $verbose;
	}
	
	public function isDryRun(): bool {
		return $this->dryRun;
	}
	
	public function setDryRun(bool $dryRun): void {
		$this->dryRun = $dryRun;
	}
	
	public static function mapOptions(array $parameters, array $mapping): array {
		foreach( $mapping as $from => $to ) {
			if( isset($parameters[$from]) ) {
				$parameters[$to] ??= $parameters[$from];
				unset($parameters[$from]);
			}
		}
		
		return $parameters;
	}
}
