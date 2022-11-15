<?php

namespace Drupal\tests\helfi_tpr\Kernel;


use Drupal\helfi_tpr\Entity\Service;
use Drupal\views\Views;
use Drupal\Tests\helfi_tpr\Kernel\MigrationTestBase;


/**
 * Tests the ServiceIdArgument views argument handler.
 *
 * @group helfi_tpr
 */
class ServiceIdArgumentTest extends MigrationTestBase
{

  /**
   * {@inheritdoc}
   */
  public function setUp(): void
  {
    parent::setUp();
  }


  public function testServiceIdArgument()
  {
    // Service migration has a soft dependency on unit migration.
    $this->runUnitMigrate();
    $this->runServiceMigrate();

    // Test for service_id.
    $view = Views::getView('test_service_list');
    $view->setDisplay();
    $view->initHandlers();
    $view->setArguments(['id_or_service_id' => '|10554']);
    $view->preExecute();
    $view->execute();
    foreach ($view->result as &$row) {
      $this->assertEquals('10554', $view->field['service_id']->getValue($row));
    }
    $view->destroy();

    // Test for id.
    $view = Views::getView('test_service_list');
    $view->setDisplay();
    $view->initHandlers();
    $view->setArguments(['id_or_service_id' => '1|']);
    $view->preExecute();
    $view->execute();

    foreach ($view->result as &$row) {
      $this->assertEquals('1', $view->field['id']->getValue($row));
    }
    $view->destroy();

    // Test for service_id and id.
    $view = Views::getView('test_service_list');
    $view->setDisplay();
    $view->initHandlers();
    $view->setArguments(['id_or_service_id' => '7705|10014']);
    $view->preExecute();
    $view->execute();
    foreach ($view->result as &$row) {
      $this->assertEquals('7705', $view->field['id']->getValue($row));
      $this->assertEquals('10014', $view->field['service_id']->getValue($row));
    }
    $view->destroy();
  }
}
