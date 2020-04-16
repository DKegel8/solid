<?php

/**
 * The complicated, underlying logic.
 */
class CPU
{
	public function freeze() {/* ... */}
	public function jump($position) {
		echo $position;
	}
	public function execute() {/* ... */}
}

class Memory
{
	public function load($position, $data) {
		echo $position;
		echo $data;
	}
}

class HardDrive
{
	public function read($lba, $size) {
		echo $lba;
		echo $size;
	}
}

/**
 * The facade that users would be interacting with.
 */
class ComputerFacade
{
	protected CPU $cpu;
	protected Memory $memory;
	protected HardDrive $hd;

	public function __construct()
	{
		$this->cpu = new CPU;
		$this->memory = new Memory;
		$this->hd = new HardDrive;
	}

	public function start() : void
	{
		$this->cpu->freeze();
		$this->memory->load("BOOT_ADDRESS", $this->hd->read("BOOT_SECTOR", "SECTOR_SIZE"));
		$this->cpu->jump("BOOT_ADDRESS");
		$this->cpu->execute();
	}
}

/**
 * How a user could start the computer.
 */
$computer = new ComputerFacade;
$computer->start();