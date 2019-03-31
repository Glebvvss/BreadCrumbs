<?php 

namespace BreadCrumbs;

interface ICrumbSet
{
    public function getAll() : array;

    public function getCurrent() : Crumb;

    public function getFirst() : Crumb;

    public function getNext() : Crumb;

    public function getPrev() : Crumb;

    public function getLast() : Crumb;

    public function add(Crumb $crumb) : void;

    public function clear() : void;
}