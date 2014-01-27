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
    define('PHPUnit_MAIN_METHOD', 'HappyFunctionsTest::main');
}


require_once 'PHPUnit/Autoload.php';


/**
 * Test class for HappyFunctions.
 * @package     Happy
 * @subpackage  Tests
 * @category    Functions Tests
 * @copyright   Copyright (c) 2013, Lambdaweb
 * @author      Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since       v1.0
 * @link        http://lambda2.github.io/Happy-Field/
 */
class HappyFunctionsTest extends PHPUnit_Framework_TestCase {

    public $hrule;
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('HappyFunctionsTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
        $this->hrule = new HappyRules('test','');
    }


/************************************************************************
 *                            Basic functions                           *
 ************************************************************************/


    /**
     * tests the rules validation for the
     * basic functions.
     * 
     * @covers Happy\HappyRules::checkRulesExists
     * @covers Happy\HappyRules::cleanArray
     * @covers Happy\HappyRules::checkRules
     * @covers Happy\HappyFunctions::sup
     * @covers Happy\HappyFunctions::inf
     * @covers Happy\HappyFunctions::equ
     * @covers Happy\HappyFunctions::required
     * @covers Happy\HappyFunctions::minLength
     * @covers Happy\HappyFunctions::maxLength
     * @covers Happy\HappyFunctions::exactLength
     * @dataProvider getBasicData
     */
    public function testBasicFunctions($testValue, $testRules, $expected) {

        $errExist = 'Ce test est incorrect';
        $this->hrule->clearRules();
        $this->hrule->addRule($testRules);
        $result = $this->hrule->checkRules($testValue);
        $this->assertEquals(
            $expected, $result, $errExist.$this->hrule->getStrFieldErrors()
            );
    }

    /**
     * Provides data for testing basic functions
     */
    public function getBasicData()
    {
        return array(
          array('5','sup 1',true),
          array('5','sup 5',false),

          array('7','inf 8',true),
          array('7','inf 6',false),

          array('5','equ 5',true),
          array(5,'equ 6',false),

          array('5','required',true),
          array('','required',false),

          array('bobo','minLength 5',false),
          array('bobo','minLength 4',true),
          array('bob','minLength 4',false),

          array('bobo','maxLength 5',true),
          array('bobo','maxLength 4',true),
          array('bobobo','maxLength 5',false),

          array('bobo','exactLength 5',false),
          array('bobo','exactLength 3',false),
          array('bobo','exactLength 4',true)
        );
    }


/************************************************************************
 *                             Type functions                           *
 ************************************************************************/


    /**
     * tests the rules validation for the
     * Type functions.
     * 
     * @covers Happy\HappyRules::checkRulesExists
     * @covers Happy\HappyRules::cleanArray
     * @covers Happy\HappyRules::checkRules
     * @covers Happy\HappyFunctions::alpha
     * @covers Happy\HappyFunctions::alphaNum
     * @covers Happy\HappyFunctions::alphaNumDash
     * @covers Happy\HappyFunctions::num
     * @covers Happy\HappyFunctions::isNum
     * @covers Happy\HappyFunctions::integer
     * @covers Happy\HappyFunctions::decimal
     * @covers Happy\HappyFunctions::natural
     * @covers Happy\HappyFunctions::naturalNotZero
     * 
     * @dataProvider getTypeData
     */
    public function testTypeFunctions($testValue, $testRules, $expected) {

        $errExist = 'Ce test est incorrect : ';
        $this->hrule->clearRules();
        $this->hrule->addRule($testRules);
        $result = $this->hrule->checkRules($testValue);
        $this->assertEquals(
            $expected, $result, $errExist.$this->hrule->getStrFieldErrors()
            );
        //print '['.$testValue.' : '.$testRules.' ? '.$expected.']'."\n";
    }

