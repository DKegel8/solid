<?php
/*
Open-Closed Principle

Software entities(Classes, modules, functions) should be open for extension, not
modification.
*/

class Animal {
	function __construct(string $name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function animalSound(array $animals) {
		foreach($animals as $animal) {
			switch($animal) {
				case 'lion':
					echo 'roar';
					break;
				case 'mouse':
					echo 'squeak';
					break;
			}
		}
	}

	private string $name;
}
$animalsList = ['lion', 'mouse'];
$animals = new Animal("");
$animals->animalSound($animalsList);

/*
The function animal_sound does not conform to the open-closed principle because
it cannot be closed against new kinds of animals.  If we add a new animal,
Snake, We have to modify the animal_sound function.  You see, for every new
animal, a new logic is added to the animal_sound function.  This is quite a
simple example. When your application grows and becomes complex, you will see
that the switch statement would be repeated over and over again in the animal_sound
function each time a new animal is added, all over the application.
*/

class Animal2 {
	function __construct(string $name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function animalSound(array $animals) {
		foreach($animals as $animal) {
			switch($animal) {
				case 'lion':
					echo 'roar';
					break;
				case 'mouse':
					echo 'squeak';
					break;
				case 'snake':
					echo 'hiss';
					break;
			}
		}
	}

	private string $name;
}

$animalsList2 = ['lion', 'mouse', 'snake'];
$animals2 = new Animal2("");
$animals2->animalSound($animalsList2);


/*
How do we make it (the duck quack behavior) conform to OCP using Strategy Pattern?
*/


interface QuackBehavior {
	public function quack();
}

Class NormalQuack implements QuackBehavior {
	public function quack(){
		return "Quack";
	}
}

Class MuteQuack implements QuackBehavior {
	public function quack(){
		return "<< Silence >>";
	}
}

Class Squeak implements QuackBehavior {
	public function quack(){
		return "Squeak";
	}
}

Class Duck {
	private $quackType;

	// Get only objects that belong to the interface.
	public function __construct(QuackBehavior $QuackBehavior){
		$this->quackType = $QuackBehavior;
	}

	public function getBehavior() {
		echo $this->quackType->quack()."\n";
	}
}

$malardDuck = new Duck(new NormalQuack);
$malardDuck->getBehavior();
