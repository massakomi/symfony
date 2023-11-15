<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TableViewExtension extends AbstractExtension
{

    public function __construct()
    {

    }

    public function getFilters()
    {
        return [
            new TwigFilter('tableView', [$this, 'tableView']),
        ];
    }

    public function tableView($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        if (is_object($value)) {
            if (method_exists($value, 'getName')) {
                return $value->getName();
            }
            if (method_exists($value, 'getTitle')) {
                return $value->getTitle();
            }
            if ($value instanceof \Doctrine\ORM\PersistentCollection) {
                return $value->count();
            }
            return get_class($value);
        }
        if (is_bool($value)) {
            return $value ? 'Да' : '';
        }
        return $value;
    }
}