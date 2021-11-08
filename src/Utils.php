<?php

namespace App;

/**
 */
class Utils
{

    /**
     * {@inheritdoc}
     */
    function testJoins($conn)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection();

        $sql = 'select * from product p
        INNER JOIN product_options po ON p.id=po.id_product';
        Utils::test($conn, $sql, $h1='INNER JOIN');

        $sql = 'select * from product p
        LEFT JOIN product_options po ON p.id=po.id_product';
        Utils::test($conn, $sql, $h1='LEFT JOIN');

        $sql = 'select * from product p
        RIGHT JOIN product_options po ON p.id=po.id_product';
        Utils::test($conn, $sql, $h1='RIGHT JOIN');
    }


    /**
     * {@inheritdoc}
     */
    public static function test($conn, $sql, $h1)
    {
        echo '<div><b>'.$h1.'</b></div>';
        // returns an array of arrays (i.e. a raw data set)
        $data = $conn->fetchAllAssociative($sql);
        echo self::printTable($data);
        echo '<hr />';
    }

    /*
        assocTable

        echo printTable($table, [
            'htmlspecialchars' => 0,
            'class' => 'table table-condensed',
            'style' => 'width:auto; margin:0 auto',
            'headers' => 0,
            'callbackValue' => function($header, $value) {
                if ($header == 'login') {
                    $value = '<a href="?page=logs&login='.$value.'">'.$value.'</a>';
                }
                return $value;
            }
        ]);
    */
    function printTable($offersData, $opts=[])
    {
        if (!$offersData) {
            echo '<p>Пустой массив</p>';
            return ;
        }
        $hsc = isset($opts['htmlspecialchars']) ? $opts['htmlspecialchars'] : true;
        $hdr = isset($opts['headers']) ? $opts['headers'] : true;
        $attrs = '';
        if (@$opts['style']) {
        	$attrs = ' style="'.$opts['style'].'"';
        }
    /*
        // Вариант шапки без бутстрапа
        echo '
        <style type="text/css">
        table.tt {empty-cells:show; border-collapse:collapse; margin:10px 0}
        table.tt td {border:1px solid #ccc; padding: 3px; vertical-align: top;}
        table.tt tr:nth-child(odd) {background-color:#eee; }
        </style>
        <table class="tt">';
    */
        $class = @$opts['class'] ?: 'table table-bordered table-condensed table-sm table-hover';
        echo '
        <table class="'.$class.'" '.$attrs.'>';
        $headers = array();
        foreach ($offersData as $vals) {
            if (is_array($vals)) {
                foreach ($vals as $k => $v) {
                	$headers [$k]= $k;
                }
            }
        }
        if ($hdr) {
            echo '<tr>';
            foreach ($headers as $k => $v) {
            	echo '<th>'.($hsc ? htmlspecialchars($k) : $k).'</th>';
            }
        }
        echo '</tr>';
        foreach ($offersData as $vals) {
            echo '<tr>';
            if (is_array($vals)) {
                foreach ($headers as $header) {
                    $v = $vals[$header];
                    $v = $hsc ? htmlspecialchars($v) : $v;
                    if (@$opts['callbackValue']) {
                    	$v = call_user_func($opts['callbackValue'], $header, $v);
                    }
                	echo '<td>'.$v.'</td>';
                }
            } else {
                echo '<td>'.$vals.'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
