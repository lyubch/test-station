<?php

namespace app\helpers\habr\di\p1;

class EntryPoint
{
	public static function run()
	{
		try {
			$controller = new UserController();
			return $controller->handle();
		} catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}
}

class UserController
{
    public function handle() {
        $repo = new UserRepository();
        // Тут, конечно, будет $_POST['email']:
        $user = $repo->findByEmail('john@gmail.com'); 
        if (empty($user)) {
            throw new \Exception('Пользователь не найден!');
        }
        return $user;
    }
}

class UserRepository
{
    public function findByEmail(string $email): ?User {
        $db = new Db();
        $res = $db->query(
            'SELECT * FROM users WHERE email=:email', 
            [':email' => $email], 
            User::class
        );
        return !empty($res) ? $res[0] : null;
    }
}

class Db {
	private \PDO $_dbh;
	
	public function __construct()
	{
		$this->_dbh = new \PDO('mysql:host=localhost;dbname=test_station', 'root', '');
	}
	
	public function query(string $sql, array $params = [], $class = \stdClass::class): array {
		$sth = $this->_dbh->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
	}
}

class User {
	public string $name;
	public string $email;
}
