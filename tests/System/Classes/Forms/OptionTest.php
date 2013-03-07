<?php


include_once '/var/www/lwf/System/Classes/Exceptions.php';
include_once '/var/www/lwf/System/Classes/Configuration.php';
include_once '/var/www/lwf/System/Classes/Forms/Field.php';
include_once '/var/www/lwf/System/Classes/Forms/Select.php';
include_once '/var/www/lwf/System/Classes/Forms/Option.php';

class OptionTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Option
     */
    protected $object;


    protected function setUp() {
        $this->object = new Option("vOption", "tOption");
    }



    /**
     * @covers Option::getHtml
     */
    public function testGetHtml() {
        $this->assertEquals('<option value="vOption" >tOption</option>',
                $this->object->getHtml());
    }

}
