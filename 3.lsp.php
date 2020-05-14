<?php

/*
The Liskov Substitution Principle states that any class that is the child of a parent class should be usable in place of its parent without any unexpected behaviour.

 Barbara Liskov and Jeannette Wing described the principle succinctly in a 1994 paper as follows[1]:

Subtype Requirement: Let {\displaystyle \phi (x)}\phi (x) be a property provable about objects {\displaystyle x}x of type T. Then {\displaystyle \phi (y)}{\displaystyle \phi (y)} should be true for objects {\displaystyle y}y of type S where S is a subtype of T.

One rule that should be abided by in PHP is that any method should return the same type as that of its parent. For example, if the search method of T returns an instance of Illuminate\\Support\\Collection then any search method of S should also return an Illuminate\\Support\\Collection.

The four conditions for abiding by the Liskov Substitution principle are as follows:
1 Method signatures must match:
    * Methods must take the same parameters.
2 The preconditions for any method can’t be greater than that of its parent:
    * Any inherited method should not have more conditionals that change the return of that method, such as throwing an Exception.
3 Post conditions must be at least equal to that of its parent:
    * Inherited methods should return the same type as that of its parent
4 Exception types must match:
    * If a method is designed to return a FileNotFoundException in the event of an error, the same condition in the inherited method must return a FileNotFoundException too
*/

/** Factory Pattern Example:
	We are following LSP in the Creator abstract Class factoryMethod(), and its two children classes ConcreteCreator1 - ConcreteCreator2.
	Also in the Product interface operation() and its children subClasses.
 */

/**
 * INTENT
 *  Define an interface for creating an object, but let subclasses decide which class to instantiate.
 *  Factory Method lets a class defer instantiation to subclasses.
 *
 * AKA
 *  Virtual Constructor
 */

/**
 * The Creator class declares the factory method that is supposed to return an
 * object of a Product class. The Creator's subclasses usually provide the
 * implementation of this method.
 */
abstract class Creator
{
    /**
     * Note that the Creator may also provide some default implementation of the
     * factory method.
     */
    abstract public function factoryMethod(): Product;

    /**
     * Also note that, despite its name, the Creator's primary responsibility is
     * not creating products. Usually, it contains some core business logic that
     * relies on Product objects, returned by the factory method. Subclasses can
     * indirectly change that business logic by overriding the factory method
     * and returning a different type of product from it.
     */
    public function someOperation(): string
    {
        // Call the factory method to create a Product object.
        $product = $this->factoryMethod();
        // Now, use the product.
        $result = "Creator: The same creator's code has just worked with " .
            $product->operation();

        return $result;
    }
}

/**
 * Concrete Creators override the factory method in order to change the
 * resulting product's type.
 */
class ConcreteCreator1 extends Creator
{
    /**
     * Note that the signature of the method still uses the abstract product
     * type, even though the concrete product is actually returned from the
     * method. This way the Creator can stay independent of concrete product
     * classes.
     */
    public function factoryMethod(): Product
    {
        return new ConcreteProduct1;
    }
}

class ConcreteCreator2 extends Creator
{
    public function factoryMethod(): Product
    {
        return new ConcreteProduct2;
    }
}

/**
 * The Product interface declares the operations that all concrete products must
 * implement.
 */
interface Product
{
    public function operation(): string;
}

/**
 * Concrete Products provide various implementations of the Product interface.
 */
class ConcreteProduct1 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct1}";
    }
}

class ConcreteProduct2 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct2}";
    }
}

/**
 * The client code works with an instance of a concrete creator, albeit through
 * its base interface. As long as the client keeps working with the creator via
 * the base interface, you can pass it any creator's subclass.
 */
function clientCode(Creator $creator)
{
    // ...
    echo "Client: I'm not aware of the creator's class, but it still works.\n"
        . $creator->someOperation();
    // ...
}

/**
 * The Application picks a creator's type depending on the configuration or
 * environment.
 */
echo "App: Launched with the ConcreteCreator1.\n";
clientCode(new ConcreteCreator1);
echo "\n\n";

echo "App: Launched with the ConcreteCreator2.\n";
clientCode(new ConcreteCreator2);
