<?php


class Vizsga {

    private $nyelv;//string
    private $evek=array();
    function __construct($nyelv, $evek) {
        $this->nyelv = $nyelv;
        $this->evek = $evek;
    }
    function getNyelv() {
        return $this->nyelv;
    }

    function getEvek() {
        return $this->evek;
    }

    function setNyelv($nyelv): void {
        $this->nyelv = $nyelv;
    }

    function setEvek($evek): void {
        $this->evek = $evek;
    }

    function sumAllYear(){
        return array_sum($this->evek);
    }
}
