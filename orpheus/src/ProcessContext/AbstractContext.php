<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ProcessContext;

abstract class AbstractContext {
	abstract function hasParameter(string $key): bool;
	
	abstract function isVerbose(): bool;
	
	abstract function isDryRun(): bool;
}
