<?php

interface ISession {
    public function set($name, $valor);
    public function get($name);
    public function delete_var($name);
    public function session_finish();
}