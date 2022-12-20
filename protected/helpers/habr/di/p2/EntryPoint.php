<?php

namespace app\helpers\habr\di\p2;

class EntryPoint
{
	public static function run()
	{
		try {
			$controller = (new UserController())
				->setUserRepository(
					(new UserRepository())
						->setDb(
							new Db()
						)
				);
			return $controller->handle();
		} catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}
}

class UserController
{
	private $userRepository;
	
	public function setUserRepository(UserRepository $userRepository): self {
		$this->userRepository = $userRepository;
		return $this;
	}
    public function handle() {
        // Тут, конечно, будет $_POST['email']:
        $user = $this->userRepository->findByEmail('john@gmail.com'); 
        if (empty($user)) {
            throw new \Exception('Пользователь не найден!');
        }
        return $user;
    }
}

class UserRepository
{
	private $db;
	
	public function setDb(Db $db): self {
		$this->db = $db;
		return $this;
	}
    public function findByEmail(string $email): ?User {
        $res = $this->db->query(
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
