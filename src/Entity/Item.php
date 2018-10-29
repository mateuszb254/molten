<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stores pool of available items to add as ShopProduct
 *
 * @ORM\Entity
 */
class Item
{
    /**
     * @ORM\Column(name="vnum", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $vnum = '0';

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type = '0';

    /**
     * @ORM\Column(name="subtype", type="integer", nullable=false)
     */
    private $subtype = '0';

    /**
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight = '0';

    /**
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size = '0';

    /**
     * @ORM\Column(name="antiflag", type="integer", nullable=true)
     */
    private $antiflag = '0';

    /**
     * @ORM\Column(name="flag", type="integer", nullable=true)
     */
    private $flag = '0';

    /**
     * @ORM\Column(name="wearflag", type="integer", nullable=true)
     */
    private $wearflag = '0';
    
    /**
     * @ORM\Column(name="gold", type="integer", nullable=true)
     */
    private $gold = '0';

    /**
     * @ORM\Column(name="shop_buy_price", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $shopBuyPrice = '0';

    /**
     * @ORM\Column(name="refined_vnum", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $refinedVnum = '0';

    /**
     * @ORM\Column(name="refine_set", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $refineSet = '0';

    /**
     * @ORM\Column(name="refine_set2", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $refineSet2 = '0';

    /**
     * @ORM\Column(name="magic_pct", type="smallint", nullable=false)
     */
    private $magicPct = '0';

    /**
     * @ORM\Column(name="limittype0", type="smallint", nullable=true)
     */
    private $limittype0 = '0';

    /**
     * @ORM\Column(name="limitvalue0", type="integer", nullable=true)
     */
    private $limitvalue0 = '0';

    /**
     * @ORM\Column(name="limittype1", type="smallint", nullable=true)
     */
    private $limittype1 = '0';

    /**
     * @ORM\Column(name="limitvalue1", type="integer", nullable=true)
     */
    private $limitvalue1 = '0';

    /**
     * @ORM\Column(name="applytype0", type="smallint", nullable=true)
     */
    private $applytype0 = '0';

    /**
     * @ORM\Column(name="applyvalue0", type="integer", nullable=true)
     */
    private $applyvalue0 = '0';

    /**
     * @ORM\Column(name="applytype1", type="smallint", nullable=true)
     */
    private $applytype1 = '0';

    /**
     * @ORM\Column(name="applyvalue1", type="integer", nullable=true)
     */
    private $applyvalue1 = '0';

    /**
     * @ORM\Column(name="applytype2", type="smallint", nullable=true)
     */
    private $applytype2 = '0';

    /**
     * @ORM\Column(name="applyvalue2", type="integer", nullable=true)
     */
    private $applyvalue2 = '0';

    /**
     * @ORM\Column(name="value0", type="integer", nullable=true)
     */
    private $value0 = '0';

    /**
     * @ORM\Column(name="value1", type="integer", nullable=true)
     */
    private $value1 = '0';

    /**
     * @ORM\Column(name="value2", type="integer", nullable=true)
     */
    private $value2 = '0';

    /**
     * @ORM\Column(name="value3", type="integer", nullable=true)
     */
    private $value3 = '0';

    /**
     * @ORM\Column(name="value4", type="integer", nullable=true)
     */
    private $value4 = '0';

    /**
     * @ORM\Column(name="value5", type="integer", nullable=true)
     */
    private $value5 = '0';

    /**
     * @ORM\Column(name="socket0", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket0 = '-1';

    /**
     * @ORM\Column(name="socket1", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket1 = '-1';

    /**
     * @ORM\Column(name="socket2", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket2 = '-1';

    /**
     * @ORM\Column(name="socket3", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket3 = '-1';

    /**
     * @ORM\Column(name="socket4", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket4 = '-1';

    /**
     * @ORM\Column(name="socket5", type="smallint", nullable=true, options={"default"="-1"})
     */
    private $socket5 = '-1';

    /**
     * @ORM\Column(name="specular", type="smallint", nullable=false)
     */
    private $specular = '0';

    /**
     * @ORM\Column(name="socket_pct", type="smallint", nullable=false)
     */
    private $socketPct = '0';

    /**
     * @ORM\Column(name="addon_type", type="smallint", nullable=false)
     */
    private $addonType = '0';

    public function getVnum(): int
    {
        return $this->vnum;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
