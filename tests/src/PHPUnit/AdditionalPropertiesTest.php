<?php

namespace Yaoi\Schema\Tests\PHPUnit;


use Yaoi\Schema\SchemaLoader;
use Yaoi\Schema\OldSchema;

class AdditionalPropertiesTest extends \PHPUnit_Framework_TestCase
{

    public function testBasic()
    {
        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => array(
                'type' => 'integer'
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);

        $object = $schema->import(
            (object)array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testRef()
    {
//        $this->markTestSkipped('Not implemented');
        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => array(
                '$ref' => '#/def'
            ),
            'def' => array(
                'type' => 'integer',
            ),
        );


        $schema = SchemaLoader::create()->readSchema($schemaData);
        //print_r($schema);

        $object = $schema->import(
            array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }

    public function testWithProperties()
    {

        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => array(
                '$ref' => '#/def'
            ),
            'properties' => array(
                'zero' => array(
                    'type' => 'string',
                ),
            ),
            'def' => array(
                'type' => 'integer',
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);
        $object = $schema->import(
            array('zero' => '0', 'one' => 1, 'two' => 2)
        );

        $this->assertSame('0', $object->zero);
        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testNonRootSchema()
    {
        $schemaData = array(
            'type' => 'object',
            'properties' => array(
                'deeper' => array(
                    'type' => 'object',
                    'additionalProperties' => array(
                        'type' => 'integer'
                    ),
                )
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);

        //print_r($schema);

        $object = $schema->import(
            array(
                'deeper' => array('one' => 1, 'two' => 2)
            )
        );

        $this->assertSame(1, $object->deeper->one);
        $this->assertSame(2, $object->deeper->two);


    }


}