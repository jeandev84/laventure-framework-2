<?php
namespace Laventure\Component\Database\ORM\Manager\Fixtures\Contract;


use Laventure\Component\Database\ORM\Manager\Contract\ObjectManager;

/**
 * @Fixture
*/
interface Fixture
{
    /**
     * Load data
     *
     * @param ObjectManager $manager
     * @return void
    */
    public function load(ObjectManager $manager);
}