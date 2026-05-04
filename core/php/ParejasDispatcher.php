<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 12:31 PM
 */

include_once("ParejasManager.php");



$parejasManager = ParejasManager::getInstance();

echo $parejasManager->getAllParejas();

