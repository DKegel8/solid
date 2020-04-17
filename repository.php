<?php

/*
taken from https://stackoverflow.com/questions/16176990/proper-repository-pattern-design-in-php

Model:

*/

class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $gender;
    public $email;
    public $password;
}


/*
Interface
*/

interface UserRepositoryInterface
{
    public function find($id);
    public function save(User $user);
    public function remove(User $user);
}

/*
Interface Implamentation
*/

class SQLUserRepository implements UserRepositoryInterface
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function find($id)
    {
        // Find a record with the id = $id
        // from the 'users' table
        // and return it as a User object
        return $this->db->find($id, 'users', 'User');
    }

    public function save(User $user)
    {
        // Insert or update the $user
        // in the 'users' table
        $this->db->save($user, 'users');
    }

    public function remove(User $user)
    {
        // Remove the $user
        // from the 'users' table
        $this->db->remove($user, 'users');
    }
}


/* 
Interface B
*/
interface AllUsersQueryInterface
{
    public function fetch($fields);
}

/*
Interface B Implamentation
*/

class AllUsersQuery implements AllUsersQueryInterface
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function fetch($fields)
    {
        return $this->db->select($fields)->from('users')->orderBy('last_name, first_name')->rows();
    }
}


/*
Controller
*/

class UsersController
{
    public function index(AllUsersQueryInterface $query)
    {
        // Fetch user data
        $users = $query->fetch(['first_name', 'last_name', 'email']);

        // Return view
        return Response::view('all_users.php', ['users' => $users]);
    }

    public function add()
    {
        return Response::view('add_user.php');
    }

    public function insert(UserRepositoryInterface $repository)
    {
        // Create new user model
        $user = new User;
        $user->first_name = $_POST['first_name'];
        $user->last_name = $_POST['last_name'];
        $user->gender = $_POST['gender'];
        $user->email = $_POST['email'];

        // Save the new user
        $repository->save($user);

        // Return the id
        return Response::json(['id' => $user->id]);
    }

    public function view(SpecificUserQueryInterface $query, $id)
    {
        // Load user data
        if (!$user = $query->fetch($id, ['first_name', 'last_name', 'gender', 'email'])) {
            return Response::notFound();
        }

        // Return view
        return Response::view('view_user.php', ['user' => $user]);
    }

    public function edit(SpecificUserQueryInterface $query, $id)
    {
        // Load user data
        if (!$user = $query->fetch($id, ['first_name', 'last_name', 'gender', 'email'])) {
            return Response::notFound();
        }

        // Return view
        return Response::view('edit_user.php', ['user' => $user]);
    }

    public function update(UserRepositoryInterface $repository)
    {
        // Load user model
        if (!$user = $repository->find($id)) {
            return Response::notFound();
        }

        // Update the user
        $user->first_name = $_POST['first_name'];
        $user->last_name = $_POST['last_name'];
        $user->gender = $_POST['gender'];
        $user->email = $_POST['email'];

        // Save the user
        $repository->save($user);

        // Return success
        return true;
    }

    public function delete(UserRepositoryInterface $repository)
    {
        // Load user model
        if (!$user = $repository->find($id)) {
            return Response::notFound();
        }

        // Delete the user
        $repository->delete($user);

        // Return success
        return true;
    }
}



/*
In researching this most examples are using repository in relation to a framework/ORM.
some examples:
https://designpatternsphp.readthedocs.io/en/latest/More/Repository/README.html
https://www.developer.com/lang/php/the-repository-pattern-in-php.html
https://webdevetc.com/blog/the-repository-pattern-in-php-and-laravel
*/