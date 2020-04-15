<?php
/*
Dependency Inversion Principle

Dependency should be on abstractions not concretions A. High-level modules
should not depend upon low-level modules. Both should depend upon abstractions.
B. Abstractions should not depend on details. Details should depend upon
abstractions.

There comes a point in software development where our app will be largely
composed of modules.  When this happens, we have to clear things up by using
dependency injection.  High-level components depending on low-level components
to function.
*/

class Database {
	public function __construct(string $host, string $user, string $password, string $name) {
		// Make Database Connection
	}

	public function getAll($type) {
		return $type;
	}

	public function getUser() {
		return "Some User";
	}
}

/*
The class User has implicit dependency on the specific database. All dependencies should always be explicit not
implicit. This defeats Dependency inversion principle

If we wanted to change database credentials, we need to edit the User class which is not good; every class should be
completely modular or black box. If we need to operate further on it, we should actually use its public properties and
methods instead of editing it again and again. This defeats Open/closed principle

Let's assume right now class is using MySQL as database. What if we wanted to use some other type of database ?
You will have to modify it.

The User class does not necessarily need to know about database connection, it should be confined to its own
functionality only. So writing database connection code in User class doesn't make it modular. This defeats the
Single responsibility principle. Think of this analogy: A cat knows how to meow and a dog knows how to woof; you cannot
mix them or expect dog to say meow. Just like real world, each object of a class should be responsible for its own
specific task.

It would become harder to write unit tests for the User class because we are instantiating the database class inside
its constructor so it would be impossible to write unit tests for the User class without also testing the database
class.
 */

class DefaultUser
{
	private $database = null;

	public function __construct() {
		$this->database = new Database('host', 'user', 'pass', 'dbname');
	}

	public function getUsers() {
		return $this->database->getAll('users');
	}
}

$user = new DefaultUser();
echo $user->getUsers();

/* It follows Hollywood Principle, which states: "Don’t call us, we’ll call you." */

class User
{
	private Database $database;

	public function __construct(Database $database) {
		$this->database = $database;
	}

	public function getUsers() {
		return $this->database->getAll('users');
	}
}

$database = new Database('host', 'user', 'pass', 'dbname');
$user = new User($database);
$user->getUsers();

/*
Interface Injection

In this type of injection, an interface enforces the dependencies for any classes that implement it, for example:
 */

interface someInterface {
	function getUsers(Database $database);
}

class IUser implements someInterface
{
	public function getUsers(Database $database) {
		return $database->getAll('users');
	}
}

$database = new Database('host', 'user', 'pass', 'dbname');
$user = new IUser();
$user->getUsers($database);