    /**
     * Provides data for testing Type functions
     */
    public function getTypeData()
    {
        return array(
          array('andre','alpha',true),
          array('_andre_','alpha',false),
          array('_4ndr3','alpha',false),
          array('4ndr3','alpha',false),
          array('5','alpha',false),
          array('5.4','alpha',false),
          array('<3','alpha',false),

          array('andre','alphaNum',true),
          array('_andre_','alphaNum',false),
          array('_4ndr3','alphaNum',false),
          array('4ndr3','alphaNum',true),
          array('5','alphaNum',true),
          array('5.4','alphaNum',false),
          array('<3','alphaNum',false),

          array('andre','alphaNumDash',true),
          array('_andre_','alphaNumDash',true),
          array('_4ndr3','alphaNumDash',true),
          array('4ndr3','alphaNumDash',true),
          array('5','alphaNumDash',true),
          array('5.4','alphaNumDash',false),
          array('<3','alphaNumDash',false),

          array('andre','num',false),
          array('_andre_','num',false),
          array('_4ndr3','num',false),
          array('4ndr3','num',false),
          array('5','num',true),
          array('5.4','num',true),
          array('<3','num',false),

          array('andre','isNum',false),
          array('_andre_','isNum',false),
          array('_4ndr3','isNum',false),
          array('4ndr3','isNum',false),
          array('5','isNum',true),
          array('5.4','isNum',true),
          array('<3','isNum',false),

          array('andre','integer',false),
          array('_andre_','integer',false),
          array('_4ndr3','integer',false),
          array('4ndr3','integer',false),
          array('5','integer',true),
          array('5.4','integer',false),
          array('<3','integer',false),

          array('andre','decimal',false),
          array('_andre_','decimal',false),
          array('_4ndr3','decimal',false),
          array('4ndr3','decimal',false),
          array('5','decimal',false),
          array('5.4','decimal',true),
          array('<3','decimal',false),
          
          array('andre','natural',false),
          array('_andre_','natural',false),
          array('_4ndr3','natural',false),
          array('4ndr3','natural',false),
          array('5','natural',true),
          array('0','natural',true),
          array('5.4','natural',false),
          array('<3','natural',false),

          array('andre','naturalNotZero',false),
          array('_andre_','naturalNotZero',false),
          array('_4ndr3','naturalNotZero',false),
          array('4ndr3','naturalNotZero',false),
          array('5','naturalNotZero',true),
          array('0','naturalNotZero',false),
          array('5.4','naturalNotZero',false),
          array('<3','naturalNotZero',false),
        );
    }




/************************************************************************
 *                             Regex functions                          *
 ************************************************************************/


    /**
     * tests the rules validation for the
     * Regex functions.
     * 
     * @covers Happy\HappyRules::checkRulesExists
     * @covers Happy\HappyRules::cleanArray
     * @covers Happy\HappyRules::checkRules
     * @covers Happy\HappyRules::addRule
     * @covers Happy\HappyRules::clearRules
     * @covers Happy\HappyFunctions::regMatch
     * @covers Happy\HappyFunctions::email
     * @covers Happy\HappyFunctions::emails
     * @covers Happy\HappyFunctions::isBase64
     * 
     * @dataProvider getRegexData
     */
    public function testRegexFunctions($testValue, $testRules, $expected) {

        $errExist = 'Ce test est incorrect';
        $this->hrule->clearRules();
        $this->hrule->addRule($testRules);
        $result = $this->hrule->checkRules($testValue);
        $this->assertEquals(
            $expected, $result, $errExist.$this->hrule->getStrFieldErrors()
            );
        //print '['.$testValue.' : '.$testRules.' ? '.$expected.']'."\n";
    }

    /**
     * Provides data for testing Regex functions
     */
    public function getRegexData()
    {
        return array(
          array('andre','regMatch /^([a-z])+$/i',true),
          array('_andre_','regMatch /^([a-z])+$/i',false),
          array('_4ndr3','regMatch /^([a-z])+$/i',false),
          array('4ndr3','regMatch /^([a-z])+$/i',false),
          array('5','regMatch /^([a-z])+$/i',false),
          array('5.4','regMatch /^([a-z])+$/i',false),
          array('<3','regMatch /^([a-z])+$/i',false),

          array('andre.aubin@lambdaweb.fr','email',true),
          array('andre.aubin@lambdaweb','email',false),
          array('andre.aubin@.fr','email',false),
          array('andre.aubinatlambdaweb.fr','email',false),

          array('
            andre.aubin@lambdaweb.fr,
            contact@lambdaweb.fr,
            admin@lambdaweb.fr,
            lucille.arragon@lambdaweb.fr',
            'emails',true),
          array('
            andre.aubin@lambdaweb.fr',
            'emails',true),
          array('
            andre.aubin@lambdaweb,
            contact@lambdaweb.fr,
            admin@lambdaweb.fr,
            lucille.arragon@lambdaweb.fr',
            'emails',false),
          array('
            andre.aubin@lambdaweb.fr,
            contact@lambdaweb.fr,
            admin@.fr,
            lucille.arragon@lambdaweb.fr',
            'emails',false),
          array('
            admin@.fr',
            'emails',false),

          array('SGkh','isBase64',true),
          array('Qm9uam91ciB0b3V0IGxlIG1vbmRlICEgQ29tbWVudCBhbGxleiB2b3VzID8=',
            'isBase64',true),
          array('Qm9uam91ciB0<0IGxlIG1vbmRlICEgQ29tPw==','isBase64',false)
        );
    }



}

// Call HappyRulesTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'HappyFunctionsTest::main') {
    HappyFunctionsTest::main();
}
?>