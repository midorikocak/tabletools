<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use midorikocak\nanodb\Database;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

use function array_keys;
use function reset;

class DatabaseTableTest extends TestCase
{
    private DatabaseTable $databaseTable;

    private Database $db;
    private PDO $pdo;

    private array $firstUser;
    private array $secondUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->pdo = new PDO('sqlite::memory:');
        $this->db = new Database($this->pdo);
        $this->createTable();

        $this->firstUser = [
            'username' => 'midorikocak',
            'email' => 'mtkocak@gmail.com',
            'password' => '12345678',
        ];

        $this->secondUser = [
            'username' => 'newuser',
            'email' => 'email@email.com',
            'password' => '87654321',
        ];

        $this->insertUser($this->firstUser['email'], $this->firstUser['username'], $this->firstUser['password']);
        $this->insertUser($this->secondUser['email'], $this->secondUser['username'], $this->secondUser['password']);

        $this->databaseTable = new DatabaseTable('users', $this->db);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTable, $this->db, $this->pdo);
    }

    private function createTable(): void
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
            $sql = "CREATE table users(
     id INTEGER PRIMARY KEY,
     username TEXT NOT NULL UNIQUE,
     email TEXT NOT NULL UNIQUE,
     password TEXT NOT NULL);";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage(); //Remove or change message in production code
        }
    }

    private function insertUser($email, $username, $password): void
    {
        $sql = "INSERT INTO users (email, username, password) VALUES (?,?,?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$email, $username, $password]);
    }

    public function testColumns(): void
    {
        $result = $this->databaseTable->columns(['username', 'email'])->run();

        $keys = array_keys(reset($result));

        $this->assertEquals(['username', 'email'], $keys);
    }

    public function testFilter(): void
    {
        $result = $this->databaseTable->filter('username', 'midorikocak')->run();
        $this->assertNotEmpty($result);
    }

    public function testSearch(): void
    {
        $result = $this->databaseTable->search('username', 'kocak')->run();
        $this->assertNotEmpty($result);
    }

    public function testSort(): void
    {
        $result = $this->databaseTable->sort('email')->run();
        $this->assertNotEmpty($result);
    }

    public function testRange(): void
    {
        $result = $this->databaseTable->range(0, 2)->run();
        $this->assertNotEmpty($result);
    }

    public function testPaginate(): void
    {
        $result = $this->databaseTable->paginate(0, 2)->run();
        $this->assertNotEmpty($result);
    }
}
