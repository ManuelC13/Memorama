<?php

class AuthService {
    private IDataBaseManager $database;
    private ISession $session;

    public function __construct(IDataBaseManager $database, ISession $session) {
        $this->database = $database;
        $this->session = $session;
    }

    public function login(string $username, string $password): ?array {
        // Prepared statement para evitar SQL Injection
        $query = "SELECT * FROM usuario WHERE nombre = ? AND clave = ?";
        $result = $this->database->realizeQueryPrepared($query, [$username, $password]);

        return $this->verifyLogin($result ?? []);
    }

    public function verifyLogin(array $result): ?array {
        if (count($result) > 0) {
            $this->session->set("user", $result[0]['id']);
            return [['type' => $result[0]['tipo']]];
        }
        return null;
    }
}|