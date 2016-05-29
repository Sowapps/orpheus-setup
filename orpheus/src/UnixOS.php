<?php

class UnixOS extends OperatingSystem {

	public function removePath($path) {
		system('rm -rf "'.$path.'"');
	}
}
