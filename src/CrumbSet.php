<?php 

namespace BreadCrumbs;

use BreadCrumbs\ICrumbSet;

class CrumbSet implements ICrumbSet
{
    private $crumbList;

    public function getAll() : array
    {
        return $this->crumbList;
    }

    public function getCurrent() : Crumb
    {
        return current($this->crumbList);
    }

    public function getFirst() : Crumb
    {
        return reset($this->crumbList);
    }

    public function getNext() : Crumb
    {
        return next($this->crumbList);
    }

    public function getPrev() : Crumb
    {
        return prev($this->crumbList);
    }

    public function getLast() : Crumb
    {
        return end($this->crumbList);
    }

    public function add(Crumb $crumb) : void
    {
        $this->crumbList[] = $crumb;
    }

    public function clear() : void
    {
        $this->crumbList = [];
    }
}