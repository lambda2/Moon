<?php

//include_once '/var/www/moon/Helpers/common.php'; //-> ne marche pas !
include_once '/var/www/moon/System/Classes/Forms/Field.php';

/**
 * Classe de Test pour Field
 * On crée un mock pour pouvoir tester les méthodes de la classe
 * abstraite Field
 * @see Field
 */
class FieldMock extends Field {
    
    /**
     * Et on lui fournit des valeurs arbitraires par défaut que nous
     * allons faire évoluer
     */
    public function __construct() {
        $this->type = "m_type";
        $this->name = "m_name";
        $this->id = "m_id";
        $this->value = "m_value";
        $this->placeholder = "m_placeholder";

        $this->classes = array("classOne","classTwo");
        $this->datas = array(
            "dataOne"=>"dataOneContent","dataTwo"=>"dataTwoContent");
        $this->customAttributes = array(
            "caOne"=>"caOneContent","caTwo"=>"caTwoContent");

        $this->required = false;
        $this->enabled = true;
        $this->visible = true;
    }
    
    /**
     * Cette méthode n'étant pas implémentée, 
     * nous n'allons rien y faire de miraculeux
     * @return string la chaine "html"
     */
    public function getHtml() {
        return "html";
    }    
};

class FieldTest extends PHPUnit_Framework_TestCase {

    /**
     * @var FieldMock
     */
    protected $object;

    protected function setUp() {
        $this->object = new FieldMock();
    }


    /**
     * @covers Field::addClass
     */
    public function testAddClass() {
        
        $expected = array("classOne","classTwo");
        $this->assertEquals($expected,$this->object->getClasses());
        
        $this->object->addClass("red");
        $expected = array("classOne","classTwo","red");
        $this->assertEquals($expected,$this->object->getClasses());
    }
    
    /**
     * @covers Field::getClasses
     */
    public function testGetClasses() {
        
        $expected = array("classOne","classTwo");
        $this->assertEquals($expected,$this->object->getClasses());
        
        $this->object->addClass("red");
        $expected = array("classOne","classTwo","red");
        $this->assertEquals($expected,$this->object->getClasses());
    }

    /**
     * @covers Field::getPlaceholder
     * @depends testSetPlaceholder
     */
    public function testGetPlaceholder() {
        $this->assertEquals("m_placeholder",$this->object->getPlaceholder());
        $this->object->setPlaceholder("new placeholder");
        $this->assertEquals("new placeholder",$this->object->getPlaceholder());
    }

    /**
     * @covers Field::setPlaceholder
     */
    public function testSetPlaceholder() {
        
        $this->object->setPlaceholder("very new placeholder");
        $this->assertEquals("very new placeholder",$this->object->getPlaceholder());
    }

    /**
     * @covers Field::getValue
     * @depends testSetValue
     */
    public function testGetValue() {
        $this->assertEquals("m_value",$this->object->getValue());
        $this->object->setValue("new value");
        $this->assertEquals("new value",$this->object->getValue());
    }

    /**
     * @covers Field::setValue
     */
    public function testSetValue() {
        
        $this->object->setValue("very new value");
        $this->assertEquals("very new value",$this->object->getValue());
    }

    /**
     * @covers Field::isEnabled
     * @depends testSetEnabled
     */
    public function testIsEnabled() {
        $this->assertTrue($this->object->isEnabled(),"is enabled");
        $this->object->setEnabled(False);
        $this->assertFalse($this->object->isEnabled(),"is not enabled");
    }

    /**
     * @covers Field::setEnabled
     */
    public function testSetEnabled() {
        $this->object->setEnabled(False);
        $this->assertFalse($this->object->isEnabled(),"is not enabled");
    }

    /**
     * @covers Field::isVisible
     * @depends testSetVisible
     */
    public function testIsVisible() {
        $this->assertTrue($this->object->isVisible(),"is Visible");
        $this->object->setVisible(False);
        $this->assertFalse($this->object->isVisible(),"is not Visible");
    }

    /**
     * @covers Field::setVisible
     */
    public function testSetVisible() {
        $this->object->setVisible(False);
        $this->assertFalse($this->object->isVisible(),"is not Visible");
    }

    /**
     * @covers Field::getName
     * @depends testSetName
     */
    public function testGetName() {
        $this->assertEquals("m_name",$this->object->getName());
        $this->object->setName("new name");
        $this->assertEquals("new name",$this->object->getName());
    }

    /**
     * @covers Field::setName
     */
    public function testSetName() {
        $this->object->setName("new name");
        $this->assertEquals("new name",$this->object->getName());
    }

