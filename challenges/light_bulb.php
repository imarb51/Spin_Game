<?php
function bulbs_on_after_operations($n = 100) {
    $count = 0;
    for ($i = 1; $i <= $n; $i++) {
        if (sqrt($i) == floor(sqrt($i))) {
            $count++;
        }
    }
    return $count;
}

// Example usage
echo bulbs_on_after_operations();  // Output: 10
?>
