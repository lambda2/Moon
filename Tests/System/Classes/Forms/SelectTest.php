<?php

include_once '/var/www/moon/System/Classes/Exceptions.php';
include_once '/var/www/moon/System/Classes/Configuration.php';
include_once '/var/www/moon/System/Classes/Forms/Field.php';
include_once '/var/www/moon/System/Classes/Forms/Select.php';
include_once '/var/www/moon/System/Classes/Forms/Option.php';

class SelectTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Select
     */
    protected $object;


    protected function setUp() {
        $this->object = new Select("nSelect", array());
    }



    /**
     * @covers Select::addOptionObject
     * @covers Select::addOption
     * @covers Select::addOptionsArray
     */
    public function testAddOptionObject() {
        
        $this->assertEquals(array(),$this->object->getOptions());
        $this->object->addOption("vOption", "tOption");
        $this->assertEquals(
                array(new Option("vOption", "tOption")),
                $this->object->getOptions());
        
        $test = new Option("otherOption", "anotherOption");
        $this->object->addOptionObject($test);
        $this->assertEquals(
                array(
                    new Option("vOption", "tOption"),
                    $test),
                $this->object->getOptions());
        
        
        $testTwo = array(
            new Option("dudu", "dodo"),
            new Option("lulu", "lolo"),
            new Option("mumu", "momo"));
        $this->object->addOptionsArray($testTwo);
        $this->assertEquals(
                array(
                    new Option("vOption", "tOption"),
                    $test,
                    new Option("dudu", "dodo"),
                    new Option("lulu", "lolo"),
                    new Option("mumu", "momo")),
                $this->object->getOptions());
    }



    /**
     * @covers Select::clearOptions
     */
    public function testClearOptions() {
        $testTwo = array(
            new Option("dudu", "dodo"),
            new Option("lulu", "lolo"),
            new Option("mumu", "momo"));
        $this->object->addOptionsArray($testTwo);
        $this->assertEquals(
                array(
                    new Option("dudu", "dodo"),
                    new Option("lulu", "lolo"),
                    new Option("mumu", "momo")),
                $this->object->getOptions());
        $this->object->clearOptions();
        $this->assertEquals(array(),$this->object->getOptions());
    }

    /**
     * @covers Select::getHtml
     */
    public function testGetHtml() {
        $testTwo = array(
            new Option("dudu", "dodo"),
            new Option("lulu", "lolo"),
            new Option("mumu", "momo"));
        $this->object->addOptionsArray($testTwo);
        $this->assertEquals(
                '<select type="select" name="nSelect" >'.
                    '<option value="dudu" >dodo</option>'.
                    '<option value="lulu" >lolo</option>'.
                    '<option value="mumu" >momo</option>'.
                    '</select>',
                $this->object->getHtml());
    }

}
