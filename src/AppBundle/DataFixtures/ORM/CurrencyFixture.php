<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Difficulty;
use AppBundle\Entity\Currency;

class CurrencyFixture extends AbstractFixture implements OrderedFixtureInterface
{

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
	 */
	public function load(ObjectManager $manager)
	{		
		$data = [
		    [
		        'name' => 'Dollar Américain',
		        'code' => 'USD',
		        'symbol' => '$',
		    ],
		    [
		      'name' => 'Euro',
		      'code' => 'EUR',
		      'symbol' => '€',
		    ],
		    [
		      'name' => 'Dollar Canadien',
		      'code' => 'CAD',
		      'symbol' => 'C$',
		    ],
		    [
		    'name' => 'Franc Guinéen',
		    'code' => 'GNF',
		    'symbol' => 'FG',
		    ],
		    [
		    'name' => 'Franc CFA (UEMOA)',
		    'code' => 'XOF',
		    'symbol' => 'F',
		    ],
		];
		
		foreach ($data as $value) {
		    $currency = new Currency();
		    $currency->setName($value['name']);
		    $currency->setCode($value['code']);
		    $currency->setSymbol($value['symbol']);
		    
		    $manager->persist($currency);
		}
		$manager->flush();	 
	}

	public function getOrder()
	{
		return 1;
	}
}