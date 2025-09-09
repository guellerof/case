<?php
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;

class UserTest extends TestCase {
    private PDO $pdo;
    private UserRepository $repo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->repo = new UserRepository($this->pdo);
        $this->repo->createTableIfNotExists();
    }

    public function testAddAndListUser(): void {
        $id = $this->repo->addUser('Test','test@example.com',password_hash('pass',PASSWORD_DEFAULT));
        $this->assertGreaterThan(0, $id);
        $users = $this->repo->listUsers();
        $this->assertCount(1, $users);
        $this->assertEquals('test@example.com', $users[0]['email']);
    }

    public function testFindByEmail(): void {
        $this->repo->addUser('A','a@a.com',password_hash('p',PASSWORD_DEFAULT));
        $user = $this->repo->findByEmail('a@a.com');
        $this->assertNotNull($user);
        $this->assertEquals('a@a.com', $user['email']);
    }
}
