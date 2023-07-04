<?php
/**
 * Undocumented function
 *
 * @param float $param1 
 * @param float $param2 
 * @param boolean $bool 
 * @return integer|boolean 
 */
function doSomething(float $param1, float $param2, bool $bool = false) :int|bool {
    global $var;
    $param1 *= 2;
    $param2 += $var;
    if ($param2 > 17) return false;
    if ($bool) $param2 *= 10;
    return $param1 + $param2;
}


function isEven($v) {
    return $v % 2 === 0;
}

function myFunc(int $x) :void {
    $x += 2;
    var_dump('myFunc', $x);
}


function myFunRef(int &$x) :void {
    $x += 2;
    var_dump('myFunRef', $x);
}
