<?php
/**
 * 
 * @package Zoom Learning Platform
 * @subpackage M. Sufyan Shaikh
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}

function createAllTables()
{
    global $wpdb;
    $ctp_registered = "cpt_registeration";
    if (get_option($ctp_registered) != null) {
        return;
    } else {
        try {
            $table_plugin = $wpdb->prefix . "zlp_custom_plugin";
            $charset_collate = $wpdb->get_charset_collate();

            $createTablePlugin = "CREATE TABLE $table_plugin  (
                id int(11) NOT NULL AUTO_INCREMENT,
                userAddress varchar(150) NOT NULL,
                userState varchar(150) NOT NULL,
                userCountry varchar(150) NOT NULL,
                userAddress varchar(150) NOT NULL,
                email varchar(150) NOT NULL,
                phone int(11) NOT NULL,
                nonce varchar(50) NOT NULL,
                paymentStatus varchar(50) NOT NULL DEFAULT 0,
                currentDate timestamp NOT NULL DEFAULT current_timestamp(),
                price float(10,2) NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once ABSPATH . "wp-admin/includes/upgrade.php";
            dbDelta($createTablePlugin);

        } catch (\Throwable $erro) {
            error_log($erro->getMessage());
            return $erro;
        }
        add_option($ctp_registered, true);
    }
}

function removeAllTables()
{
    $optionsToDelette = [
        "ctp_registered"
    ];

    global $wpdb;

    $table_plugin = $wpdb->prefix . "zlp_custom_plugin";

    try {
        $removal_pluginDatabase = "DROP TABLE IF EXISTS {$table_plugin}";
        $remResult2 = $wpdb->query($removal_pluginDatabase);

        foreach ($optionsToDelette as $options_value) {
            if (get_option($options_value)) {
                delete_option($options_value);
            }
        }

        return $remResult2;
    } catch (\Throwable $erro) {
        error_log($erro->getMessage());
        return $erro;
    }
}