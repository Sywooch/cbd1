<?php

use yii\db\Migration;

class m170513_060606_install extends Migration
{
    public function up()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('api_auctions', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_auctions}}', [
                    'id' => 'VARCHAR(255) NULL',
                    'auctionID' => 'VARCHAR(35) NULL',
                    'tenderAttempts' => 'INT(11) NULL',
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    1 => 'PRIMARY KEY (`unique_id`)',
                    'dgfID' => 'VARCHAR(25) NULL',
                    'dgfDecisionID' => 'VARCHAR(25) NULL',
                    'dgfDecisionDate' => 'VARCHAR(35) NULL',
                    'baseAuction_id' => 'INT(255) NOT NULL',
                    'access_token' => 'VARCHAR(255) NULL',
                    'awardCriteria' => 'VARCHAR(25) NULL',
                    'dateModified' => 'VARCHAR(35) NOT NULL',
                    'title' => 'TEXT NULL',
                    'description' => 'TEXT NULL',
                    'tenderID' => 'VARCHAR(32) NULL',
                    'procuringEntity_id' => 'INT(11) NULL',
                    'procuringEntity_kind' => 'VARCHAR(25) NULL',
                    'procurementMethod' => 'VARCHAR(25) NULL',
                    'procurementMethodType' => 'VARCHAR(255) NULL',
                    'owner' => 'VARCHAR(25) NULL',
                    'value_amount' => 'DECIMAL(15,2) NULL',
                    'value_currency' => 'VARCHAR(3) NULL',
                    'value_valueAddedTaxIncluded' => 'TINYINT(1) NULL',
                    'guarantee_amount' => 'DECIMAL(15,2) NULL',
                    'guarantee_currency' => 'VARCHAR(3) NULL',
                    'date' => 'VARCHAR(35) NULL',
                    'minimalStep_amount' => 'DECIMAL(15,2) NULL',
                    'minimalStep_currency' => 'VARCHAR(3) NULL',
                    'minimalStep_valueAddedTaxIncluded' => 'TINYINT(1) NULL',
                    'enquiryPeriod_startDate' => 'VARCHAR(35) NULL',
                    'enquiryPeriod_endDate' => 'VARCHAR(35) NULL',
                    'tenderPeriod_startDate' => 'VARCHAR(35) NULL',
                    'tenderPeriod_endDate' => 'VARCHAR(35) NULL',
                    'auctionPeriod_startDate' => 'VARCHAR(35) NULL',
                    'auctionPeriod_endDate' => 'VARCHAR(35) NULL',
                    'auctionUrl' => 'TEXT NULL',
                    'awardPeriod_startDate' => 'VARCHAR(35) NULL',
                    'awardPeriod_endDate' => 'VARCHAR(35) NULL',
                    'status' => 'VARCHAR(255) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_award_organizations', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_award_organizations}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'award_id' => 'VARCHAR(255) NOT NULL',
                    'organization_id' => 'VARCHAR(25) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_awards', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_awards}}', [
                    'id' => 'VARCHAR(255) NULL',
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    1 => 'PRIMARY KEY (`unique_id`)',
                    'bid_id' => 'VARCHAR(255) NULL',
                    'auction_id' => 'VARCHAR(255) NOT NULL',
                    'title' => 'VARCHAR(255) NULL',
                    'description' => 'TEXT NULL',
                    'status' => 'VARCHAR(25) NOT NULL',
                    'date' => 'VARCHAR(35) NOT NULL',
                    'value_amount' => 'DECIMAL(15,2) NOT NULL',
                    'value_currency' => 'VARCHAR(3) NOT NULL DEFAULT \'UAH\'',
                    'value_valueAddedTaxIncluded' => 'TINYINT(1) NOT NULL',
                    'complaintPeriod_startDate' => 'VARCHAR(35) NOT NULL',
                    'complaintPeriod_endDate' => 'VARCHAR(35) NOT NULL',
                    'lotID' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_bids', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_bids}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'access_token' => 'VARCHAR(255) NULL',
                    'date' => 'VARCHAR(35) NULL',
                    'status' => 'VARCHAR(25) NOT NULL DEFAULT \'draft\'',
                    'accepted' => 'INT(1) NOT NULL',
                    'reason' => 'TEXT NULL',
                    'value_amount' => 'DECIMAL(15,2) NOT NULL',
                    'value_currency' => 'VARCHAR(3) NOT NULL DEFAULT \'UAH\'',
                    'value_valueAddedTaxIncluded' => 'TINYINT(1) NOT NULL',
                    'participationUrl' => 'TEXT NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'organization_id' => 'INT(11) NULL',
                    'lot_id' => 'INT(11) NOT NULL',
                    'auction_id' => 'INT(11) NULL',
                    'file_id' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_cancellations', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_cancellations}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'reason' => 'TEXT NULL',
                    'description' => 'TEXT NULL',
                    'status' => 'VARCHAR(25) NOT NULL',
                    'date' => 'VARCHAR(35) NOT NULL',
                    'cancellationOf' => 'VARCHAR(25) NOT NULL',
                    'relatedItem' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_complaints', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_complaints}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'author_id' => 'INT(11) NOT NULL',
                    'title' => 'VARCHAR(255) NOT NULL',
                    'description' => 'TEXT NOT NULL',
                    'date' => 'VARCHAR(35) NULL',
                    'dateSubmitted' => 'INT(11) NULL',
                    'dateAnswered' => 'INT(11) NULL',
                    'dateEscalated' => 'INT(11) NULL',
                    'dateDecision' => 'INT(11) NULL',
                    'dateCancelled' => 'INT(11) NULL',
                    'status' => 'VARCHAR(25) NOT NULL',
                    'type' => 'VARCHAR(25) NOT NULL',
                    'resolution' => 'TEXT NULL',
                    'resolutionType' => 'VARCHAR(25) NOT NULL',
                    'satisfied' => 'TINYINT(1) NOT NULL',
                    'decision' => 'TEXT NULL',
                    'cancellationReason' => 'TEXT NOT NULL',
                    'relatedLot' => 'VARCHAR(255) NOT NULL',
                    'tendererAction' => 'VARCHAR(255) NULL',
                    'tendererActionDate' => 'VARCHAR(35) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_contracts', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_contracts}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'awardID' => 'VARCHAR(255) NOT NULL',
                    'contractID' => 'VARCHAR(255) NULL',
                    'auction_id' => 'VARCHAR(255) NOT NULL',
                    'contractNumber' => 'VARCHAR(255) NULL',
                    'title' => 'TEXT NULL',
                    'description' => 'TEXT NULL',
                    'value_amount' => 'DECIMAL(10,0) NULL',
                    'value_currency' => 'VARCHAR(3) NULL',
                    'value_valueAddedTaxIncluded' => 'TINYINT(1) NULL',
                    'status' => 'VARCHAR(25) NOT NULL',
                    'period_startDate' => 'VARCHAR(35) NULL',
                    'period_endDate' => 'VARCHAR(35) NULL',
                    'dateSigned' => 'VARCHAR(35) NULL',
                    'date' => 'VARCHAR(35) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_documents', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_documents}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'author' => 'VARCHAR(255) NULL',
                    'file_id' => 'INT(11) NULL',
                    'hash' => 'VARCHAR(40) NULL',
                    'documentType' => 'VARCHAR(35) NOT NULL',
                    'title' => 'VARCHAR(255) NOT NULL',
                    'description' => 'TEXT NOT NULL',
                    'format' => 'VARCHAR(255) NULL',
                    'url' => 'TEXT NOT NULL',
                    'datePublished' => 'VARCHAR(35) NOT NULL',
                    'dateModified' => 'VARCHAR(255) NOT NULL',
                    'language' => 'VARCHAR(25) NOT NULL',
                    'documentOf' => 'VARCHAR(25) NOT NULL',
                    'relatedItem' => 'VARCHAR(255) NULL',
                    'lot_id' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_features', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_features}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'code' => 'VARCHAR(255) NOT NULL',
                    'featureOf' => 'VARCHAR(15) NOT NULL',
                    'relatedItem' => 'INT(11) NOT NULL',
                    'title' => 'VARCHAR(255) NOT NULL',
                    'description' => 'TEXT NULL',
                    'enum_value' => 'FLOAT(15,2) NULL',
                    'enum_title' => 'VARCHAR(255) NOT NULL',
                    'enum_description' => 'TEXT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_identifiers', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_identifiers}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'scheme' => 'VARCHAR(255) NULL',
                    'legalName' => 'VARCHAR(255) NULL',
                    'uri' => 'VARCHAR(255) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_items', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_items}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NOT NULL',
                    'auction_id' => 'VARCHAR(35) NOT NULL',
                    'api_auction_id' => 'VARCHAR(35) NULL',
                    'description' => 'TEXT NOT NULL',
                    'classification_id' => 'VARCHAR(25) NOT NULL',
                    'unit_code' => 'VARCHAR(25) NOT NULL',
                    'unit_name' => 'VARCHAR(255) NULL',
                    'quantity' => 'INT(15) NOT NULL',
                    'address_postalCode' => 'VARCHAR(25) NULL',
                    'address_countryName' => 'VARCHAR(255) NULL',
                    'address_streetAddress' => 'VARCHAR(255) NULL',
                    'address_region' => 'VARCHAR(255) NULL',
                    'address_locality' => 'TEXT NULL',
                    'location_latitude' => 'FLOAT NULL',
                    'location_longitude' => 'FLOAT NULL',
                    'relatedLot' => 'VARCHAR(255) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_items_classifications', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_items_classifications}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'item_id' => 'INT(11) NOT NULL',
                    'classification_id' => 'VARCHAR(25) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_lot_values', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_lot_values}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'value_amount' => 'DECIMAL(15,2) NOT NULL',
                    'value_currency' => 'TEXT NOT NULL',
                    'value_valueAddedTaxIncluded' => 'TINYINT(1) NOT NULL',
                    'relatedLot' => 'VARCHAR(255) NOT NULL',
                    'date' => 'VARCHAR(35) NOT NULL',
                    'participationUrl' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_organizations', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_organizations}}', [
                    'id' => 'VARCHAR(255) NULL',
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    1 => 'PRIMARY KEY (`unique_id`)',
                    'user_id' => 'INT(11) NULL',
                    'name' => 'VARCHAR(255) NOT NULL',
                    'kind' => 'VARCHAR(25) NULL',
                    'address_postalCode' => 'VARCHAR(25) NULL',
                    'address_countryName' => 'VARCHAR(255) NOT NULL',
                    'address_streetAddress' => 'VARCHAR(255) NULL',
                    'address_region' => 'VARCHAR(255) NULL',
                    'address_locality' => 'VARCHAR(255) NULL',
                    'contactPoint_name' => 'VARCHAR(255) NOT NULL',
                    'contactPoint_email' => 'VARCHAR(255) NULL',
                    'contactPoint_telephone' => 'VARCHAR(255) NULL',
                    'contactPoint_faxNumber' => 'VARCHAR(255) NULL',
                    'identifier_id' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_organizations_identifiers', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_organizations_identifiers}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'organization_id' => 'INT(11) NOT NULL',
                    'identifier_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_questions', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_questions}}', [
                    'unique_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`unique_id`)',
                    'id' => 'VARCHAR(255) NULL',
                    'author_id' => 'INT(11) NOT NULL',
                    'title' => 'VARCHAR(255) NOT NULL',
                    'description' => 'TEXT NOT NULL',
                    'date' => 'VARCHAR(35) NOT NULL',
                    'dateAnswered' => 'VARCHAR(35) NOT NULL',
                    'answer' => 'TEXT NOT NULL',
                    'questionOf' => 'VARCHAR(25) NOT NULL',
                    'relatedItem' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('api_users_organizations', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%api_users_organizations}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'user_id' => 'INT(11) NOT NULL',
                    'organization_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_assignment', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_assignment}}', [
                    'item_name' => 'VARCHAR(64) NOT NULL',
                    0 => 'KEY (`item_name`)',
                    'user_id' => 'INT(11) NOT NULL',
                    1 => 'PRIMARY KEY (`user_id`)',
                    'created_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'type' => 'INT(11) NOT NULL',
                    'description' => 'TEXT NULL',
                    'rule_name' => 'VARCHAR(64) NULL',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item_child', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item_child}}', [
                    'parent' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`parent`)',
                    'child' => 'VARCHAR(64) NOT NULL',
                    1 => 'KEY (`child`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_rule', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_rule}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('email_tasks', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%email_tasks}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'email' => 'VARCHAR(255) NOT NULL',
                    'message' => 'TEXT NOT NULL',
                    'process' => 'TINYINT(1) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('eventlog', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%eventlog}}', [
                    'id' => 'INT(255) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'ip' => 'VARCHAR(20) NOT NULL',
                    'user_id' => 'INT(10) NULL',
                    'auk_id' => 'INT(12) NULL',
                    'action' => 'VARCHAR(255) NOT NULL',
                    'date' => 'DATETIME NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('files', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%files}}', [
                    'id' => 'INT(255) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'type' => 'VARCHAR(35) NULL',
                    'path' => 'VARCHAR(255) NOT NULL',
                    'name' => 'VARCHAR(100) NOT NULL',
                    'user_id' => 'INT(255) NOT NULL',
                    'auction_id' => 'INT(255) NULL',
                    'lot_id' => 'INT(255) NULL',
                    'bid_id' => 'INT(11) NULL',
                    'cancellation_id' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('lots', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%lots}}', [
                    'id' => 'INT(255) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'procurementMethodType' => 'VARCHAR(25) NOT NULL',
                    'tenderAttempts' => 'INT(1) NULL',
                    'dgfDecisionID' => 'VARCHAR(25) NULL',
                    'dgfDecisionDate' => 'VARCHAR(255) NULL',
                    'num' => 'VARCHAR(25) NULL',
                    'user_id' => 'INT(10) NOT NULL',
                    'name' => 'VARCHAR(255) NOT NULL',
                    'ownerName' => 'VARCHAR(255) NULL',
                    'description' => 'TEXT(1600) NULL',
                    'start_price' => 'DECIMAL(20,2) NULL DEFAULT \'0.00\'',
                    'nds' => 'INT(1) NOT NULL DEFAULT \'1\'',
                    'step' => 'DECIMAL(12,2) NOT NULL',
                    'address' => 'VARCHAR(255) NULL',
                    'vdr' => 'VARCHAR(255) NULL',
                    'passport' => 'VARCHAR(255) NULL',
                    'delivery_time' => 'VARCHAR(800) NOT NULL',
                    'delivery_term' => 'VARCHAR(800) NOT NULL',
                    'requires' => 'TEXT NOT NULL',
                    'payment_term' => 'TEXT NULL',
                    'payment_order' => 'TEXT NULL',
                    'member_require' => 'TEXT NULL',
                    'term_procedure' => 'TEXT NULL',
                    'requisites_id' => 'INT(10) NULL',
                    'notes' => 'TEXT NOT NULL',
                    'dogovor_id' => 'INT(10) NULL',
                    'date' => 'VARCHAR(255) NOT NULL',
                    'bidding_date' => 'VARCHAR(255) NULL',
                    'bidding_date_end' => 'VARCHAR(255) NOT NULL',
                    'auction_date' => 'VARCHAR(255) NOT NULL',
                    'auction_time' => 'VARCHAR(25) NOT NULL',
                    'auction_date_end' => 'VARCHAR(255) NOT NULL',
                    'status' => 'INT(10) NOT NULL DEFAULT \'1\'',
                    'lot_lock' => 'INT(1) NOT NULL',
                    'step_down' => 'INT(1) NOT NULL DEFAULT \'3\'',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('messages', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%messages}}', [
                    'id' => 'INT(255) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'user_id' => 'INT(11) NOT NULL',
                    'notes' => 'TEXT NOT NULL',
                    'date' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                    'status' => 'INT(20) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('profile', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%profile}}', [
                    'user_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`user_id`)',
                    'licenseNumber' => 'VARCHAR(25) NULL',
                    'role' => 'TINYINT(1) NOT NULL',
                    'files' => 'VARCHAR(255) NOT NULL',
                    'status' => 'INT(1) NOT NULL DEFAULT \'1\'',
                    'fio' => 'VARCHAR(255) NOT NULL',
                    'at_org' => 'VARCHAR(255) NOT NULL',
                    'org_type' => 'VARCHAR(25) NOT NULL',
                    'phone' => 'VARCHAR(255) NULL',
                    'fax' => 'VARCHAR(255) NULL',
                    'firma_full' => 'VARCHAR(255) NULL',
                    'inn' => 'INT(30) NULL',
                    'zkpo' => 'INT(30) NULL',
                    'u_address' => 'VARCHAR(255) NULL',
                    'f_address' => 'VARCHAR(255) NULL',
                    'member' => 'VARCHAR(255) NULL',
                    'member_phone' => 'VARCHAR(255) NULL',
                    'member_email' => 'VARCHAR(255) NULL',
                    'site' => 'VARCHAR(255) NULL',
                    'edrpou_bank' => 'VARCHAR(12) NULL',
                    'mfo' => 'VARCHAR(15) NULL',
                    'bank_name' => 'VARCHAR(255) NULL',
                    'postal_code' => 'VARCHAR(25)',
                    'region' => 'VARCHAR(225)',
                    'city' => 'VARCHAR(25)',
                    'passport_number' => 'VARCHAR(25) NULL'
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('social_account', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%social_account}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'user_id' => 'INT(11) NULL',
                    'provider' => 'VARCHAR(255) NOT NULL',
                    'client_id' => 'VARCHAR(255) NOT NULL',
                    'data' => 'TEXT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('token', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%token}}', [
                    'user_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`user_id`)',
                    'code' => 'VARCHAR(32) NOT NULL',
                    1 => 'KEY (`code`)',
                    'created_at' => 'INT(11) NOT NULL',
                    'type' => 'SMALLINT(6) NOT NULL',
                    3 => 'KEY (`type`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'username' => 'VARCHAR(25) NOT NULL',
                    'role' => 'INT(11) NOT NULL',
                    'email' => 'VARCHAR(255) NOT NULL',
                    'fio' => 'VARCHAR(50) NOT NULL',
                    'at_org' => 'VARCHAR(255) NULL',
                    'org_type' => 'VARCHAR(25) NOT NULL',
                    'member_phone' => 'VARCHAR(15) NULL',
                    'status' => 'INT(1) NOT NULL DEFAULT \'1\'',
                    'password_hash' => 'VARCHAR(60) NOT NULL',
                    'auth_key' => 'VARCHAR(32) NOT NULL',
                    'confirmed_at' => 'INT(11) NULL',
                    'unconfirmed_email' => 'VARCHAR(255) NULL',
                    'blocked_at' => 'INT(11) NULL',
                    'registration_ip' => 'VARCHAR(45) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'last_login_at' => 'INT(11) NULL',
                    'flags' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_UNIQUE_unique_id_1936_00','api_auctions','unique_id',1);
        $this->createIndex('idx_baseAuction_id_1936_01','api_auctions','baseAuction_id',0);
        $this->createIndex('idx_id_1936_02','api_auctions','id',0);
        $this->createIndex('idx_unique_id_1936_03','api_auctions','unique_id',0);
        $this->createIndex('idx_status_1936_04','api_auctions','status',0);
        $this->createIndex('idx_created_at_1936_05','api_auctions','created_at',0);
        $this->createIndex('idx_baseAuction_id_1936_06','api_auctions','baseAuction_id',0);
        $this->createIndex('idx_auctionID_1936_07','api_auctions','auctionID',0);
        $this->createIndex('idx_unique_id_1936_08','api_auctions','unique_id',0);
        $this->createIndex('idx_id_1937_09','api_auctions','id',0);
        $this->createIndex('idx_created_at_2009_10','api_bids','created_at',0);
        $this->createIndex('idx_user_id_201_11','api_bids','user_id',0);
        $this->createIndex('idx_lot_id_201_12','api_bids','lot_id',0);
        $this->createIndex('idx_auction_id_201_13','api_bids','auction_id',0);
        $this->createIndex('idx_unique_id_201_14','api_bids','unique_id',0);
        $this->createIndex('idx_id_201_15','api_bids','id',0);
        $this->createIndex('idx_organization_id_201_16','api_bids','organization_id',0);
        $this->createIndex('idx_id_2028_17','api_cancellations','id',0);
        $this->createIndex('idx_author_id_209_19','api_complaints','author_id',0);
        $this->createIndex('idx_relatedLot_209_20','api_complaints','relatedLot',0);
        $this->createIndex('idx_id_209_21','api_complaints','id',0);
        $this->createIndex('idx_awardID_2114_22','api_contracts','awardID',0);
        $this->createIndex('idx_contractID_2114_23','api_contracts','contractID',0);
        $this->createIndex('idx_contractNumber_2114_24','api_contracts','contractNumber',0);
        $this->createIndex('idx_id_2114_25','api_contracts','id',0);
        $this->createIndex('idx_lot_id_214_26','api_documents','lot_id',0);
        $this->createIndex('idx_auction_id_2196_27','api_items','auction_id',0);
        $this->createIndex('idx_relatedLot_2222_28','api_lot_values','relatedLot',0);
        $this->createIndex('idx_user_id_2313_29','auth_assignment','user_id',0);
        $this->createIndex('idx_rule_name_2327_30','auth_item','rule_name',0);
        $this->createIndex('idx_type_2327_31','auth_item','type',0);
        $this->createIndex('idx_child_2342_32','auth_item_child','child',0);
        $this->createIndex('idx_type_2393_33','files','type',0);
        $this->createIndex('idx_bid_id_2394_34','files','bid_id',0);
        $this->createIndex('idx_cancellation_id_2394_35','files','cancellation_id',0);
        $this->createIndex('idx_procurementMethodType_2412_36','lots','procurementMethodType',0);
        $this->createIndex('idx_status_2412_37','lots','status',0);
        $this->createIndex('idx_id_2412_38','lots','id',0);
        $this->createIndex('idx_user_id_2412_39','lots','user_id',0);
        $this->createIndex('idx_user_id_2427_40','messages','user_id',0);
        $this->createIndex('idx_status_2428_41','messages','status',0);
        $this->createIndex('idx_UNIQUE_provider_2468_42','social_account','provider',1);
        $this->createIndex('idx_user_id_2468_43','social_account','user_id',0);
        $this->createIndex('idx_UNIQUE_user_id_2485_44','token','user_id',1);
        $this->createIndex('idx_UNIQUE_username_2508_45','user','username',1);
        $this->createIndex('idx_UNIQUE_email_2508_46','user','email',1);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_auth_item_2311_00','{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_2311_01','{{%auth_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_rule_2325_02','{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_234_03','{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_234_04','{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_2446_05','{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_2465_06','{{%social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_2482_07','{{%token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->execute('SET foreign_key_checks = 1;');

        
        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%api_identifiers}}',['id'=>'2147483647','scheme'=>'UA-EDR','legalName'=>'React Logic Agency','uri'=>'']);
        $this->execute('SET foreign_key_checks = 1;');

        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%api_organizations}}',['id'=>'','unique_id'=>'','user_id'=>'6','name'=>'React Logic Agency','kind'=>'','address_postalCode'=>'39600','address_countryName'=>'Україна','address_streetAddress'=>'e;fdlcekijm[','address_region'=>'Вінницька область','address_locality'=>'Киев','contactPoint_name'=>'Витренко Вячеслав','contactPoint_email'=>'admin@react-logic.com','contactPoint_telephone'=>'+380663564463','contactPoint_faxNumber'=>'','identifier_id'=>'2147483647','created_at'=>'1485376906','updated_at'=>'1493791705']);
        $this->insert('{{%api_organizations}}',['id'=>'','unique_id'=>'','user_id'=>'7','name'=>'React Logic Agency','kind'=>'','address_postalCode'=>'39600','address_countryName'=>'Україна','address_streetAddress'=>'e;fdlcekijm[','address_region'=>'Вінницька область','address_locality'=>'Киев','contactPoint_name'=>'Витренко Вячеслав','contactPoint_email'=>'admin@react-logic.com','contactPoint_telephone'=>'+380663564463','contactPoint_faxNumber'=>'','identifier_id'=>'2147483647','created_at'=>'1485376986','updated_at'=>'']);
        $this->insert('{{%api_organizations}}',['id'=>'','unique_id'=>'','user_id'=>'8','name'=>'React Logic Agency','kind'=>'','address_postalCode'=>'39600','address_countryName'=>'Україна','address_streetAddress'=>'e;fdlcekijm[','address_region'=>'Вінницька область','address_locality'=>'Киев','contactPoint_name'=>'Витренко Вячеслав','contactPoint_email'=>'admin@react-logic.com','contactPoint_telephone'=>'+380663564463','contactPoint_faxNumber'=>'','identifier_id'=>'2147483647','created_at'=>'1485377027','updated_at'=>'']);
        $this->insert('{{%api_organizations}}',['id'=>'','unique_id'=>'','user_id'=>'9','name'=>'React Logic Agency','kind'=>'','address_postalCode'=>'39600','address_countryName'=>'Україна','address_streetAddress'=>'e;fdlcekijm[','address_region'=>'Закарпатська область','address_locality'=>'Киев','contactPoint_name'=>'Витренко Вячеслав','contactPoint_email'=>'admin@react-logic.com','contactPoint_telephone'=>'+380663564463','contactPoint_faxNumber'=>'','identifier_id'=>'2147483647','created_at'=>'1485377069','updated_at'=>'']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'admin','user_id'=>'6','created_at'=>'1485376893']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'member','user_id'=>'8','created_at'=>'1485377017']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'member','user_id'=>'9','created_at'=>'1485377058']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'member','user_id'=>'10','created_at'=>'1493983699']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'org','user_id'=>'7','created_at'=>'1485376974']);
        $this->insert('{{%auth_item}}',['name'=>'admin','type'=>'1','description'=>'','rule_name'=>null,'data'=>'','created_at'=>'','updated_at'=>'']);
        $this->insert('{{%auth_item}}',['name'=>'member','type'=>'1','description'=>'','rule_name'=>null,'data'=>'','created_at'=>'','updated_at'=>'']);
        $this->insert('{{%auth_item}}',['name'=>'org','type'=>'1','description'=>'','rule_name'=>null,'data'=>'','created_at'=>'','updated_at'=>'']);
        $this->insert('{{%auth_item}}',['name'=>'watcher','type'=>'1','description'=>'','rule_name'=>null,'data'=>'','created_at'=>'','updated_at'=>'']);
        $this->insert('{{%profile}}',['user_id'=>'6','licenseNumber'=>'234837','role'=>'1','files'=>'','status'=>'1','fio'=>'','at_org'=>'','org_type'=>'0','phone'=>'+380663564463','fax'=>'','firma_full'=>'React Logic Agency','inn'=>'2147483647','zkpo'=>'2147483647','u_address'=>'e;fdlcekijm[','f_address'=>'lijn;dwilcn;l','member'=>'Витренко Вячеслав','member_phone'=>'','member_email'=>'','site'=>'https://mxuser.com','edrpou_bank'=>'sergse','mfo'=>'','bank_name'=>'']);
        $this->insert('{{%profile}}',['user_id'=>'7','licenseNumber'=>'234837','role'=>'1','files'=>'','status'=>'1','fio'=>'','at_org'=>'','org_type'=>'0','phone'=>'+380663564463','fax'=>'','firma_full'=>'React Logic Agency','inn'=>'2147483647','zkpo'=>'2147483647','u_address'=>'e;fdlcekijm[','f_address'=>'lijn;dwilcn;l','member'=>'Витренко Вячеслав','member_phone'=>'','member_email'=>'','site'=>'https://mxuser.com','edrpou_bank'=>'segesERFH','mfo'=>'','bank_name'=>'']);
        $this->insert('{{%profile}}',['user_id'=>'8','licenseNumber'=>'234837','role'=>'1','files'=>'','status'=>'1','fio'=>'','at_org'=>'','org_type'=>'0','phone'=>'+380663564463','fax'=>'','firma_full'=>'React Logic Agency','inn'=>'2147483647','zkpo'=>'2147483647','u_address'=>'e;fdlcekijm[','f_address'=>'lijn;dwilcn;l','member'=>'Витренко Вячеслав','member_phone'=>'','member_email'=>'','site'=>'https://mxuser.com','edrpou_bank'=>'sergserg','mfo'=>'','bank_name'=>'']);
        $this->insert('{{%profile}}',['user_id'=>'9','licenseNumber'=>'234837','role'=>'1','files'=>'','status'=>'1','fio'=>'','at_org'=>'','org_type'=>'0','phone'=>'+380663564463','fax'=>'','firma_full'=>'React Logic Agency','inn'=>'2147483647','zkpo'=>'2147483647','u_address'=>'e;fdlcekijm[','f_address'=>'lijn;dwilcn;l','member'=>'Витренко Вячеслав','member_phone'=>'','member_email'=>'','site'=>'https://mxuser.com','edrpou_bank'=>'sergse34','mfo'=>'','bank_name'=>'']);
        $this->insert('{{%profile}}',['user_id'=>'10','licenseNumber'=>'','role'=>'1','files'=>'','status'=>'1','fio'=>'','at_org'=>'','org_type'=>'0','phone'=>'+380678301688','fax'=>'','firma_full'=>'zam','inn'=>'56546546','zkpo'=>'2147483647','u_address'=>'м. Полтава, Шевченка','f_address'=>'м. Полтава, Шевченка','member'=>'direktor','member_phone'=>'','member_email'=>'','site'=>'','edrpou_bank'=>'sergser434','mfo'=>'','bank_name'=>'']);
        $this->insert('{{%token}}',['user_id'=>'6','code'=>'Nc9DDAitc5S--Eu1mGm13xK5H5_jE34G','created_at'=>'1485376893','type'=>'0']);
        $this->insert('{{%token}}',['user_id'=>'7','code'=>'mNR1ttesaht6K9cLQ4pH54Ph34trxLqC','created_at'=>'1485376974','type'=>'0']);
        $this->insert('{{%token}}',['user_id'=>'8','code'=>'JtIT-1Ch0YrG7bq_XfNyYw9AIKhs0tK6','created_at'=>'1485377017','type'=>'0']);
        $this->insert('{{%token}}',['user_id'=>'9','code'=>'iDKfv4gSXTyzfAOgrDCfEO0iZml_PRs3','created_at'=>'1485377058','type'=>'0']);
        $this->insert('{{%token}}',['user_id'=>'10','code'=>'hczkdN4-mjxLUIv1lbhCEtjG_1_JlK_e','created_at'=>'1493983699','type'=>'0']);
        $this->insert('{{%user}}',['id'=>'6','username'=>'neiron','role'=>'1','email'=>'admin@react-logic.com','fio'=>'Витренко Вячеслав','at_org'=>'React Logic Agency','org_type'=>'0','member_phone'=>'+380663564463','status'=>'1','password_hash'=>'$2y$10$Dw5Jnci.5sGk9mVx1Q2ydOrwxbNS8N.PBFsJjGXam5dcrKJ7kT7Zi','auth_key'=>'QURasVn9Zn570CkNzx_GfBE6LxuE3mBw','confirmed_at'=>'1','unconfirmed_email'=>'','blocked_at'=>'','registration_ip'=>'127.0.0.1','created_at'=>'1485376893','updated_at'=>'1485376893','last_login_at'=>'1485376928','flags'=>'0']);
        $this->insert('{{%user}}',['id'=>'7','username'=>'organizator','role'=>'1','email'=>'organizator@mail.ru','fio'=>'Витренко Вячеслав','at_org'=>'React Logic Agency','org_type'=>'0','member_phone'=>'+380663564463','status'=>'1','password_hash'=>'$2y$10$Hh2U9yFxfV1iIxAIte9DP.V0qaKxs5ZznFmonD20/SlN32aW9/Fda','auth_key'=>'gQ19sXaJEtzC-s2YF7dCq3FGteJpcZmt','confirmed_at'=>'1','unconfirmed_email'=>'','blocked_at'=>'','registration_ip'=>'127.0.0.1','created_at'=>'1485376974','updated_at'=>'1493906160','last_login_at'=>'1494319109','flags'=>'0']);
        $this->insert('{{%user}}',['id'=>'8','username'=>'member','role'=>'2','email'=>'slavavitrenko@gmail.com','fio'=>'Витренко Вячеслав','at_org'=>'React Logic Agency','org_type'=>'0','member_phone'=>'+380663564463','status'=>'1','password_hash'=>'$2y$10$dUlkk.Wcx/XosO61nwgl1uW8izlCa60h7ubOTL9OXkewQj24LyJ6.','auth_key'=>'UsQ_LmiUAimser41Do-OKjk6ucpMFtIS','confirmed_at'=>'1','unconfirmed_email'=>'','blocked_at'=>'','registration_ip'=>'127.0.0.1','created_at'=>'1485377017','updated_at'=>'1485377017','last_login_at'=>'1494318249','flags'=>'0']);
        $this->insert('{{%user}}',['id'=>'9','username'=>'member1','role'=>'2','email'=>'slava@mxuser.com','fio'=>'Витренко Вячеслав','at_org'=>'React Logic Agency','org_type'=>'0','member_phone'=>'+380663564463','status'=>'1','password_hash'=>'$2y$10$HewFE7IGh4pgxY7kE.o23epr5PQGqK1orasW7FoNYS81YiytU6zMa','auth_key'=>'Ra0ghGqxl8yh_sqka5GPOFS3UxFncaGk','confirmed_at'=>'1','unconfirmed_email'=>'','blocked_at'=>'','registration_ip'=>'127.0.0.1','created_at'=>'1485377058','updated_at'=>'1485377058','last_login_at'=>'1494318244','flags'=>'0']);
        $this->insert('{{%user}}',['id'=>'10','username'=>'zamovnuk','role'=>'2','email'=>'neros354@gmail.com','fio'=>'Ліквідатор Тестович','at_org'=>'Замовляю что попало','org_type'=>'0','member_phone'=>'+38(050)-250-55','status'=>'1','password_hash'=>'$2y$10$RthgKhkf/X2NYvctNtMPs.Buz9RytZxwFhT0OevrLKMW5NUdPGeHC','auth_key'=>'gbukOgSqe8tXqZ4Fps2K6G3Zd566_n5_','confirmed_at'=>'','unconfirmed_email'=>'','blocked_at'=>'','registration_ip'=>'93.78.238.18','created_at'=>'1493983699','updated_at'=>'1493983699','last_login_at'=>'','flags'=>'0']);
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `api_auctions`');
        $this->execute('DROP TABLE IF EXISTS `api_award_organizations`');
        $this->execute('DROP TABLE IF EXISTS `api_awards`');
        $this->execute('DROP TABLE IF EXISTS `api_bids`');
        $this->execute('DROP TABLE IF EXISTS `api_cancellations`');
        $this->execute('DROP TABLE IF EXISTS `api_complaints`');
        $this->execute('DROP TABLE IF EXISTS `api_contracts`');
        $this->execute('DROP TABLE IF EXISTS `api_documents`');
        $this->execute('DROP TABLE IF EXISTS `api_features`');
        $this->execute('DROP TABLE IF EXISTS `api_identifiers`');
        $this->execute('DROP TABLE IF EXISTS `api_items`');
        $this->execute('DROP TABLE IF EXISTS `api_items_classifications`');
        $this->execute('DROP TABLE IF EXISTS `api_lot_values`');
        $this->execute('DROP TABLE IF EXISTS `api_organizations`');
        $this->execute('DROP TABLE IF EXISTS `api_organizations_identifiers`');
        $this->execute('DROP TABLE IF EXISTS `api_questions`');
        $this->execute('DROP TABLE IF EXISTS `api_users_organizations`');
        $this->execute('DROP TABLE IF EXISTS `auth_assignment`');
        $this->execute('DROP TABLE IF EXISTS `auth_item`');
        $this->execute('DROP TABLE IF EXISTS `auth_item_child`');
        $this->execute('DROP TABLE IF EXISTS `auth_rule`');
        $this->execute('DROP TABLE IF EXISTS `email_tasks`');
        $this->execute('DROP TABLE IF EXISTS `eventlog`');
        $this->execute('DROP TABLE IF EXISTS `files`');
        $this->execute('DROP TABLE IF EXISTS `lots`');
        $this->execute('DROP TABLE IF EXISTS `messages`');
        $this->execute('DROP TABLE IF EXISTS `profile`');
        $this->execute('DROP TABLE IF EXISTS `social_account`');
        $this->execute('DROP TABLE IF EXISTS `token`');
        $this->execute('DROP TABLE IF EXISTS `user`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}
