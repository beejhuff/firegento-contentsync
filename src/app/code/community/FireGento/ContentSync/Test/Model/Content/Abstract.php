<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_ContentSync
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
/**
 * Test Abstract Model
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Test_Model_Content_Abstract extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FireGento_ContentSync_Model_Content_Cms_Abstract
     */
    protected $model = NULL;

    /**
     * sets up the contentsync/content_cms_abstract
     */
    protected function setUp()
    {
        // mock it, because class is abstract
        $this->model = $this->getModelMock('contentsync/content_cms_abstract', array('getConfig'));
    }

    /**
     * @param $type storage type
     */
    private function setUpStorageMock($type)
    {
        $mockStorage = $this->getModelMock('contentsync/storage_abstract');
        $this->replaceByMock('model', 'contentsync/storage_' . $type, $mockStorage);
    }

    /**
     * sets up the config and storage mocks
     * @param $storageType storage type
     */
    private function setUpStorage($storageType)
    {
        $this->setUpStorageMock($storageType);
    }

    public function testGetStorageReturnInstance()
    {
        $this->setUpStorage('foobar');

        $this->assertInstanceOf(
            'FireGento_ContentSync_Model_Storage_Abstract',
            $this->model->getStorage('cms_page')
        );
    }

    public function testGetStorageVoid()
    {
        $this->assertInstanceOf(
            'FireGento_ContentSync_Model_Storage_Void',
            $this->model->getStorage('foo')
        );
    }

    public function testStoreDataInStorage()
    {
        $dataValues = array(
            array(
                'creation_date' => 11,
                'update_date'   => 12,
            ),
            array(
                'creation_date' => 21,
                'update_date'   => 22,
            )
        );

        $mockStorage = $this->getMock('Varien_Object', array('storeData'));
        $mockStorage
            ->expects($this->once())
            ->method('storeData')
            ->with(
                $dataValues,
                'cms_page'
            );

        // using a inherited class of Content_Abstract. protected storeDataInStorage is only called from them
        $model = $this->getModelMock(
            'contentsync/content_cms_page',
            array('getStorage')
        );
        $model
            ->expects($this->once())
            ->method('getStorage')
            ->will(
                $this->returnValue(
                     $mockStorage
                )
            );

        $mocks = new FireGento_ContentSync_Test_Model_Content_Abstract_Mocks($this);
        $classAlias = 'cms/page_collection';
        $resourceCmsPageCollection = $mocks->getResourceCollectionModelMock(
            $classAlias,
            $dataValues
        );
        $this->replaceByMock('resource_model', $classAlias, $resourceCmsPageCollection);

        $this->assertNull(
            $model->storeData()
        );
    }

    public function testLoadDataFromStorage()
    {
        $mockStorage = $this->getMock('Varien_Object', array('loadData'));
        $mockStorage
            ->expects($this->once())
            ->method('loadData')
            ->with(
                'cms_page'
            )
            ->will($this->returnValue(array()));

        // using a inherited class of Content_Abstract. protected storeDataInStorage is only called from them
        $model = $this->getModelMock(
            'contentsync/content_cms_page',
            array('getStorage')
        );
        $model
            ->expects($this->once())
            ->method('getStorage')
            ->will(
                $this->returnValue(
                     $mockStorage
                )
            );

        $this->assertNull(
            $model->loadData()
        );
    }
}
