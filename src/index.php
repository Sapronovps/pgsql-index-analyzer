<?php

require '../vendor/autoload.php';

// Запуск тестов sudo docker-compose run --rm php php ./vendor/phpunit/phpunit/phpunit -c phpunit.xml --testsuite pgsqlIndexAnalyzer

$options = new \Sapronovps\PgsqlIndexAnalyzer\Option\Options();
$options
    ->setHost('10.243.182.166')
    ->setDbName('wms')
    ->setUser('sapronovps')
    ->setPassword('93011c8a40');

$options
    ->setHost('wms.local')
    ->setDbName('wms')
    ->setUser('wms')
    ->setPassword('1111');

$connection = new \Sapronovps\PgsqlIndexAnalyzer\Connection\Connection($options);

$pgsqlIndexAnalyzer = new \Sapronovps\PgsqlIndexAnalyzer\PgsqlIndexAnalyzer($connection);

$tables = [
    'doc_spta_picking_task',
    'doc_internal_purchase',
    'doc_spta_picking',
    'doc_internal_shipment_task',
    'doc_change_quality',
    'doc_assessment',
    'doc_shipment_wave_correction',
    'doc_write_off',
    'doc_supply_batch_correction',
    'doc_cross_docking_purchase',
    'doc_internal_purchase_task',
    'doc_universal_task',
    'doc_purchase_task',
    'doc_movement_task',
    'doc_movement_correction',
    'doc_service_center_shipment_task',
    'doc_purchase_order_correction',
    'doc_shipment_order_correction',
    'doc_cross_docking_movement',
    'doc_purchase_return_task',
    'doc_external_service_center_client_resolution',
    'doc_repair',
    'doc_cross_docking_internal_purchase_task',
    'doc_resale',
    'doc_return',
    'doc_acceptance',
    'doc_purchase_discrepancy',
    'doc_service_center_shipment',
    'doc_external_service_center_conclusion',
    'doc_universal',
    'doc_service_center_purchase_task',
    'doc_service_center_purchase',
    'doc_cross_docking_purchase_task',
    'doc_purchase_trust_acceptance_task',
    'doc_shipment_task',
    'doc_serviceable_outputting',
    'doc_cancellation',
    'doc_purchase_by_relocation',
    'doc_discrepancy_by_relocation',
    'doc_cross_docking_internal_purchase',
    'doc_return_task',
    'doc_purchase_trust_acceptance',
    'doc_cross_docking_shipment',
    'doc_inventory_act',
    'doc_purchase_return',
    'doc_client_resolution',
    'doc_batch_correction',
    'doc_internal_purchase_task_unpacking_container',
    'doc_spta_reservation',
    'doc_withdrawal_by_relocation',
    'doc_markdown',
    'doc_purchase_order',
    'doc_inventory_task',
    'doc_shipment_order',
    'doc_spta_unreservation',
    'doc_purchase',
    'doc_internal_shipment',
    'doc_relabelling_true_mark',
    'doc_shipment_discrepancy',
    'doc_return_discrepancy',
    'doc_movement',
    'doc_shipment_wave',
    'doc_cross_docking_movement_task',
    'doc_client_rejection',
    'doc_withdrawal_task_by_relocation',
    'doc_mismatch_registry',
    'doc_shipment_by_relocation',
    'doc_unloading_by_relocation',
    'doc_internal_purchase_discrepancy',
    'doc_acceptance_act_correction',
    'doc_shipment',
    'doc_inventory',
    'doc_acceptance_act',
    'doc_inventory_product_for_internal_usage',
    'doc_cross_docking_shipment_task',
    'doc_inventory_product_for_internal_usage_task',
];

$i = 0;

// Unused indexes

/** @var \Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto[] $indexesDtos */
$indexesDtos = $pgsqlIndexAnalyzer->indexesContainsInOtherIndexesByTables($tables);

if ($indexesDtos) {
    foreach ($indexesDtos as $indexesDto) {
        if (str_contains($indexesDto->getIndexName(), 'document')) {
            $i++;
            echo $i . '.' . '<b>dbName:</b> ' . $indexesDto->getTableName() . '; <b>IndexName:</b> ' . $indexesDto->getIndexName() . '; <b>columns:</b> ' . $indexesDto->getColumns() . '; <b>size:</b> ' . $indexesDto->getIndexSizePretty() . '<br>';
        }
    }
}
