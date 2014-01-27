<?php

/*
 * This file is part of HappyField, a field parser for the Moon Framework.
 * See more at the GitHub page :
 * - Of this project @[ https://github.com/lambda2/Happy-Field ]
 * - Of the Moon project @[ https://github.com/lambda2/Moon ]
 *
 * ----------------------------------------------------------------------------
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once '../HappyRules.php';

use Happy\HappyRules as HappyRules;

// Call HappyRulesTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'HappyRulesTest::main');
}


require_once 'PHPUnit/Autoload.php';


/**
 * Test class for HappyRules.
 * @package     Happy
 * @subpackage  Tests
 * @category    Rules Tests
 * @copyright   Copyright (c) 2013, Lambdaweb
 * @author      Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since       v1.0
 * @link        http://lambda2.github.io/Happy-Field/
 */
class HappyRulesTest extends PHPUnit_Framework_TestCase {


    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('HappyRulesTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

    /**
     * tests the HappyRules constructor
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::parseRules
     * @covers Happy\HappyRules::cleanArray
     */
    public function testConstruct() {
        $err = 'Les regles ne sont pas converties en tableau dans le constructeur de HappyField !';
        $rules = array();
        $rules[] = new HappyRules('testOne','');
        $rules[] = new HappyRules('testTwo','sup');
        $rules[] = new HappyRules('testThree','sup 8');
        $rules[] = new HappyRules('testFour','sup 8 | inf 10');

        foreach ($rules as $rule) {
            $rulesv = $rule->getRules();
            $this->assertTrue(is_array($rulesv),$err);
        }
    }

    /**
     * tests the HappyRules isRuleMethod()
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::isRuleMethod
     */
    public function testRuleMethod() {

        $rules = new HappyRules('testOne','sup 10');

        $this->assertTrue(
            $rules->isRuleMethod('isRuleMethod'),
            'it is a rule method !');

        $this->assertFalse(
            $rules->isRuleMethod('notAMethod'),
            'it is not a rule method !');

        $this->assertTrue(
            $rules->isRuleMethod('checkRulesExists'),
            'it is a rule method !');

        $this->assertTrue(
            $rules->isRuleMethod('sameThat'),
            'it is a rule method !');
    }


    /**
     * tests the HappyRules isRuleMethod()
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::isHappyFunction
     */
    public function testHappyFunction() {

        $rules = new HappyRules('testOne','sup 10');

        $this->assertTrue(
            $rules->isHappyFunction('sup'),
            'it is a Happy Function !');

        $this->assertFalse(
            $rules->isHappyFunction('isRuleMethod'),
            'it is not a Happy Function !');

        $this->assertTrue(
            $rules->isHappyFunction('email'),
            'it is a Happy Function !');
    }


    /**
     * tests the rules existence
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::checkRulesExists
     * @covers Happy\HappyRules::cleanArray
     */
    public function testCheckRulesExists() {
        $errExist = 'Une regle devrait exister, mais ce n\'est pas le cas...';
        $errNotExist = 'Une regle ne devrait pas exister !';

        $rules = new HappyRules('testOne','');
        $this->assertTrue($rules->checkRulesExists(),
            $errExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testTwo','sup');
        $this->assertTrue($rules->checkRulesExists(),
            $errExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testThree','sup 8');
        $this->assertTrue($rules->checkRulesExists(),
            $errExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testFour','sup 8 | inf 10');
        $this->assertTrue($rules->checkRulesExists(),
            $errExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testFive','blabla 8');
        $this->assertFalse($rules->checkRulesExists(),
            $errNotExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testSix','sup 8 | blabla 10');
        $this->assertFalse($rules->checkRulesExists(),
            $errNotExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testArray',array('sup 8','inf 10'));
        $this->assertTrue($rules->checkRulesExists(),
            $errNotExist.$rules->getStrDebugErrors());

        $rules = new HappyRules('testNull',null);
        $this->assertTrue($rules->checkRulesExists(),
            $errNotExist.$rules->getStrDebugErrors());
    }

    /**
     * tests the rules validation
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::checkRulesExists
     * @covers Happy\HappyRules::cleanArray
     * @covers Happy\HappyRules::checkRules
     * @dataProvider getTestValues
     */
    public function testCheckRulesValid($testValue, $testRules, $expected) {

        $errExist = 'Ce test est incorrect';

        $rules = new HappyRules('test',$testRules);
        $result = $rules->checkRules($testValue);
        $this->assertEquals($expected, $result, $errExist.$rules->getStrFieldErrors());
    }

    /**
     * Returns test values for the testCheckRulesValid test
     */
    public function getTestValues()
    {
        return array(
          array('5','',true),
          array('5','sup 1',true),
          array('5','equ 5',true),
          array(5,'equ 5',true),
          array('5','sup 1|inf 6',true),
          array('7','sup 8',false),
          array('7','inf 6',false),
          array('7','sup 8|inf 6',false),
          array('7','sup 6|equ 6',false),
          array('7','sup 6|inf 6',false)
        );
    }

    /**
     * tests the getter and the setter of Field
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::getField
     * @covers Happy\HappyRules::setField
     */
    public function testGetSetField() {
        $rules = new HappyRules('test','sup 6|inf 10','Un label');
        $this->assertEquals($rules->getField(), 'test');
        $rules->setField('autreTest');
        $this->assertEquals($rules->getField(), 'autreTest');
    }

    /**
     * tests the getter and the setter of Label
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::getLabel
     * @covers Happy\HappyRules::setLabel
     */
    public function testGetSetLabel() {
        $rules = new HappyRules('test','sup 6|inf 10','Un label');
        $this->assertEquals($rules->getLabel(), 'Un label');
        $rules->setLabel('un autre label');
        $this->assertEquals($rules->getLabel(), 'un autre label');
    }

    /**
     * tests the getter and the setter of Rules
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::getRules
     * @covers Happy\HappyRules::setRules
     */
    public function testGetSetRules() {
        $rules = new HappyRules('test','sup 6|inf 10','Un Rules');
        $this->assertEquals($rules->getRules(), array('sup 6','inf 10'));
        $rules->setRules(array('equ 6','inf 12'));
        $this->assertEquals($rules->getRules(), array('equ 6','inf 12'));
    }

    /**
     * tests the getter and the setter of FieldErrors
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::getFieldErrors
     * @covers Happy\HappyRules::setFieldErrors
     * @covers Happy\HappyRules::getStrFieldErrors
     * @covers Happy\HappyRules::getDebugErrors
     * @covers Happy\HappyRules::setDebugErrors
     * @covers Happy\HappyRules::getStrDebugErrors
     */
    public function testGetSetErrors() {
        $rules = new HappyRules('test','sup 6|inf 10','Un FieldErrors');
        $err = 'error';
        $rules->getFieldErrors();
        $rules->getDebugErrors();
        $rules->getStrFieldErrors();
        $rules->getStrDebugErrors();
        $rules->setFieldErrors($err);
        $rules->setDebugErrors($err);
    }

}

// Call HappyRulesTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'HappyRulesTest::main') {
    HappyRulesTest::main();
}
?>