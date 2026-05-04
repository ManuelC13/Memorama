<?php

/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 05/05/2016
 * Time: 06:51 PM
 */
class Couple
{
    private $idMatter;
    private $concept;
    private $definition;
    public function __construct($concept,$definition,$idMatter) {
        $this->idMatter = $idMatter;
        $this->concept = $concept;
        $this->definition = $definition;
    }

    /**
     * @return mixed
     */
    public function getIdMatter()
    {
        return $this->idMatter;
    }

    /**
     * @param mixed $idMatter
     */
    public function setIdMatter($idMatter)
    {
        $this->idMatter = $idMatter;
    }

    /**
     * @return mixed
     */
    public function getConcept()
    {
        return $this->concept;
    }

    /**
     * @param mixed $concept
     */
    public function setConcept($concept)
    {
        $this->concept = $concept;
    }

    /**
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param mixed $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    function __toString()
    {
        return "Concepto: ".$this->getConcept()."\n".
        "Descripcion: ".$this->getDefinition()."\n".
            "Materia correspondiente: ".$this->getIdMatter();
    }


}