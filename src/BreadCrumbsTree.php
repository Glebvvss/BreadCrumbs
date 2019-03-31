<?php 

namespace BreadCrumbs;

use BreadCrumbs\Crumb;
use BreadCrumbs\IBreadCrumbsTree;

class BreadCrumbsTree implements IBreadCrumbsTree
{
    protected $crumbTree = [];

    public function addCrumb(Crumb $crumb, string $crumbName, ?string $parentCrumbName = null) : void
    {
        if (! empty($this->crumbTree) && empty($parentCrumbName)) {
            throw new \Exception('Crumb tree must contains only one root element');
        }

        if ($crumbName === '') {
            throw new \Exception('Crumb key cannot be empty string.');
        }

        if ($this->checkCrumbByName($crumbName)) {
            throw new \Exception('This crumb key already used.');
        }

        if ($parentCrumbName === null) {
            $this->crumbTree[$crumbName] = $crumb;
            return;
        }

        $traversalTree = function($parentName, $tree, $traversalTree = null) use (&$crumb, $crumbName) {
            foreach($tree as $nodeKey => $node) {
                if ($nodeKey === $parentName) {
                    $node->addChild($crumbName, $crumb);
                }
                elseif($node->getChildren()) {
                    $traversalTree($name, $node->getChildren(), $traversalTree);
                }
            }
        };

        $traversalTree($parentCrumbName, $this->crumbTree, $traversalTree);
    }

    public function getCrumbByName(string $crumbName) : Crumb
    {
        $crumbEntity = false;

        $traversalTree = function($name, $tree, $traversalTree = null) use (&$crumbEntity) {
            foreach($tree as $nodeKey => $node) {
                if ($nodeKey === $name) {
                    $crumbEntity = $node;
                }
                elseif(! empty($node->getChildren())) {
                    $traversalTree($name, $node->getChildren(), $traversalTree);
                }
            }
        };

        $traversalTree($crumbName, $this->crumbTree, $traversalTree);

        if ($crumbEntity === false) {
            throw new \Exception('Crumb entity does not exists.');
        }

        return $crumbEntity;
    }

    public function removeCrumb(string $crumbName) : void
    {
        if ($this->isRootCrumb($crumbName)) {
            $this->crumbTree = [];
            return;
        }

        $traversalTree = function($name, $tree, $traversalTree = null) {
            foreach($tree as $nodeKey => $node) {
                if (empty($node->getChildren())) {
                    continue;
                }

                foreach($node->getChildren() as $subnodeKey => $subnode) {
                    if ($subnodeKey === $name) {
                        $node->removeChild($name);
                    }
                    else {
                        $traversalTree($name, $subnode->getChildren(), $traversalTree);
                    }
                }
            }
        };

        $traversalTree($crumbName, $this->crumbTree, $traversalTree);
    }

    public function isRootCrumb(string $crumbName) : bool
    {
        $crumbNamesLevel1 = array_keys($this->crumbTree);

        if (in_array($crumbName, $crumbNamesLevel1)) {
            return true;
        }

        return false;
    }

    public function getCrumbsByUri(string $uri) : CrumbSet
    {
        $matchedCrumbNames = [];

        $buildCrumbs = function(string $uri, array $tree, array $crumbs = [], $recurciveBuildCrumbs) use (&$matchedCrumbNames) {
            foreach($tree as $crumbName => $node) {
                $crumbs[] = $crumbName;
                if ($node->getRoute() === $uri) {
                    $matchedCrumbNames = $crumbs;
                }
                elseif(! empty($node->getChildren())) {
                    $recurciveBuildCrumbs($uri, $node->getChildren(), $crumbs, $recurciveBuildCrumbs);
                }
            }           
        };

        $buildCrumbs($uri, $this->crumbTree, [], $buildCrumbs);

        return $this->generateCrumbSet($matchedCrumbNames);
    }

    public function checkCrumbByName(string $crumbName) : bool
    {
        $check = false;

        $traversalTree = function($name, $tree, $traversalTree = null) use (&$check) {
            foreach($tree as $nodeKey => $node) {
                if ($nodeKey === $name) {
                    $check = true;
                }
                elseif(! empty($node->getChildren())) {
                    $traversalTree($name, $node->getChildren());
                }
            }
        };

        $traversalTree($crumbName, $this->crumbTree, $traversalTree);

        return $check;
    }

    private function generateCrumbSet(array $crumbNames) : CrumbSet
    {
        $crumbSet = new CrumbSet();
        foreach($crumbNames as $crumbName) {
            $crumbSet->add($this->getCrumbByName($crumbName));
        }       

        return $crumbSet;
    }
}