<?php 

namespace BreadCrumbs;

class Crumb
{
    private $title;
    private $route;
    private $children = [];

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getTitle() : string 
    {
        return $this->title;
    }

    public function setRoute(string $route) : void
    {
        $this->route = $route;
    }

    public function getRoute() : string
    {
        return $this->route;
    }

    public function getChildren() : array
    {
        return $this->children;
    }

    public function addChild(string $name, Crumb $crumb) : void
    {
        $this->children[$name] = $crumb;
    }

    public function removeChild(string $name): void
    {
        unset($this->children[$name]);
    }
}