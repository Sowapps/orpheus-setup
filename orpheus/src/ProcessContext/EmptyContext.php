<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ProcessContext;

class EmptyContext extends AbstractContext {
	
	public function hasParameter(string $key): bool {
		return false;
	}
	
	public function isVerbose(): bool {
		return false;
	}
	
	public function isDryRun(): bool {
		return false;
	}
	
}
