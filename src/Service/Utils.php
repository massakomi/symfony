<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class Utils {

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function exampleSession()
    {
        $session = $this->requestStack->getSession();

        // stores an attribute in the session for later reuse
        $session->set('foo', 'attribute-value');

        // gets an attribute by name
        $foo = $session->get('foo');

        // the second argument is the value returned when the attribute doesn't exist
        $filters = $session->get('filters', []);

        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function load404($siteUrl)
    {
        $path = substr($_SERVER['REQUEST_URI'], 1);
        $path = preg_replace('~\?.*~i', '', $path);
        if (strpos($path, '.')) {
            $url = $siteUrl.$path;
        } else {
            return ;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);

        fwrite($a = fopen('log.txt', 'a+'), "\n".date('Y-m-d H:i:s').' '.$url); fclose($a);

        //if ($content) {
            $dirs = explode('/', $path);
            $d = '';
            foreach ($dirs as $k => $v) {
                if ($d) {
                    $d .= '/';
                }
                $d .= $v;
                if (file_exists($d)) {
                    continue;
                }
                if (preg_match('~\..{2,4}$~i', $d)) {
                    continue;
                }
                mkdir($d);
            }
            fwrite($a = fopen(urldecode($path), 'w+'), $content); fclose($a);
        /*} else {
            echo 'empty content '.$url;
        }*/
        return true;
    }
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