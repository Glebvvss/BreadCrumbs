<?php

namespace BreadCrumbs;

use BreadCrumbs\Crumb;
use BreadCrumbs\CrumbSet;

interface IBreadCrumbsTree
{
    public function addCrumb(Crumb $crumb, string $crumbName, ?string $parentCrumbName = null) : void;

    public function getCrumbByName(string $crumbName) : Crumb;

    public function removeCrumb(string $crumbName) : void;

    public function isRootCrumb(string $crumbName) : bool;

    public function getCrumbsByUri(string $uri) : CrumbSet;

    public function checkCrumbByName(string $crumbName) : bool;
}