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


require_once '../HappyField.php';

use Happy\HappyField as HappyField;

// Call HappyFieldTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'HappyFieldTest::main');
}


require_once 'PHPUnit/Autoload.php';


/**
 * Test class for HappyField.
 * @package     Happy
 * @subpackage  Tests
 * @category    Rules Tests
 * @copyright   Copyright (c) 2013, Lambdaweb
 * @author      Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since       v1.0
 * @link        http://lambda2.github.io/Happy-Field/
 */
class HappyFieldTest extends PHPUnit_Framework_TestCase {

    public $hfield; 

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('HappyFieldTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() { $this->hfield = new HappyField(); }


    /**
     * Returns sample post array
     */
    public function getSamplePost()
    {
        return array(
            'nom' => 'Aubin',
            'prenom' => 'Andre',
            'age' => 20,
            'email' => 'contact@lambdaweb.fr'
        );
    }

    /**
     * Returns sample post (with duplicates) array
     */
    public function getSampleDuplicatePost()
    {
        return array(
            'nom' => 'Aubin',
            'conf_nom' => 'Aubinn',
            'prenom' => 'Andre',
            'conf_prenom' => 'Andre',
            'age' => 20,
            'email' => 'contact@lambdaweb.fr'
        );
    }

    /**
     * Returns sample array with test rules.
     */
    public function getSampleTestTree()
    {
        return array(
            'nom' => 
                array(
                    'label' => 'Nom de famille',
                    'rules' => 'minLength 3|maxLength 14|alpha'
                ),
            'email' => 
                array(
                    'label' => 'Adresse e-mail',
                    'rules' => 'email'
                ),
        );
    }

    /**
     * tests the add rules from array system
     * @covers Happy\HappyField::__construct
     * @covers Happy\HappyField::loadRulesFromArray
     * @covers Happy\HappyField::showErrors
     */
    public function testAddRuleFromArray() {

        $this->hfield->showErrors(false);

        $rules = $this->getSampleTestTree();

        $this->assertTrue(
            $this->hfield->setFields($this->getSamplePost()), 
            'unable to add sample fields to validation.'
            );

        $this->hfield->loadRulesFromArray($rules);

        echo "\nNom : \n";
        echo "\trules : ".join(',',$this->hfield->getRule('nom')->getRules())."\n";
        echo "\tlabel : ".$this->hfield->getRule('nom')->getLabel()."\n";

        echo "\nEmail: \n";
        echo "\trules : ".join(',',$this->hfield->getRule('email')->getRules())."\n";
        echo "\tlabel : ".$this->hfield->getRule('email')->getLabel()."\n";


        $this->assertTrue(
            $this->hfield->check(), 
            'Cannot check with array loading !'
            );

    }

    /**
     * tests the add rules overriding
     * @covers Happy\HappyField::__construct
     * @covers Happy\HappyField::addRule
     * @covers Happy\HappyField::setFields
     * @covers Happy\HappyField::showErrors
     */
    public function testAddRuleCheck() {

        $this->hfield->showErrors(false);

        $this->assertTrue(
            $this->hfield->setFields($this->getSamplePost()), 
            'unable to add sample fields to validation.'
            );

        $this->assertTrue(
            $this->hfield->addRule('prenom','alpha','surname'),
            'The supplied rule (alpha) is false !'
            );


        $this->assertTrue(
            $this->hfield->addRule('prenom','',''),
            'The supplied rule (None) is false !'
            );

        $this->assertTrue(
            $this->hfield->addRule('prenom','num','Un beau prénom !'),
            'The supplied rule (num) is false !'
            );

        $this->assertFalse(
            $this->hfield->check(), 
            'The HappyField is not correct (prenom is not a alpha ?!)'
            );


    }

    /**
     * tests the rules existence
     * @covers Happy\HappyField::__construct
     * @covers Happy\HappyField::addRule
     * @covers Happy\HappyField::setFields
     * @covers Happy\HappyField::showErrors
     */
    public function testSimpleRuleCheck() {

        $this->hfield->showErrors(false);

        $this->assertTrue(
            $this->hfield->setFields($this->getSamplePost()), 
            'unable to add sample fields to validation.'
            );

        $this->assertTrue(count($this->hfield->getFields()) > 0, 'fields are empty !');

        $this->assertFalse(
            $this->hfield->setFields('Trolololoooo'), 
            'Able to add STRING fields to validation ?'
            );

        $this->assertTrue(count($this->hfield->getFields()) > 0, 'fields are empty !');


        $this->assertFalse(
            $this->hfield->check(), 
            'The HappyField should not check without rules !'
            );

        $this->assertFalse(
            $this->hfield->addRule('nom','donotexists','name'), 
            'The supplied rule is false !'
            );

        $this->assertTrue(
            $this->hfield->addRule('nom','alpha','name'), 
            'The supplied rule (alpha) is true !'
            );

        // var_dump($this->hfield->getFields());

        $this->assertTrue(
            $this->hfield->check(), 
            'The HappyField is correct (name is alphanumeric... no ?)'
            );

        $this->assertTrue(
            $this->hfield->addRule('age','num|naturalNotZero','age'),
            'The supplied rule (alpha) is true !'
            );

        $this->assertTrue(
            $this->hfield->check(), 
            'The HappyField is correct (name is alphanumeric... no ?)'
            );

        $this->assertTrue(
            $this->hfield->addRule('prenom','num','surname'),
            'The supplied rule (num) is false !'
            );

        $this->assertFalse(
            $this->hfield->check(), 
            'The HappyField is not correct (prenom is not a number !)'
            );


    }


    /**
     * tests the HappyRules sameThat()
     * @covers Happy\HappyRules::__construct
     * @covers Happy\HappyRules::sameThat
     */
    public function testSameThat() {

        
        $this->hfield->showErrors(false);

        $this->assertTrue(
            $this->hfield->setFields($this->getSampleDuplicatePost()), 
            'unable to add sample (duplicates) fields to validation.'
            );

        $this->assertTrue(
            $this->hfield->addRule('prenom','alpha','surname'),
            'The supplied rule (alpha) is false !'
            );

        $this->assertTrue(
            $this->hfield->check(), 
            'The HappyField is not correct (prenom is not a alpha ?!)'
            );

        $this->assertTrue(
            $this->hfield->addRule('conf_prenom','sameThat prenom','surname confirmation'),
            'The supplied rule (alpha) is false !'
            );

        $this->assertTrue(
            $this->hfield->check(), 
            'The HappyField is not correct (prenom is not a alpha ?!)'
            );


        $this->assertTrue(
            $this->hfield->addRule(
                'conf_nom',
                'sameThat nom',
                'surname confirmation'),
            'The supplied rule (sameThat) is false !'
            );

        $this->assertFalse(
            $this->hfield->check(), 
            'The HappyField is not correct (sameThat nom) '
            );

        
    
    }

    

}

// Call HappyFieldTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'HappyFieldTest::main') {
    HappyFieldTest::main();
}
?>