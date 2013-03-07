<?php

include_once '/var/www/lwf/System/Classes/Forms/Field.php';
include_once '/var/www/lwf/System/Classes/Forms/CheckBox.php';

class CheckBoxTest extends PHPUnit_Framework_TestCase {

    /**
     * @var CheckBox
     */
    protected $object;

    protected function setUp() {
        $this->object = new CheckBox("cbName", "cbValue");
    }

    /**
     * @covers CheckBox::isChecked
     * @depends testSetChecked
     */
    public function testIsChecked() {
        $this->assertFalse($this->object->isChecked());
        $this->object->setChecked(True);
        $this->assertTrue($this->object->isChecked());
    }

    /**
     * @covers CheckBox::setChecked
     */
    public function testSetChecked() {
        $this->object->setChecked(True);
        $this->assertTrue($this->object->isChecked());
    }

    /**
     * @covers CheckBox::getHtml
     */
    public function testGetHtml() {
        $this->assertEquals(
                '<input type="checkbox" value="cbValue" name="cbName" >',
                $this->object->getHtml());
    }

}
