echo user;
read user;
echo password;
read -s password;
echo databaseName;
read databaseName;
echo 'outputFile (dont use ~ for home)';
read outputFile;

mysqldump -u${user} -p"${password}" --single-transaction --no-data  --single-transaction --quick "${databaseName}" | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' > "${outputFile}";

mysqldump -u${user} -p"${password}" "${databaseName}" --no-create-info --single-transaction --quick --ignore-table=${databaseName}.catalogsearch_fulltext --ignore-table=${databaseName}.catalogsearch_query  --ignore-table=${databaseName}.catalogsearch_result --ignore-table=${databaseName}.core_session --ignore-table=${databaseName}.customer_address_entity --ignore-table=${databaseName}.customer_address_entity_datetime --ignore-table=${databaseName}.customer_address_entity_decimal --ignore-table=${databaseName}.customer_address_entity_int --ignore-table=${databaseName}.customer_address_entity_text --ignore-table=${databaseName}.customer_address_entity_varchar --ignore-table=${databaseName}.customer_entity --ignore-table=${databaseName}.customer_entity_datetime --ignore-table=${databaseName}.customer_entity_decimal --ignore-table=${databaseName}.customer_entity_int --ignore-table=${databaseName}.customer_entity_text --ignore-table=${databaseName}.customer_entity_varchar --ignore-table=${databaseName}.dataflow_batch --ignore-table=${databaseName}.dataflow_batch_export --ignore-table=${databaseName}.dataflow_batch_import --ignore-table=${databaseName}.dataflow_import_data --ignore-table=${databaseName}.dataflow_session --ignore-table=${databaseName}.report_event --ignore-table=${databaseName}.report_viewed_product_aggregated_daily --ignore-table=${databaseName}.report_viewed_product_aggregated_monthly --ignore-table=${databaseName}.report_viewed_product_aggregated_yearly --ignore-table=${databaseName}.report_viewed_product_index --ignore-table=${databaseName}.sales_bestsellers_aggregated_daily --ignore-table=${databaseName}.sales_bestsellers_aggregated_monthly --ignore-table=${databaseName}.sales_bestsellers_aggregated_yearly --ignore-table=${databaseName}.sales_flat_creditmemo --ignore-table=${databaseName}.sales_flat_creditmemo_comment --ignore-table=${databaseName}.sales_flat_creditmemo_grid --ignore-table=${databaseName}.sales_flat_creditmemo_item --ignore-table=${databaseName}.sales_flat_invoice --ignore-table=${databaseName}.sales_flat_invoice_comment --ignore-table=${databaseName}.sales_flat_invoice_grid --ignore-table=${databaseName}.sales_flat_invoice_item --ignore-table=${databaseName}.sales_flat_order --ignore-table=${databaseName}.sales_flat_order_address --ignore-table=${databaseName}.sales_flat_order_grid --ignore-table=${databaseName}.sales_flat_order_item --ignore-table=${databaseName}.sales_flat_order_payment --ignore-table=${databaseName}.sales_flat_order_status_history --ignore-table=${databaseName}.sales_flat_quote --ignore-table=${databaseName}.sales_flat_quote_address --ignore-table=${databaseName}.sales_flat_quote_address_item --ignore-table=${databaseName}.sales_flat_quote_item --ignore-table=${databaseName}.sales_flat_quote_item_option --ignore-table=${databaseName}.sales_flat_quote_payment --ignore-table=${databaseName}.sales_flat_quote_shipping_rate --ignore-table=${databaseName}.sales_flat_shipment --ignore-table=${databaseName}.sales_flat_shipment_comment --ignore-table=${databaseName}.sales_flat_shipment_grid --ignore-table=${databaseName}.sales_flat_shipment_item --ignore-table=${databaseName}.sales_flat_shipment_track --ignore-table=${databaseName}.sales_order_aggregated_created --ignore-table=${databaseName}.sales_order_aggregated_updated --ignore-table=${databaseName}.sales_order_tax --ignore-table=${databaseName}.sales_order_tax_item --ignore-table=${databaseName}.sales_payment_transaction --ignore-table=${databaseName}.sales_recurring_profile --ignore-table=${databaseName}.sales_recurring_profile_order --ignore-table=${databaseName}.sales_refunded_aggregated --ignore-table=${databaseName}.sales_refunded_aggregated_order  --ignore-table=${databaseName}.taxonomies_customers --ignore-table=${databaseName}.taxonomies_customers_addresses --ignore-table=${databaseName}.log_customer --ignore-table=${databaseName}.log_quote --ignore-table=${databaseName}.log_summary --ignore-table=${databaseName}.log_summary_type --ignore-table=${databaseName}.log_url --ignore-table=${databaseName}.log_url_info --ignore-table=${databaseName}.log_visitor --ignore-table=${databaseName}.log_visitor_info --ignore-table=${databaseName}.log_visitor_online | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' >> "${outputFile}";