    /**
     * @covers Field::getId
     * @depends testSetId
     */
    public function testGetId() {
        $this->assertEquals("m_id",$this->object->getId());
        $this->object->setId("new id");
        $this->assertEquals("new id",$this->object->getId());
    }

    /**
     * @covers Field::setId
     */
    public function testSetId() {
        $this->object->setId("new id");
        $this->assertEquals("new id",$this->object->getId());
    }

    /**
     * @covers Field::isRequired
     * @depends testSetRequired
     */
    public function testIsRequired() {
        $this->assertFalse($this->object->isRequired(),"is not Required");
        $this->object->setRequired(True);
        $this->assertTrue($this->object->isVisible(),"is Required");
    
    }

    /**
     * @covers Field::setRequired
     */
    public function testSetRequired() {
        $this->object->setRequired(True);
        $this->assertTrue($this->object->isVisible(),"is Required");
    }

    /**
     * @covers Field::clearClasses
     * @depends testaddClass
     */
    public function testClearClasses() {
        $expected = array("classOne","classTwo");
        $this->assertEquals($expected,$this->object->getClasses());
        
        $this->object->addClass("red");
        $expected = array("classOne","classTwo","red");
        $this->assertEquals($expected,$this->object->getClasses());
        
        $this->object->clearClasses();
        $expected = array();
        $this->assertEquals($expected,$this->object->getClasses());
        
    }

    /**
     * @covers Field::addData
     */
    public function testAddData() {
        $expected = array(
            "dataOne"=>"dataOneContent","dataTwo"=>"dataTwoContent");
        $this->assertEquals($expected,$this->object->getDatas());
        
        $this->object->addData("color","red");
        $expected = array(
            "dataOne"=>"dataOneContent",
            "dataTwo"=>"dataTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getDatas());
        
    }

    /**
     * @covers Field::removeData
     * @depends testAddData
     */
    public function testRemoveData() {
        
        $expected = array(
            "dataOne"=>"dataOneContent","dataTwo"=>"dataTwoContent");
        $this->assertEquals($expected,$this->object->getDatas());
        
        $this->object->addData("color","red");
        $expected = array(
            "dataOne"=>"dataOneContent",
            "dataTwo"=>"dataTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getDatas());
        
        $this->object->removeData("color");
        $expected = array(
            "dataOne"=>"dataOneContent",
            "dataTwo"=>"dataTwoContent");
        $this->assertEquals($expected,$this->object->getDatas());
    }

    /**
     * @covers Field::clearData
     * @depends testAddData
     */
    public function testClearData() {
        $expected = array(
            "dataOne"=>"dataOneContent","dataTwo"=>"dataTwoContent");
        $this->assertEquals($expected,$this->object->getDatas());
        
        $this->object->addData("color","red");
        $expected = array(
            "dataOne"=>"dataOneContent",
            "dataTwo"=>"dataTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getDatas());
        
        $this->object->clearData();
        $expected = array();
        $this->assertEquals($expected,$this->object->getDatas());
    }

    /**
     * @covers Field::addCustomAttribute
     */
    public function testAddCustomAttribute() {
        $expected = array(
            "caOne"=>"caOneContent","caTwo"=>"caTwoContent");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
        
        $this->object->addCustomAttribute("color","red");
        $expected = array(
            "caOne"=>"caOneContent",
            "caTwo"=>"caTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
    }

    /**
     * @covers Field::removeCustomAttribute
     */
    public function testRemoveCustomAttribute() {
        $expected = array(
            "caOne"=>"caOneContent","caTwo"=>"caTwoContent");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
        
        $this->object->addCustomAttribute("color","red");
        $expected = array(
            "caOne"=>"caOneContent",
            "caTwo"=>"caTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
        
        $this->object->removeCustomAttribute("color");
        $expected = array(
            "caOne"=>"caOneContent",
            "caTwo"=>"caTwoContent");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
    }

    /**
     * @covers Field::clearCustomAttribute
     */
    public function testClearCustomAttribute() {
        $expected = array(
            "caOne"=>"caOneContent","caTwo"=>"caTwoContent");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
        
        $this->object->addCustomAttribute("color","red");
        $expected = array(
            "caOne"=>"caOneContent",
            "caTwo"=>"caTwoContent",
            "color"=>"red");
        $this->assertEquals($expected,$this->object->getCustomAttributes());
        $this->object->clearCustomAttribute();
        $expected = array();
        $this->assertEquals($expected,$this->object->getCustomAttributes());
    }

}
