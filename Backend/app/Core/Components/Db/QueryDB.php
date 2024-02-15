<?php

declare(strict_types=1);

namespace App\Core\Components\Db; // Assuming your Laravel app namespace is 'App'

use Illuminate\Database\ConnectionInterface; // Import Laravel's database connection interface
use Illuminate\Support\Facades\DB; // Import the DB facade

class QueryDB {

    private $link;
    private $result = array();
    private $sql = '';
    private $stmt = '';
    private $sp = '';
    private $params = [];
    private $param_values = [];
    private $is_error = false;
    private $errors;

    /*
     * Create a new instance of link to Database
     */

    public function __construct(ConnectionInterface $connection) {
        $this->link = $connection;
    }

    /*
     * Execute an SQL Query and return ONE row of result
     * 
     * @param   string $sql 
     * @return  array
     */

    public function select(string $sql, array $values) {
        $this->sql = $sql;
        $this->param_values = $values;
        $this->stmt = $this->link->select($this->sql, $this->param_values);
        
        if ($this->stmt) {
            $this->result = (array) $this->stmt;
            $this->result = $this->encodeResult($this->result);
        } else {
            $this->errors = $this->link->select("SELECT @@ERROR as error");
            $this->is_error = true;
        }
        return $this->result;
    }

    /*
     * Execute an SQL Query and return MULTIPLE row of result
     * 
     * @param   string $sql 
     * @return  array
     */

    public function selectAll(string $sql, array $values) {
        $this->sql = $sql;
        $this->param_values = $values;
        $this->stmt = $this->link->select($this->sql, $this->param_values);
        
        if ($this->stmt) {
            foreach ($this->stmt as $row) {
                array_push($this->result, $this->encodeResult((array) $row));
            }
        } else {
            $this->errors = $this->link->select("SELECT @@ERROR as error");
            $this->is_error = true;
        }
        return $this->result;
    }

    /*
     * Generate and Execute an SQL Query for SQL Server Stored Procedure
     * 
     * @param   $sql
     * 
     * @return array containing single row [result int, uuid varchar(36), message varchar(500)]
     */

    public function exec_sp(string $sp, array $params = null): array {

        $this->params = $params;
        $this->sp = $sp;

        $this->sql = 'BEGIN TRY ';
        $this->sql .= 'DECLARE @_result int, @_uuid varchar(36), @_message varchar(500); ';
        $this->sql .= 'exec @_result =  ' . $sp . $this->get_params($params) . ' @uuid_ = @_uuid output, @message_ = @_message output;';
        $this->sql .= 'SELECT @_result as result, @_uuid as uuid, @_message as message;';
        $this->sql .= 'END TRY      ';
        $this->sql .= 'BEGIN CATCH  ';
        $this->sql .= 'SELECT   0 AS result, \'\' AS uuid ,ERROR_MESSAGE() AS message;';
        $this->sql .= 'END CATCH    ';
        $this->stmt = $this->link->select($this->sql, $this->param_values);

        if ($this->stmt === false) {
            $this->errors = $this->link->select("SELECT @@ERROR as error");
            $this->is_error = true;
            return [
                'result' => 0,
                'uuid' => '',
                'message' => 'Erreur lors de l\'exécution de la procédure stockée.'
            ];
        }

        $this->result = (array) $this->stmt[0];

        if (is_string($this->result['message'])) {
            // Séparation du nom de la procedure 
            $message = explode('>>>', $this->result['message']);

            if (isset($message[1])) {
                $this->result['code'] = $message[0];
                $this->result['message'] = utf8_encode($message[1]);
            } else {
                $this->result['message'] = utf8_encode($message[0]);
            }
        } else {
            $this->result['message'] = '';
        }
        return $this->result;
    }

    /**
     * 
     */
    public function exec_sp_bulk(string $sp, array $params = null): array 
    {
        $this->params = $params;
        $this->sp = $sp;

        $this->sql = ' begin try ';
        $this->sql .= ' execute ' . $sp . rtrim($this->get_params($params), ', ');
        $this->sql .= ' end try      ';
        $this->sql .= ' begin catch  ';
        $this->sql .= ' select error_message() as message;';
        $this->sql .= ' end catch    ';
        $this->stmt = $this->link->select($this->sql, $this->param_values);
        
        if ($this->stmt === false) {
            $this->errors = $this->link->select("SELECT @@ERROR as error");
            $this->is_error = true;
            return [
                'message' => 'Erreur lors de l\'exécution de la procédure stockée.'
            ];
        }
        else
        {
            foreach ($this->stmt as $row) {
                array_push($this->result, $this->encodeResult((array) $row));
            }
        }
        return $this->result;
    }

    /*
     * Generate and Execute an SQL Query for SQL Server Stored Procedure
     * 
     * @param   $sql
     * 
     * @return Single Row of Result
     */

    public function execBulkSP() {
        
    }

    /*
     * Format params to be used in the prepared SQL Query for SQL Server Stored Procedure
     * 
     * @param  $params array
     * 
     * @return string
     */

    private function get_params(array $params): string {
        $formated_params = '';
        $this->param_values = [];

        foreach ($params as $key => $value) {
            $formated_params .= ' @_' . $key . '_ =  ? ,';
            array_push($this->param_values, $value); // we also push values to be user in prepared statement
        }
        return $formated_params;
    }

    /*
     * Encode a row result with utf8_encode
     * 
     * @return array
     */

    public function encodeResult(array $row): array {
        if (is_array($row)) {
            foreach ($row as $index => $champ) {
                $row[$index] = mb_convert_encoding($champ, 'UTF-8', 'ISO-8859-1');
            }
        }
        return $row;
    }

    /*
     * Return the sql statement used for this instance of query
     * 
     * @return string
     */

    public function getSql(): string {
        return $this->sql;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function is_error(): bool {
        return $this->is_error;
    }

    /*
     * return the sql statement for exec_sp
     */

    public function exec_sp_stmt(): array {

        $sql_stmt = 'BEGIN TRY ';
        $sql_stmt .= 'DECLARE @_result int, @_uuid varchar(36), @_message varchar(500); ';
        $sql_stmt .= 'exec @_result =  ' . $this->sp . $this->get_params_stmt($this->params) . ' @uuid_ = @_uuid output, @message_ = @_message output;';
        $sql_stmt .= 'SELECT @_result as result, @_uuid as uuid, @_message as message;';
        $sql_stmt .= 'END TRY      ';
        $sql_stmt .= 'BEGIN CATCH  ';
        $sql_stmt .= 'SELECT   0 AS result, \'\' AS uuid ,ERROR_MESSAGE() AS message;';
        $sql_stmt .= 'END CATCH    ';
        return [
            'prepared_sql' => $this->sql,
            'param_values' => $this->param_values,
            'sql' => $sql_stmt,
            'params' => $this->params
        ];
    }

    private function get_params_stmt(array $params): string {
        $formated_params = '';

        foreach ($params as $key => $value) {
            $formated_params .= ' @_' . $key . '_ =  \'' . $value . '\' ,';
        }
        return $formated_params;
    }
}
