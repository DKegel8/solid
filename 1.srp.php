<?php

/*
Single Responsibility Principle

"Gwen: Look, I have one job on this lousy ship. It’s stupid, but I’m going to do it, okay?"

- Galaxy Quest

A class should have only one job.  If a class has more than one responsibility,
it becomes coupled.  A change to one responsibility results to modification of
the other responsibility.
*/

class Animal {
	function __construct(string $name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function save() {
		$servername = "localhost";
		$username = "username";
		$password = "password";

		// Create connection
		$conn = new mysqli($servername, $username, $password);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully";
		$sql = "INSERT INTO animals (name) VALUES (".$this->name.");";

		if ($conn->multi_query($sql) === TRUE) {
			echo "New records created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
	}

	private string $name;
}

/*
The Animal class violates the SRP.

How does it violate SRP?

SRP states that classes should have one responsibility, here, we can draw out
two responsibilities: animal database management and animal properties
management.  The constructor and get_name manage the Animal properties while the
save manages the Animal storage on a database.

How will this design cause issues in the future?

If the application changes in a way that it affects database management
functions. The classes that make use of Animal properties will have to be
touched and recompiled to compensate for the new changes.

You see this system smells of rigidity, it’s like a domino effect, touch one
card it affects all other cards in line.

To make this conform to SRP, we create another class that will handle the sole
responsibility of storing an animal to a database:
*/

class Animal2 {
	function __construct(string $name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	private string $name;
}

class AnimalDB {
	function __construct() {
		$servername = "localhost";
		$username = "username";
		$password = "password";

		// Create connection
		$conn = new mysqli($servername, $username, $password);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$this->conn = $conn;
	}

	public function getAnimal(int $id) {
		$sql = "SELECT * FROM animals WHERE id = ".$id.";";

		$result = mysqli_query($this->conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				echo "Name: " . $row["name"]. "<br>";
			}
		} else {
			echo "0 results";
		}
	}

	public function save($name) {
		$sql = "INSERT INTO animals (name) VALUES (".$name.");";

		if ($this->conn->multi_query($sql) === TRUE) {
			echo "New records created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $this->conn->error;
		}
	}

	public function __destruct() {
		$this->conn->close();
	}

	private mysqli $conn;
}

/*
When designing our classes, we should aim to put related features together, so
whenever they tend to change they change for the same reason.  And we should try
to separate features if they will change for different reasons. - Steve Fenton

The downside of this solution is that the clients of the this code have to deal
with two classes.  A common solution to this dilemma is to apply the Facade
pattern.  Animal class will be the Facade for animal database management and
animal properties management.
*/

class Animal3 {
	function __construct(string $name) {
		$this->name = $name;
		$this->animalDb = new AnimalDB();
	}

	public function getName(): string {
		return $this->name;
	}

	public function save() {
		$this->animalDb->save($this->name);
	}

	private string $name;
	private AnimalDB $animalDb;
}

/*
The most important methods are kept in the Animal class and used as Facade for
the lesser functions.
*/