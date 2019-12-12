<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class DatabaseTest extends TestCase
{
    /**
     * @var Database
     */
    protected $adapter;

    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var array
     */
    protected $options;

    protected function setUp(): void
    {
        $this->options = [
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'dbname' => getenv('DB_NAME'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
        ];

        $this->createDatabaseFixture($this->options);
        $this->adapter = new Database($this->options);
    }

    public function testIsPdoObjectLazilyInstantiated(): void
    {
        $testAdapter = new Database($this->options);

        $adapterPdo = $this->getAttribute($testAdapter, 'connection');
        $this->assertNull($adapterPdo);

        $testAdapter->execute('SELECT 1');

        /** @var PDO $adapterPdo */
        $adapterPdo = $this->getAttribute($testAdapter, 'connection');
        $this->assertInstanceOf(PDO::class, $adapterPdo);
    }

    public function testIsSettingPdoDefaultErrorModeAttributeToException(): void
    {
        $testAdapter = new Database($this->options);
        $testAdapter->execute('SELECT 1');

        /** @var PDO $adapterPdo */
        $adapterPdo = $this->getAttribute($testAdapter, 'connection');
        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $adapterPdo->getAttribute(PDO::ATTR_ERRMODE));
    }

    public function testIsFetchingAllWithoutParams(): void
    {
        $actual = $this->adapter->findAll('SELECT * FROM departments ORDER BY dept_no');

        $this->assertIsArray($actual);
        $firstRow = reset($actual);
        $this->assertIsArray($firstRow);
        $this->assertArrayHasKey('dept_id', $firstRow);
        $this->assertArrayHasKey('dept_no', $firstRow);
        $this->assertEquals('d001', $firstRow['dept_no']);
        $this->assertArrayHasKey('dept_name', $firstRow);
        $this->assertEquals('Marketing', $firstRow['dept_name']);
    }

    public function testIsFetchingAllWithNamelessParam(): void
    {
        $actual = $this->adapter->findAll('SELECT * FROM departments WHERE dept_no = ?', ['d001']);

        $this->assertIsArray($actual);
        $firstRow = reset($actual);
        $this->assertIsArray($firstRow);
        $this->assertArrayHasKey('dept_id', $firstRow);
        $this->assertArrayHasKey('dept_no', $firstRow);
        $this->assertEquals('d001', $firstRow['dept_no']);
        $this->assertArrayHasKey('dept_name', $firstRow);
        $this->assertEquals('Marketing', $firstRow['dept_name']);
    }

    public function testIsFetchingAllWithNamedParam(): void
    {
        $actual = $this->adapter->findAll('SELECT * FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd001']);

        $this->assertIsArray($actual);
        $firstRow = reset($actual);
        $this->assertIsArray($firstRow);
        $this->assertArrayHasKey('dept_id', $firstRow);
        $this->assertArrayHasKey('dept_no', $firstRow);
        $this->assertEquals('d001', $firstRow['dept_no']);
        $this->assertArrayHasKey('dept_name', $firstRow);
        $this->assertEquals('Marketing', $firstRow['dept_name']);
    }

    public function testIsFetchingAllWithEmptyResult(): void
    {
        $actual = $this->adapter->findAll('SELECT * FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd099']);

        $this->assertIsArray($actual);
        $this->assertEmpty($actual);
    }

    public function testIsFetchingOneWithNamedParam(): void
    {
        $actual = $this->adapter->find('SELECT * FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd001']);

        $this->assertIsArray($actual);
        $this->assertArrayHasKey('dept_id', $actual);
        $this->assertArrayHasKey('dept_no', $actual);
        $this->assertEquals('d001', $actual['dept_no']);
        $this->assertArrayHasKey('dept_name', $actual);
        $this->assertEquals('Marketing', $actual['dept_name']);
    }

    public function testIsFetchingOneWithEmptyResult(): void
    {
        $actual = $this->adapter->find('SELECT * FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd099']);

        $this->assertIsArray($actual);
        $this->assertEmpty($actual);
    }

    public function testIsFetchingValueWithNamedParam(): void
    {
        $actual = $this->adapter->fetchValue('SELECT dept_name FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd001']);

        $this->assertEquals('Marketing', $actual);
    }

    public function testIsFetchingValueWithEmptyResult(): void
    {
        $actual = $this->adapter->fetchValue('SELECT dept_name FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd099']);

        $this->assertNull($actual);
    }

    public function testIsFetchingKeyPairWithNamedParam(): void
    {
        $actual = $this->adapter->fetchKeyPair('SELECT dept_no,dept_name FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd001']);

        $this->assertIsArray($actual);
        $this->assertArrayHasKey('d001', $actual);
        $this->assertEquals('Marketing', $actual['d001']);
    }

    public function testIsFetchingKeyPairWithEmptyResult(): void
    {
        $actual = $this->adapter->fetchKeyPair('SELECT dept_no,dept_name FROM departments WHERE dept_no = :dept_no', ['dept_no' => 'd099']);

        $this->assertIsArray($actual);
        $this->assertEmpty($actual);
    }

    public function testIsFetchingColumnWithIndexZero(): void
    {
        $actual = $this->adapter->fetchColumn('SELECT dept_no,dept_name FROM departments ORDER BY dept_no', [], 0);

        $this->assertIsArray($actual);
        $this->assertCount(9, $actual);
        $firstValue = reset($actual);
        $this->assertEquals('d001', $firstValue);
    }

    public function testIsFetchingColumnWithIndexOne(): void
    {
        $actual = $this->adapter->fetchColumn('SELECT dept_no,dept_name FROM departments ORDER BY dept_no', [], 1);

        $this->assertIsArray($actual);
        $this->assertCount(9, $actual);
        $firstValue = reset($actual);
        $this->assertEquals('Marketing', $firstValue);
    }

    public function testIsExecutingInserts(): void
    {
        $actual = $this->adapter->execute(
            'INSERT INTO departments (dept_no, dept_name) VALUES (?, ?), (?, ?)',
            ['d010', 'Test Dept 1', 'd011', 'Test Dept 2']
        );

        $this->assertEquals(2, $actual);

        $count = $this->adapter->fetchValue('SELECT COUNT(*) FROM departments WHERE dept_no IN (?, ?)', ['d010', 'd011']);
        $this->assertEquals(2, $count);
    }

    public function testIsExecutingUpdates(): void
    {
        $actual = $this->adapter->execute('UPDATE departments SET dept_name = ?', ['Test Dept']);

        $this->assertEquals(9, $actual);

        $count = $this->adapter->fetchValue('SELECT COUNT(*) FROM departments WHERE dept_name = ?', ['Test Dept']);
        $this->assertEquals(9, $count);
    }

    public function testIsExecutingUpdatesWithNoMatched(): void
    {
        $actual = $this->adapter->execute('UPDATE departments SET dept_name = ? WHERE dept_no = ?', ['Test Dept', 'd010']);

        $this->assertEquals(0, $actual);
    }

    public function testIsThrowingExceptionWithInvalidQuery(): void
    {
        $this->expectException(PDOException::class);
        $this->adapter->execute('UPDATE nop SET dept_name = ? WHERE dept_no = ?', ['Test Dept', 'd010']);
    }

    public function testIsNotCommittingTransactionWithoutCreating(): void
    {
        $this->expectException(PDOException::class);
        $this->adapter->commit();
    }

    public function testIsNotRollingBackTransactionWithoutCreating(): void
    {
        $this->expectException(PDOException::class);
        $this->adapter->rollBack();
    }

    public function testIsGettingLastInsertId(): void
    {
        $this->adapter->execute('INSERT INTO departments (dept_no, dept_name) VALUES (?, ?)', ['d010', 'Test Dept 110']);

        $actual = $this->adapter->getLastInsertId();

        $this->assertEquals(10, $actual);
    }

    public function testIsEscapingValue(): void
    {
        $value = "test'test\ntest";

        $actual = $this->adapter->escapeValue($value);

        $this->assertEquals('test\\\'test\\ntest', $actual);
    }

    protected function createDatabaseFixture(array $options): void
    {
        $connection = new PDO(
            sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $options['host'],
                $options['port'],
                $options['dbname']
            ),
            $options['username'],
            $options['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

        $connection->exec('drop table if exists departments');
        $connection->exec('
            create table departments (
                dept_id int generated by default as identity primary key,
                dept_no text not null,
                dept_name text not null
            )');
        $connection->exec("insert into departments (dept_no, dept_name) values ('d009','Customer Service'),('d005','Development'),('d002','Finance'),
          ('d003','Human Resources'),('d001','Marketing'),('d004','Production'),('d006','Quality Management'),('d008','Research'),('d007','Sales')");
    }

    protected function getAttribute(object $object, string $attributeName)
    {
        $reflector = new ReflectionObject($object);
        $attribute = $reflector->getProperty($attributeName);

        if (!$attribute || $attribute->isPublic()) {
            return $object->$attributeName;
        }

        $attribute->setAccessible(true);
        $value = $attribute->getValue($object);
        $attribute->setAccessible(false);

        return $value;
    }
}
