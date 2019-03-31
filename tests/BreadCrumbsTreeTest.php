<?php 

use PHPUnit\Framework\TestCase;

use BreadCrumbs\Crumb;
use BreadCrumbs\CrumbSet;
use BreadCrumbs\BreadCrumbsTree;

class BreadCrumbsTreeTest extends TestCase
{
    public function test_checkCrumbByName_successLevel1() : BreadCrumbsTree
    {
        $bct = new BreadCrumbsTree();

        $crumb = new Crumb();
        $crumb->setTitle('home');
        $crumb->setRoute('/');
        
        $bct->addCrumb($crumb, 'home');

        $this->assertTrue($bct->checkCrumbByName('home'));

        return $bct;
    }

    /**
     * @depends test_checkCrumbByName_successLevel1
     */
    public function test_checkCrumbByName_successLevel2(BreadCrumbsTree $bct) : void
    {
        $crumbLevel2 = new Crumb();
        $crumbLevel2->setTitle('categories');
        $crumbLevel2->setRoute('/categories');

        $bct->addCrumb($crumbLevel2, 'categories', 'home');

        $this->assertTrue($bct->checkCrumbByName('categories'));
    }

    public function test_addCrumb_successLevel1() : BreadCrumbsTree
    {
        $bct = new BreadCrumbsTree();

        $crumb = new Crumb();
        $crumb->setTitle('Home');
        $crumb->setRoute('/');

        $bct->addCrumb($crumb, 'home');

        $this->assertEquals($crumb, $bct->getCrumbByName('home'));

        return $bct;
    }

    /**
     * @depends test_addCrumb_successLevel1
     */
    public function test_addCrumb_successLevel2(BreadCrumbsTree $bct) : BreadCrumbsTree
    {
        $crumb = new Crumb();
        $crumb->setTitle('Categories');
        $crumb->setRoute('/categories');

        $bct->addCrumb($crumb, 'categories', 'home');

        $this->assertEquals($crumb, $bct->getCrumbByName('categories'));

        return $bct;
    }

    /**
     * @depends test_addCrumb_successLevel2
     */
    public function test_removeCrumb_seccessLevel2(BreadCrumbsTree $bct) : BreadCrumbsTree
    {
        $bct->removeCrumb('categories');

        $this->assertFalse($bct->checkCrumbByName('categories'));

        return $bct;
    }

    /**
     * @depends test_removeCrumb_seccessLevel2
     */
    public function test_removeCrumb_seccessLevel1(BreadCrumbsTree $bct) : void
    {
        $bct->removeCrumb('home');

        $this->assertFalse($bct->checkCrumbByName('home'));
    }   

    public function test_getCrumbsByUri_rootUri() : BreadCrumbsTree
    {
        $crumb = new Crumb();
        $crumb->setTitle('Home');
        $crumb->setRoute('/');

        $bct = new BreadCrumbsTree();
        $bct->addCrumb($crumb, 'home');

        $crumbSet = new CrumbSet();
        $crumbSet->add($crumb);

        $this->assertEquals($crumbSet, $bct->getCrumbsByUri('/'));

        return $bct;
    }

    public function test_getCrumbsByUri_level2() : void
    {
        $rootCrumb = new Crumb();
        $rootCrumb->setTitle('Home');
        $rootCrumb->setRoute('/');

        $level2Crumb = new Crumb();
        $level2Crumb->setTitle('Categories');
        $level2Crumb->setRoute('/categories');

        $bct = new BreadCrumbsTree();
        $bct->addCrumb($rootCrumb, 'home');
        $bct->addCrumb($level2Crumb, 'categories', 'home');

        $crumbSet = new CrumbSet();
        $crumbSet->add($bct->getCrumbByName('home'));
        $crumbSet->add($bct->getCrumbByName('categories'));

        $this->assertEquals($crumbSet, $bct->getCrumbsByUri('/categories'));
    }
}