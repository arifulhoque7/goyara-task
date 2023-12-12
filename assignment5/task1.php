
<?php
function sumOfEvenNumbers($arr) {
    $sum = 0;
    foreach ($arr as $num) {
        if ($num % 2 === 0) { 
            $sum += $num;
        }
    }
    return $sum;
}

echo sumOfEvenNumbers([1, 2, 3, 4, 5, 6]);
?>