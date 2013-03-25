<?php

include_once 'includeAll.php';


/**
 * Tests unitaires basiques pour la classe Core.
 * @TODO : Améliorer ces tests afin de les rendres plus pertinents.
 */
class CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Core
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        Core::startEngine();
        $this->object = Core::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Core::opts
     */
    public function testOpts()
    {
       $opts = $this->object->opts();
       $this->assertFalse(is_string($opts));
    }

    /**
     * @covers Core::routes
     */
    public function testRoutes()
    {
        $r = $this->object->routes();
        $this->assertFalse(is_string($r));
    }

    /**
     * @covers Core::startEngine
     */
    public function testStartEngine()
    {
        $this->assertTrue($this->object->isStarted());
    }


    /**
     * @covers Core::getInstance
     */
    public function testGetInstance()
    {
        $i = $this->object->getInstance();
        $this->assertTrue(is_a($i, 'Core'));
    }

    /**
     * @covers Core::getBdd
     */
    public function testGetBdd()
    {
        $i = $this->object->getBdd();
        $this->assertTrue(is_a($i, 'PDO'));
    }

    /**
     * @covers Core::isValidClass
     */
    public function testIsValidClass()
    {
        $this->assertTrue($this->object->isValidClass('astre'));
        $this->assertFalse($this->object->isValidClass('cat'));
    }

    /**
     * @covers Core::isStarted
     */
    public function testIsStarted()
    {
        $this->assertTrue(Core::isStarted());
    }

    /**
     * @covers Core::bdd
     */
    public function testBdd()
    {
        $i = $this->object->bdd()->getDb();
        $this->assertTrue(is_a($i, 'PDO'));
    }

    /**
     * @covers Core::getDevMode
     */
    public function testGetDevMode()
    {
        
        $i = $this->object->getDevMode();
        $this->assertTrue(strcasecmp($i, 'DEBUG') == 0);
    }

    /**
     * @covers Core::getDbPrefix
     */
    public function testGetDbPrefix()
    {
        $this->assertEquals($this->object->getDbPrefix(),
                $this->object->opts()->database->db_prefix);
    }

    /**
     * @covers Core::debug
     */
    public function testDebug()
    {
        $i = $this->object->debug();
        $this->assertTrue(is_a($i, 'Debug'));
    }

    /**
     * @covers Core::getTablesList
     */
    public function testGetTablesList()
    {
        $i = $this->object->getTablesList();
        $this->assertTrue(is_array($i));
    }


  
}
