<?php

interface IDataBaseManager {
    public function realizeQuery($query);
    public function insertQuery($query);
    public function realizeQueryPrepared($query, $params): ?array;
}